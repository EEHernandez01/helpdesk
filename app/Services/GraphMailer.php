<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * GraphMailer
 *
 * Pequeño servicio para enviar correos usando Microsoft Graph y OAuth2 (client credentials).
 * Funciona dentro de Laravel (usa Cache si está disponible) o fuera (usa archivo en storage/).
 */
class GraphMailer
{
    protected Client $http;
    protected string $tenant;
    protected string $clientId;
    protected string $clientSecret;
    protected string $sender;

    public function __construct(?Client $http = null)
    {
        // Configurar cliente HTTP con soporte para certificados en Windows
        if ($http) {
            $this->http = $http;
        } else {
            $this->http = $this->buildHttpClient();
        }
        $this->tenant = $this->envOrConfig('AZURE_TENANT_ID', 'services.microsoft.tenant_id');
        $this->clientId = $this->envOrConfig('AZURE_CLIENT_ID', 'services.microsoft.client_id');
        $this->clientSecret = $this->envOrConfig('AZURE_CLIENT_SECRET', 'services.microsoft.client_secret');
        $this->sender = $this->envOrConfig('MAIL_FROM_ADDRESS', 'mail.from.address') ?: '';
    }

    /**
     * Construye un cliente Guzzle con manejo de CA bundle/SSL para entornos Windows/locales.
     */
    protected function buildHttpClient(): Client
    {
        $options = [
            'timeout' => 15,
        ];

        // Permitir controlar la verificación SSL vía env
        // HTTP_VERIFY=true|false o ruta a cacert.pem vía CURL_CA_BUNDLE/SSL_CERT_FILE
        $httpVerify = getenv('HTTP_VERIFY');
        $caBundle = getenv('CURL_CA_BUNDLE') ?: getenv('SSL_CERT_FILE');

        if ($httpVerify !== false) {
            $val = strtolower((string)$httpVerify);
            // Acepta 0/1, true/false
            if (in_array($val, ['0', 'false', 'no'], true)) {
                $options['verify'] = false;
            } elseif (in_array($val, ['1', 'true', 'yes'], true)) {
                // usar verify por defecto (true) o ruta si existe
                if ($caBundle && is_file($caBundle)) {
                    $options['verify'] = $caBundle;
                }
            }
        } elseif ($caBundle && is_file($caBundle)) {
            // Si el sistema expone un CA bundle, úsalo
            $options['verify'] = $caBundle;
        } elseif ((getenv('APP_ENV') ?: '') === 'local' && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Entorno local en Windows: intenta usar un cacert local si existe, si no, deshabilita como último recurso
            $localCacert = __DIR__ . '/../../storage/cacert/cacert.pem';
            if (is_file($localCacert)) {
                $options['verify'] = $localCacert;
            } else {
                // Último recurso para desarrollo local: deshabilitar verificación
                $options['verify'] = false;
            }
        }

        return new Client($options);
    }

    protected function envOrConfig(string $envKey, string $configKey): string
    {
        // Primero intentar variables de entorno (útil cuando se ejecuta fuera de Laravel)
        $val = $_ENV[$envKey] ?? $_SERVER[$envKey] ?? getenv($envKey);
        if (!empty($val)) {
            return (string)$val;
        }

        // Si estamos dentro de Laravel, intentar obtener desde config()
        if (function_exists('config')) {
            try {
                $cfg = config($configKey);
                if (!empty($cfg)) {
                    return (string)$cfg;
                }
            } catch (\Throwable $e) {
                // ignore and fall through to empty string
            }
        }

        return '';
    }

    protected function cacheGet(string $key)
    {
        if (class_exists(\Illuminate\Support\Facades\Cache::class)) {
            try {
                return \Illuminate\Support\Facades\Cache::get($key);
            } catch (\Throwable $e) {
                // fallthrough to file cache
            }
        }
        $file = $this->cacheFile($key);
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data['value']) && isset($data['expires_at']) && $data['expires_at'] > time()) {
                return $data['value'];
            }
        }
        return null;
    }

    protected function cachePut(string $key, $value, int $seconds)
    {
        if (class_exists(\Illuminate\Support\Facades\Cache::class)) {
            try {
                return \Illuminate\Support\Facades\Cache::put($key, $value, $seconds);
            } catch (\Throwable $e) {
                // fallthrough to file cache
            }
        }
        $file = $this->cacheFile($key);
        $data = ['value' => $value, 'expires_at' => time() + $seconds];
        @file_put_contents($file, json_encode($data));
        return true;
    }

    protected function cacheFile(string $key): string
    {
        $base = __DIR__ . '/../../storage/app';
        if (!is_dir($base)) {
            @mkdir($base, 0755, true);
        }
        $name = preg_replace('/[^a-z0-9_\-]/i', '_', $key);
        return $base . "/graph_token_{$name}.json";
    }

    protected function getToken(): string
    {
        $cacheKey = "microsoft_graph_token_{$this->clientId}";
        $cached = $this->cacheGet($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }

        if (empty($this->tenant) || empty($this->clientId) || empty($this->clientSecret)) {
            throw new \RuntimeException('Faltan variables de entorno AZURE_TENANT_ID, AZURE_CLIENT_ID o AZURE_CLIENT_SECRET');
        }

        $url = "https://login.microsoftonline.com/{$this->tenant}/oauth2/v2.0/token";
        $resp = $this->http->post($url, [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode((string)$resp->getBody(), true);
        if (empty($data['access_token'])) {
            throw new \RuntimeException('No se obtuvo access_token de Azure: ' . json_encode($data));
        }
        $ttl = isset($data['expires_in']) ? max(60, intval($data['expires_in']) - 60) : 300;
        $this->cachePut($cacheKey, $data['access_token'], $ttl);
        return $data['access_token'];
    }

    /**
     * Enviar correo.
     * @param string $subject
     * @param array $to array de direcciones (strings)
     * @param string $body
     * @param bool $isHtml
     * @param string|null $from
     * @param array $cc
     * @param array $bcc
     * @return array
     */
    public function send(string $subject, array $to, string $body, bool $isHtml = false, ?string $from = null, array $cc = [], array $bcc = []): array
    {
        $from = $from ?: $this->sender;
        if (empty($from)) {
            throw new \RuntimeException('No se ha definido remitente (MAIL_FROM_ADDRESS)');
        }

        $token = $this->getToken();

        $toRecipients = array_map(fn($addr) => ['emailAddress' => ['address' => $addr]], $to);
        $ccRecipients = array_map(fn($addr) => ['emailAddress' => ['address' => $addr]], $cc);
        $bccRecipients = array_map(fn($addr) => ['emailAddress' => ['address' => $addr]], $bcc);

        $message = [
            'message' => [
                'subject' => $subject,
                'body' => [
                    'contentType' => $isHtml ? 'HTML' : 'Text',
                    'content' => $body,
                ],
                'toRecipients' => $toRecipients,
            ],
            'saveToSentItems' => true,
        ];
        if (!empty($ccRecipients)) $message['message']['ccRecipients'] = $ccRecipients;
        if (!empty($bccRecipients)) $message['message']['bccRecipients'] = $bccRecipients;

        $url = "https://graph.microsoft.com/v1.0/users/{$from}/sendMail";
        $resp = $this->http->post($url, [
            'headers' => [
                'Authorization' => "Bearer {$token}",
                'Content-Type' => 'application/json',
            ],
            'json' => $message,
        ]);

        return ['status' => $resp->getStatusCode(), 'body' => (string)$resp->getBody()];
    }
}
