<?php
// scripts/send_test_mail.php
// Usage: php scripts/send_test_mail.php recipient@example.com "Subject" "<p>HTML body</p>"

# Simple CLI parser for --key=value or --key value pairs
// If user passed --help, print usage
if (in_array('--help', $argv) || in_array('-h', $argv)) {
    echo "Usage: php scripts/send_test_mail.php [to] [subject] [body] [--mail-host=HOST] [--mail-port=PORT] [--mail-encryption=tls] [--mail-username=USER] [--mail-password=PASS] [--mail-from=FROM] [--mail-from-name=NAME]\n";
    exit(0);
}

$rawArgs = $argv;
array_shift($rawArgs); // remove script name

// Collect positional args first (to, subject, body) until a flag appears
$positional = [];
while (count($rawArgs) > 0 && strpos($rawArgs[0], '--') !== 0) {
    $positional[] = array_shift($rawArgs);
}


$overrides = [];
while (count($rawArgs) > 0) {
    $arg = array_shift($rawArgs);
    if (strpos($arg, '--') === 0) {
        $arg = substr($arg, 2);
        if (strpos($arg, '=') !== false) {
            list($k, $v) = explode('=', $arg, 2);
        } else {
            $k = $arg;
            // next token is value if exists and not another flag
            if (count($rawArgs) > 0 && strpos($rawArgs[0], '--') !== 0) {
                $v = array_shift($rawArgs);
            } else {
                $v = true;
            }
        }
        $overrides[$k] = $v;
    }
}

// Map supported override keys to env names
$map = [
    'mail-host' => 'MAIL_HOST',
    'mail-port' => 'MAIL_PORT',
    'mail-encryption' => 'MAIL_ENCRYPTION',
    'mail-username' => 'MAIL_USERNAME',
    'mail-password' => 'MAIL_PASSWORD',
    'mail-from' => 'MAIL_FROM_ADDRESS',
    'mail-from-name' => 'MAIL_FROM_NAME',
];

foreach ($map as $flag => $envName) {
    if (isset($overrides[$flag])) {
        putenv($envName . '=' . $overrides[$flag]);
        $_ENV[$envName] = $overrides[$flag];
        $_SERVER[$envName] = $overrides[$flag];
    }
}

require __DIR__ . '/../vendor/autoload.php';

// Now bootstrap the app so it picks up the overridden env vars
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Boot the console kernel to initialize application (env, config, etc.)
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArrayInput([]),
    new Symfony\Component\Console\Output\NullOutput()
);

// Use facades
use Illuminate\Support\Facades\Mail;
use App\Mail\ProxyMail;
use Illuminate\Support\Facades\Config;

$to = $positional[0] ?? 'elioth.hernandez@genbio.com.mx';
$subject = $positional[1] ?? 'Prueba desde script';
$body = $positional[2] ?? '<p>Mensaje de prueba enviado desde scripts/send_test_mail.php</p>';

echo "Enviando correo de prueba a: $to\n";

// Mostrar configuración de mail tomada desde el .env (enmascarando la contraseña)
$mailHost = env('MAIL_HOST');
$mailPort = env('MAIL_PORT');
$mailEnc = env('MAIL_ENCRYPTION');
$mailUser = env('MAIL_USERNAME');
$mailPass = env('MAIL_PASSWORD');
$mailFrom = env('MAIL_FROM_ADDRESS');
$mailName = env('MAIL_FROM_NAME');

function mask($s) {
    if (! $s) return null;
    return substr($s,0,2) . str_repeat('*', max(3, strlen($s)-2));
}

echo "Usando configuración MAIL:\n";
echo "  host: " . ($mailHost ?? 'n/a') . "\n";
echo "  port: " . ($mailPort ?? 'n/a') . "\n";
echo "  encryption: " . ($mailEnc ?? 'n/a') . "\n";
echo "  username: " . ($mailUser ?? 'n/a') . "\n";
echo "  password: " . (mask($mailPass) ?? 'n/a') . "\n";
echo "  from: " . ($mailFrom ?? 'n/a') . " (name: " . ($mailName ?? 'n/a') . ")\n\n";

try {
    Mail::to($to)->send(new ProxyMail($subject, $body));
    echo "Mail enviado (o intento enviado por el driver). Revisa la bandeja y los logs.\n";
} catch (Throwable $e) {
    echo "Error al enviar: " . $e->getMessage() . "\n";
}

$kernel->terminate($input, $status);
