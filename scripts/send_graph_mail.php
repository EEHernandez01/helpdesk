<?php
// Script de prueba para enviar un correo usando App\Services\GraphMailer
// Uso: php scripts/send_graph_mail.php

require __DIR__ . '/../vendor/autoload.php';

// Carga variables de entorno si existe .env local
if (file_exists(__DIR__ . '/../.env')) {
    $dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotEnv->safeLoad();
}

use App\Services\GraphMailer;

$to = ['elioth.hernandez@genbio.com.mx'];
$subject = 'Prueba GraphMailer';
$body = '<h1>Correo de Prueba</h1><p>Este correo confirma que GraphTransport ha sido corregido correctamente y ahora implementa TransportInterface de Symfony.</p><p>Fecha: ' . date('Y-m-d H:i:s') . '</p>';

$gm = new GraphMailer();
try {
    $res = $gm->send($subject, $to, $body, true); // HTML enabled
    echo "✅ Status: {$res['status']}\n";
    echo "Body: {$res['body']}\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    exit(1);
}
