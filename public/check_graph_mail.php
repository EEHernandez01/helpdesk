<?php
// Standalone check script for Microsoft Graph mail connectivity.
// Place this file in the `public/` folder and open in a browser or via the hosting panel.
// Protect it by setting MAIL_TEST_SECRET in your .env or editing $FALLBACK_SECRET below.

declare(strict_types=1);

// Try to load composer autoload - if it fails, show a clear message
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Missing vendor/autoload.php. Run composer install or upload vendor folder.\n";
    exit(1);
}
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

// Improve error visibility for debugging (remove or reduce in production)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
set_exception_handler(function($e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Unhandled exception: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo "In " . $e->getFile() . " on line " . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
    exit(1);
});

// Fallback secret - change this to a long random value if you can't edit .env
$FALLBACK_SECRET = 'change-me-to-a-long-random-string-2025';

// Load .env if available
if (file_exists(__DIR__ . '/../.env')) {
    try {
        $dot = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dot->safeLoad();
    } catch (Exception $e) {
        // ignore
    }
}

function getenv_safe(string $k): ?string
{
    $v = $_ENV[$k] ?? $_SERVER[$k] ?? getenv($k);
    return $v === false ? null : $v;
}

$secretEnv = getenv_safe('MAIL_TEST_SECRET');
$secret = $secretEnv ?: $FALLBACK_SECRET;

$provided = $_GET['secret'] ?? $_POST['secret'] ?? null;
if (!$provided || !hash_equals((string)$secret, (string)$provided)) {
    http_response_code(403);
    echo "Forbidden - secret missing or invalid\n";
    exit;
}

// Read essential config
$tenant = getenv_safe('AZURE_TENANT_ID') ?: ''; 
$clientId = getenv_safe('AZURE_CLIENT_ID') ?: '';
$clientSecret = getenv_safe('AZURE_CLIENT_SECRET') ?: '';
$from = getenv_safe('MAIL_FROM_ADDRESS') ?: '';

header('Content-Type: text/plain; charset=utf-8');

echo "Graph Mail Connectivity Check\n";
echo "----------------------------\n";

if (empty($tenant) || empty($clientId) || empty($clientSecret) || empty($from)) {
    echo "Missing configuration. Please set AZURE_TENANT_ID, AZURE_CLIENT_ID, AZURE_CLIENT_SECRET and MAIL_FROM_ADDRESS in .env\n";
    exit(1);
}

$guzzle = new Client(['timeout' => 15]);

// 1) Get token
$tokenUrl = "https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token";
try {
    $resp = $guzzle->post($tokenUrl, [
        'form_params' => [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
            'grant_type' => 'client_credentials',
        ],
        // 'verify' => false, // uncomment only for local debugging
    ]);
    $body = json_decode((string)$resp->getBody(), true);
    if (!empty($body['access_token'])) {
        echo "Token: OK\n";
        $expires = $body['expires_in'] ?? null;
        echo "Expires in: " . ($expires ? $expires . " seconds\n" : "unknown\n");
    } else {
        echo "Token: NO - response did not contain access_token\n";
        echo "Response: " . json_encode($body) . "\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "Token request failed: " . $e->getMessage() . "\n";
    if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->getResponse()) {
        echo "Response: " . (string)$e->getResponse()->getBody() . "\n";
    }
    exit(1);
}

// If action=send, perform a single test send. Provide ?action=send&to=addr@example.com
$action = $_GET['action'] ?? null;
if ($action === 'send') {
    $to = $_GET['to'] ?? null;
    if (!$to) {
        echo "Missing 'to' parameter for send action\n";
        exit(1);
    }

    $accessToken = $body['access_token'];
    $sendUrl = "https://graph.microsoft.com/v1.0/users/{$from}/sendMail";
    $message = [
        'message' => [
            'subject' => 'Prueba de conexión desde Neubox',
            'body' => [
                'contentType' => 'Text',
                'content' => 'Mensaje de prueba enviado por Graph via script de comprobación.',
            ],
            'toRecipients' => [[ 'emailAddress' => ['address' => $to] ]],
        ],
        'saveToSentItems' => true,
    ];

    try {
        $r = $guzzle->post($sendUrl, [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => $message,
        ]);
        echo "Send status: " . $r->getStatusCode() . "\n";
        echo "Send response body: " . (string)$r->getBody() . "\n";
    } catch (Exception $e) {
        echo "Send failed: " . $e->getMessage() . "\n";
        if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->getResponse()) {
            echo "Response: " . (string)$e->getResponse()->getBody() . "\n";
        }
        exit(1);
    }
}

echo "All checks passed (token obtained).\n";

// end
