<?php
/**
 * Script para verificar que todas las notificaciones estén correctamente
 * configuradas para trabajar con Microsoft Graph Transport
 *
 * Uso: php scripts/verify_notifications_config.php
 */

require __DIR__ . '/../vendor/autoload.php';

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║     VERIFICACIÓN DE CONFIGURACIÓN DE NOTIFICACIONES          ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$checks = [];
$errors = [];
$warnings = [];

// ========== CHECK 1: Verificar archivos de notificación ==========
echo "📋 CHECK 1: Archivos de Notificaciones\n";
echo str_repeat("─", 65) . "\n";

$notificationFiles = [
    'NewTicketCreated' => __DIR__ . '/../app/Notifications/NewTicketCreated.php',
    'TicketStatusChanged' => __DIR__ . '/../app/Notifications/TicketStatusChanged.php',
    'TicketAssigned' => __DIR__ . '/../app/Notifications/TicketAssigned.php',
    'NewTicketComment' => __DIR__ . '/../app/Notifications/NewTicketComment.php',
];

foreach ($notificationFiles as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name}: existe\n";
        $checks[] = "{$name} file exists";

        // Verificar que tenga método toMail
        $content = file_get_contents($path);
        if (strpos($content, 'public function toMail') !== false) {
            echo "   ✅ Método toMail() implementado\n";
        } else {
            echo "   ❌ FALTA método toMail()\n";
            $errors[] = "{$name}: No implementa toMail()";
        }

        // Verificar que use MailMessage
        if (strpos($content, 'use Illuminate\Notifications\Messages\MailMessage') !== false) {
            echo "   ✅ Importa MailMessage\n";
        } else {
            echo "   ⚠️  No importa MailMessage explícitamente\n";
            $warnings[] = "{$name}: No importa MailMessage";
        }

        // Verificar que incluya 'mail' en via()
        if (preg_match('/public function via.*?return.*?[\'"]mail[\'"]/s', $content)) {
            echo "   ✅ Canal 'mail' configurado en via()\n";
        } else {
            echo "   ❌ FALTA canal 'mail' en via()\n";
            $errors[] = "{$name}: No incluye canal 'mail' en via()";
        }

    } else {
        echo "❌ {$name}: NO EXISTE\n";
        $errors[] = "{$name} file not found";
    }
    echo "\n";
}

// ========== CHECK 2: Verificar GraphTransport ==========
echo "📋 CHECK 2: GraphTransport\n";
echo str_repeat("─", 65) . "\n";

$graphTransportPath = __DIR__ . '/../app/Mail/Transport/GraphTransport.php';
if (file_exists($graphTransportPath)) {
    echo "✅ GraphTransport.php existe\n";
    $content = file_get_contents($graphTransportPath);

    // Verificar que implemente TransportInterface
    if (strpos($content, 'implements TransportInterface') !== false) {
        echo "✅ Implementa TransportInterface\n";
    } else {
        echo "❌ NO implementa TransportInterface\n";
        $errors[] = "GraphTransport no implementa TransportInterface";
    }

    // Verificar imports correctos
    $requiredImports = [
        'Symfony\Component\Mailer\Transport\TransportInterface',
        'Symfony\Component\Mailer\SentMessage',
        'Symfony\Component\Mailer\Envelope',
        'Symfony\Component\Mime\RawMessage',
    ];

    foreach ($requiredImports as $import) {
        if (strpos($content, "use {$import}") !== false) {
            echo "✅ Import: {$import}\n";
        } else {
            echo "❌ FALTA import: {$import}\n";
            $errors[] = "GraphTransport falta import: {$import}";
        }
    }

    // Verificar firma del método send()
    if (preg_match('/public function send\s*\(\s*RawMessage\s+\$message\s*,\s*\?Envelope\s+\$envelope\s*=\s*null\s*\)\s*:\s*\?SentMessage/s', $content)) {
        echo "✅ Firma de send() correcta\n";
    } else {
        echo "❌ Firma de send() incorrecta o faltante\n";
        $errors[] = "GraphTransport: firma de send() incorrecta";
    }

    // Verificar que retorne SentMessage
    if (strpos($content, 'return new SentMessage') !== false) {
        echo "✅ Retorna SentMessage\n";
    } else {
        echo "⚠️  Posiblemente no retorna SentMessage\n";
        $warnings[] = "GraphTransport: verificar que retorne SentMessage";
    }

} else {
    echo "❌ GraphTransport.php NO EXISTE\n";
    $errors[] = "GraphTransport.php not found";
}
echo "\n";

// ========== CHECK 3: Verificar AppServiceProvider ==========
echo "📋 CHECK 3: AppServiceProvider\n";
echo str_repeat("─", 65) . "\n";

$appServiceProviderPath = __DIR__ . '/../app/Providers/AppServiceProvider.php';
if (file_exists($appServiceProviderPath)) {
    echo "✅ AppServiceProvider.php existe\n";
    $content = file_get_contents($appServiceProviderPath);

    // Verificar que registre el transport 'graph'
    if (strpos($content, "Mail::extend('graph'") !== false) {
        echo "✅ Registra transport 'graph'\n";
    } else {
        echo "❌ NO registra transport 'graph'\n";
        $errors[] = "AppServiceProvider: no registra Mail::extend('graph')";
    }

    // Verificar que use GraphTransport
    if (strpos($content, 'new GraphTransport') !== false) {
        echo "✅ Instancia GraphTransport\n";
    } else {
        echo "❌ NO instancia GraphTransport\n";
        $errors[] = "AppServiceProvider: no instancia GraphTransport";
    }

    // Verificar que use GraphMailer
    if (strpos($content, 'new GraphMailer') !== false) {
        echo "✅ Instancia GraphMailer\n";
    } else {
        echo "❌ NO instancia GraphMailer\n";
        $errors[] = "AppServiceProvider: no instancia GraphMailer";
    }

} else {
    echo "❌ AppServiceProvider.php NO EXISTE\n";
    $errors[] = "AppServiceProvider.php not found";
}
echo "\n";

// ========== CHECK 4: Verificar config/mail.php ==========
echo "📋 CHECK 4: Configuración de Mail\n";
echo str_repeat("─", 65) . "\n";

$mailConfigPath = __DIR__ . '/../config/mail.php';
if (file_exists($mailConfigPath)) {
    echo "✅ config/mail.php existe\n";
    $content = file_get_contents($mailConfigPath);

    // Verificar que exista mailer 'graph'
    if (preg_match("/['\"]graph['\"]\s*=>\s*\[/", $content)) {
        echo "✅ Mailer 'graph' configurado\n";
    } else {
        echo "⚠️  Mailer 'graph' no encontrado en config\n";
        $warnings[] = "config/mail.php: mailer 'graph' no encontrado";
    }

    // Verificar transport => 'graph'
    if (preg_match("/['\"]transport['\"]\s*=>\s*['\"]graph['\"]/", $content)) {
        echo "✅ Transport 'graph' definido\n";
    } else {
        echo "⚠️  Transport 'graph' no encontrado\n";
        $warnings[] = "config/mail.php: transport 'graph' no definido";
    }

} else {
    echo "❌ config/mail.php NO EXISTE\n";
    $errors[] = "config/mail.php not found";
}
echo "\n";

// ========== CHECK 5: Verificar .env ==========
echo "📋 CHECK 5: Variables de Entorno (.env)\n";
echo str_repeat("─", 65) . "\n";

if (file_exists(__DIR__ . '/../.env')) {
    $dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotEnv->safeLoad();

    // MAIL_MAILER
    $mailMailer = $_ENV['MAIL_MAILER'] ?? null;
    if ($mailMailer === 'graph') {
        echo "✅ MAIL_MAILER=graph\n";
    } else {
        echo "⚠️  MAIL_MAILER={$mailMailer} (debería ser 'graph' para usar Microsoft Graph)\n";
        $warnings[] = "MAIL_MAILER no está configurado como 'graph'";
    }

    // Graph credentials (soporta GRAPH_* y AZURE_* como fallback)
    $graphVars = [
        ['GRAPH_TENANT_ID', 'AZURE_TENANT_ID'],
        ['GRAPH_CLIENT_ID', 'AZURE_CLIENT_ID'],
        ['GRAPH_CLIENT_SECRET', 'AZURE_CLIENT_SECRET'],
        ['GRAPH_FROM_ADDRESS', 'MAIL_FROM_ADDRESS'],
    ];

    foreach ($graphVars as $vars) {
        $primary = $vars[0];
        $fallback = $vars[1];
        $value = $_ENV[$primary] ?? $_ENV[$fallback] ?? null;
        $found = !empty($_ENV[$primary]) ? $primary : (!empty($_ENV[$fallback]) ? $fallback : null);

        if (!empty($value)) {
            echo "✅ {$found} configurado\n";
        } else {
            echo "❌ {$primary}/{$fallback} NO configurado\n";
            $errors[] = "{$primary} o {$fallback} falta en .env";
        }
    }

} else {
    echo "❌ Archivo .env NO EXISTE\n";
    $errors[] = ".env file not found";
}
echo "\n";

// ========== CHECK 6: Verificar GraphMailer Service ==========
echo "📋 CHECK 6: GraphMailer Service\n";
echo str_repeat("─", 65) . "\n";

$graphMailerPath = __DIR__ . '/../app/Services/GraphMailer.php';
if (file_exists($graphMailerPath)) {
    echo "✅ GraphMailer.php existe\n";
    $content = file_get_contents($graphMailerPath);

    // Verificar método send
    if (strpos($content, 'public function send') !== false) {
        echo "✅ Método send() implementado\n";
    } else {
        echo "❌ Método send() NO encontrado\n";
        $errors[] = "GraphMailer: método send() no encontrado";
    }

    // Verificar que use Guzzle
    if (strpos($content, 'GuzzleHttp') !== false || strpos($content, 'Guzzle') !== false) {
        echo "✅ Usa GuzzleHttp para requests\n";
    } else {
        echo "⚠️  No se detectó uso de GuzzleHttp\n";
        $warnings[] = "GraphMailer: verificar uso de HTTP client";
    }

} else {
    echo "❌ GraphMailer.php NO EXISTE\n";
    $errors[] = "GraphMailer.php not found";
}
echo "\n";

// ========== CHECK 7: Verificar vistas de email ==========
echo "📋 CHECK 7: Vistas de Email (Markdown)\n";
echo str_repeat("─", 65) . "\n";

$emailViewsPath = __DIR__ . '/../resources/views/emails';
if (is_dir($emailViewsPath)) {
    echo "✅ Directorio resources/views/emails existe\n";

    // Buscar vista de status_changed
    $statusChangedView = $emailViewsPath . '/tickets/status_changed.blade.php';
    if (file_exists($statusChangedView)) {
        echo "✅ Vista status_changed.blade.php existe\n";
    } else {
        echo "⚠️  Vista status_changed.blade.php no encontrada\n";
        $warnings[] = "Vista emails/tickets/status_changed.blade.php no encontrada";
    }
} else {
    echo "⚠️  Directorio resources/views/emails NO existe\n";
    $warnings[] = "Directorio de vistas de email no encontrado";
}
echo "\n";

// ========== RESUMEN FINAL ==========
echo str_repeat("═", 65) . "\n";
echo "📊 RESUMEN DE VERIFICACIÓN\n";
echo str_repeat("═", 65) . "\n\n";

$totalChecks = count($checks);
$totalErrors = count($errors);
$totalWarnings = count($warnings);

echo "✅ Checks exitosos: {$totalChecks}\n";
echo "❌ Errores: {$totalErrors}\n";
echo "⚠️  Advertencias: {$totalWarnings}\n\n";

if ($totalErrors > 0) {
    echo "🔴 ERRORES ENCONTRADOS:\n";
    echo str_repeat("─", 65) . "\n";
    foreach ($errors as $i => $error) {
        echo ($i + 1) . ". {$error}\n";
    }
    echo "\n";
}

if ($totalWarnings > 0) {
    echo "🟡 ADVERTENCIAS:\n";
    echo str_repeat("─", 65) . "\n";
    foreach ($warnings as $i => $warning) {
        echo ($i + 1) . ". {$warning}\n";
    }
    echo "\n";
}

if ($totalErrors === 0 && $totalWarnings === 0) {
    echo "🎉 ¡CONFIGURACIÓN PERFECTA! Todas las notificaciones están listas para usar Microsoft Graph.\n\n";
    echo "📝 Próximos pasos:\n";
    echo "   1. Ejecutar: php scripts/test_all_notifications.php\n";
    echo "   2. Verificar que los correos lleguen correctamente\n";
    echo "   3. Subir los cambios al hosting\n";
    exit(0);
} elseif ($totalErrors === 0) {
    echo "✅ No hay errores críticos, pero revisa las advertencias.\n";
    echo "   Puedes ejecutar: php scripts/test_all_notifications.php\n";
    exit(0);
} else {
    echo "❌ HAY ERRORES QUE DEBEN CORREGIRSE antes de usar las notificaciones.\n";
    exit(1);
}
