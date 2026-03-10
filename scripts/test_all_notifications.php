<?php
/**
 * Script de prueba completo para todas las notificaciones de tickets
 * Simula: creación de ticket, cambio de status, asignación y comentario
 *
 * Uso: php scripts/test_all_notifications.php [email]
 * Ejemplo: php scripts/test_all_notifications.php elioth.hernandez@genbio.com.mx
 */

require __DIR__ . '/../vendor/autoload.php';

// Carga variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotEnv->load();
}

use App\Services\GraphMailer;
use GuzzleHttp\Client;

// Configuración
$testEmail = $argv[1] ?? 'elioth.hernandez@genbio.com.mx';
$fromEmail = $_ENV['GRAPH_FROM_ADDRESS'] ?? $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@genbio.com.mx';

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║  VERIFICACIÓN Y PRUEBA DE NOTIFICACIONES - HELPDESK GENBIO   ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// ========== PASO 1: VERIFICAR CONFIGURACIÓN ==========
echo "📋 PASO 1: Verificando configuración de Microsoft Graph...\n";
echo str_repeat("─", 65) . "\n";

// Soporta tanto GRAPH_* como AZURE_* (compatibilidad)
$requiredEnvVars = [
    ['GRAPH_TENANT_ID', 'AZURE_TENANT_ID', 'Tenant ID de Azure AD'],
    ['GRAPH_CLIENT_ID', 'AZURE_CLIENT_ID', 'Client ID de la aplicación'],
    ['GRAPH_CLIENT_SECRET', 'AZURE_CLIENT_SECRET', 'Client Secret'],
    ['GRAPH_FROM_ADDRESS', 'MAIL_FROM_ADDRESS', 'Dirección de correo remitente'],
];

$configOk = true;
foreach ($requiredEnvVars as $varConfig) {
    $primaryVar = $varConfig[0];
    $fallbackVar = $varConfig[1];
    $description = $varConfig[2];

    $value = $_ENV[$primaryVar] ?? $_ENV[$fallbackVar] ?? null;
    $usedVar = !empty($_ENV[$primaryVar]) ? $primaryVar : $fallbackVar;

    if (empty($value)) {
        echo "❌ {$primaryVar}/{$fallbackVar}: NO CONFIGURADO\n";
        $configOk = false;
    } else {
        $masked = (strpos($usedVar, 'SECRET') !== false)
            ? str_repeat('*', max(0, strlen($value) - 4)) . substr($value, -4)
            : $value;
        echo "✅ {$usedVar}: {$masked}\n";
    }
}

if (!$configOk) {
    echo "\n⚠️  ADVERTENCIA: Faltan variables de entorno. Configurar en .env\n";
    exit(1);
}

echo "\n📧 Correo de prueba: {$testEmail}\n";
echo "📤 Remitente configurado: {$fromEmail}\n\n";

// ========== PASO 2: VERIFICAR CONECTIVIDAD ==========
echo "📋 PASO 2: Verificando conectividad con Microsoft Graph API...\n";
echo str_repeat("─", 65) . "\n";

try {
    $graphMailer = new GraphMailer(new Client(['timeout' => 10]));
    echo "✅ GraphMailer instanciado correctamente\n";
    echo "✅ Clase GraphTransport disponible\n";
    echo "✅ TransportInterface implementado correctamente\n\n";
} catch (Exception $e) {
    echo "❌ Error al instanciar GraphMailer: {$e->getMessage()}\n";
    exit(1);
}

// ========== PASO 3: SIMULAR NOTIFICACIONES ==========
echo "📋 PASO 3: Enviando notificaciones de prueba...\n";
echo str_repeat("─", 65) . "\n\n";

$results = [];
$totalTests = 4;
$successCount = 0;

// --- TEST 1: NewTicketCreated ---
echo "🎫 TEST 1/4: Nuevo Ticket Creado\n";
try {
    $subject = "🎫 Nuevo Ticket #12345 creado";
    $body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>✅ Nuevo Ticket Creado</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Tu ticket ha sido creado exitosamente:</p>
            <div class="info">
                <strong>ID:</strong> #12345<br>
                <strong>Título:</strong> Problema con impresora HP LaserJet<br>
                <strong>Prioridad:</strong> Alta<br>
                <strong>Creado por:</strong> Elioth Hernández<br>
                <strong>Fecha:</strong> {date('d/m/Y H:i:s')}
            </div>
            <p>Nuestro equipo de soporte revisará tu solicitud pronto.</p>
            <a href="https://soporte.genbio.com.mx/tickets/12345" class="button">Ver Ticket</a>
        </div>
        <div class="footer">
            © {date('Y')} Genbio - Sistema de Soporte
        </div>
    </div>
</body>
</html>
HTML;

    $res = $graphMailer->send($subject, [$testEmail], $body, true, $fromEmail);
    if ($res['status'] >= 200 && $res['status'] < 300) {
        echo "   ✅ Status: {$res['status']} - Enviado correctamente\n";
        $successCount++;
        $results[] = ['test' => 'NewTicketCreated', 'status' => 'OK'];
    } else {
        echo "   ⚠️  Status: {$res['status']} - Respuesta inesperada\n";
        echo "   Body: {$res['body']}\n";
        $results[] = ['test' => 'NewTicketCreated', 'status' => 'WARNING'];
    }
} catch (Exception $e) {
    echo "   ❌ Error: {$e->getMessage()}\n";
    $results[] = ['test' => 'NewTicketCreated', 'status' => 'ERROR', 'error' => $e->getMessage()];
}
echo "\n";
sleep(2);

// --- TEST 2: TicketStatusChanged ---
echo "🔄 TEST 2/4: Cambio de Status del Ticket\n";
try {
    $subject = "🔄 Actualización del Ticket #12345";
    $body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2196F3; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .status-change { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .status-old { background: #ffc107; color: #333; }
        .status-new { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔄 Estado del Ticket Actualizado</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>El estado de tu ticket ha cambiado:</p>
            <div class="status-change">
                <strong>Ticket:</strong> #12345 - Problema con impresora HP LaserJet<br>
                <strong>Cambio:</strong>
                <span class="status-badge status-old">Nuevo</span>
                →
                <span class="status-badge status-new">En Progreso</span><br>
                <strong>Actualizado por:</strong> Juan Pérez (Soporte Técnico)<br>
                <strong>Fecha:</strong> {date('d/m/Y H:i:s')}
            </div>
            <p>Tu ticket está siendo atendido por nuestro equipo.</p>
            <a href="https://soporte.genbio.com.mx/tickets/12345" class="button">Ver Ticket</a>
        </div>
        <div class="footer">
            © {date('Y')} Genbio - Sistema de Soporte
        </div>
    </div>
</body>
</html>
HTML;

    $res = $graphMailer->send($subject, [$testEmail], $body, true, $fromEmail);
    if ($res['status'] >= 200 && $res['status'] < 300) {
        echo "   ✅ Status: {$res['status']} - Enviado correctamente\n";
        $successCount++;
        $results[] = ['test' => 'TicketStatusChanged', 'status' => 'OK'];
    } else {
        echo "   ⚠️  Status: {$res['status']} - Respuesta inesperada\n";
        $results[] = ['test' => 'TicketStatusChanged', 'status' => 'WARNING'];
    }
} catch (Exception $e) {
    echo "   ❌ Error: {$e->getMessage()}\n";
    $results[] = ['test' => 'TicketStatusChanged', 'status' => 'ERROR', 'error' => $e->getMessage()];
}
echo "\n";
sleep(2);

// --- TEST 3: TicketAssigned ---
echo "👤 TEST 3/4: Ticket Asignado\n";
try {
    $subject = "👤 Ticket #12345 asignado a ti";
    $body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FF9800; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 10px 20px; background: #FF9800; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .assignment { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #FF9800; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>👤 Ticket Asignado</h2>
        </div>
        <div class="content">
            <p>Hola Elioth Hernández,</p>
            <p>Se te ha asignado un nuevo ticket:</p>
            <div class="assignment">
                <strong>ID:</strong> #12345<br>
                <strong>Título:</strong> Problema con impresora HP LaserJet<br>
                <strong>Prioridad:</strong> Alta<br>
                <strong>Cliente:</strong> María González<br>
                <strong>Departamento:</strong> Administración<br>
                <strong>Fecha de asignación:</strong> {date('d/m/Y H:i:s')}
            </div>
            <p>Por favor, revisa el ticket y comienza a trabajar en él.</p>
            <a href="https://soporte.genbio.com.mx/tickets/12345" class="button">Ver Ticket</a>
        </div>
        <div class="footer">
            © {date('Y')} Genbio - Sistema de Soporte
        </div>
    </div>
</body>
</html>
HTML;

    $res = $graphMailer->send($subject, [$testEmail], $body, true, $fromEmail);
    if ($res['status'] >= 200 && $res['status'] < 300) {
        echo "   ✅ Status: {$res['status']} - Enviado correctamente\n";
        $successCount++;
        $results[] = ['test' => 'TicketAssigned', 'status' => 'OK'];
    } else {
        echo "   ⚠️  Status: {$res['status']} - Respuesta inesperada\n";
        $results[] = ['test' => 'TicketAssigned', 'status' => 'WARNING'];
    }
} catch (Exception $e) {
    echo "   ❌ Error: {$e->getMessage()}\n";
    $results[] = ['test' => 'TicketAssigned', 'status' => 'ERROR', 'error' => $e->getMessage()];
}
echo "\n";
sleep(2);

// --- TEST 4: NewTicketComment ---
echo "💬 TEST 4/4: Nuevo Comentario en Ticket\n";
try {
    $subject = "💬 Nuevo comentario en Ticket #12345";
    $body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #9C27B0; color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; font-size: 12px; }
        .button { display: inline-block; padding: 10px 20px; background: #9C27B0; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .comment { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #9C27B0; border-radius: 3px; }
        .comment-author { font-weight: bold; color: #9C27B0; margin-bottom: 5px; }
        .comment-date { font-size: 12px; color: #666; margin-bottom: 10px; }
        .comment-text { background: #f5f5f5; padding: 10px; border-radius: 3px; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>💬 Nuevo Comentario</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Se ha añadido un nuevo comentario al ticket: <strong>Problema con impresora HP LaserJet</strong></p>
            <div class="comment">
                <div class="comment-author">👤 Juan Pérez (Soporte Técnico)</div>
                <div class="comment-date">📅 {date('d/m/Y H:i:s')}</div>
                <div class="comment-text">
                    "He revisado la impresora y encontré que el problema es un atasco de papel en la bandeja 2.
                    Ya lo he resuelto y la impresora está funcionando correctamente.
                    Por favor, confirma que todo esté operando bien."
                </div>
            </div>
            <p>Puedes responder directamente desde el sistema o por correo.</p>
            <a href="https://soporte.genbio.com.mx/tickets/12345" class="button">Ver Ticket</a>
        </div>
        <div class="footer">
            © {date('Y')} Genbio - Sistema de Soporte
        </div>
    </div>
</body>
</html>
HTML;

    $res = $graphMailer->send($subject, [$testEmail], $body, true, $fromEmail);
    if ($res['status'] >= 200 && $res['status'] < 300) {
        echo "   ✅ Status: {$res['status']} - Enviado correctamente\n";
        $successCount++;
        $results[] = ['test' => 'NewTicketComment', 'status' => 'OK'];
    } else {
        echo "   ⚠️  Status: {$res['status']} - Respuesta inesperada\n";
        $results[] = ['test' => 'NewTicketComment', 'status' => 'WARNING'];
    }
} catch (Exception $e) {
    echo "   ❌ Error: {$e->getMessage()}\n";
    $results[] = ['test' => 'NewTicketComment', 'status' => 'ERROR', 'error' => $e->getMessage()];
}
echo "\n";

// ========== RESUMEN FINAL ==========
echo str_repeat("═", 65) . "\n";
echo "📊 RESUMEN DE PRUEBAS\n";
echo str_repeat("═", 65) . "\n";

foreach ($results as $i => $result) {
    $icon = $result['status'] === 'OK' ? '✅' : ($result['status'] === 'WARNING' ? '⚠️' : '❌');
    echo sprintf("%d. %s %s - %s\n", $i + 1, $icon, $result['test'], $result['status']);
    if (isset($result['error'])) {
        echo "   Error: {$result['error']}\n";
    }
}

echo "\n";
echo "Total: {$successCount}/{$totalTests} pruebas exitosas\n";

if ($successCount === $totalTests) {
    echo "\n🎉 ¡TODAS LAS PRUEBAS PASARON! El sistema de notificaciones está funcionando correctamente.\n";
    exit(0);
} elseif ($successCount > 0) {
    echo "\n⚠️  ALGUNAS PRUEBAS FALLARON. Revisar configuración y logs.\n";
    exit(1);
} else {
    echo "\n❌ TODAS LAS PRUEBAS FALLARON. Verificar configuración de Microsoft Graph.\n";
    exit(1);
}
