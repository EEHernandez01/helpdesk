<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketCommentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Agent\TicketController as AgentTicketController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Admin\ComputerController as AdminComputerController;
use App\Http\Controllers\Agent\ComputerController as AgentComputerController;
use App\Http\Controllers\SystemStatusController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\FaviconController;
use App\Http\Controllers\HelpSectionController;
use App\Http\Controllers\HelpEntryController;
use App\Http\Controllers\TicketFeedbackController;
use App\Http\Controllers\Agent\TicketCreateController;
use App\Http\Controllers\NotificationController;

// Redirección inicial
Route::get('/', fn() => redirect('login'));

// Favicon dinámico
Route::get('/favicon.ico', [FaviconController::class, 'show'])->name('favicon');

// Ruta de prueba para debug
Route::get('/test-favicon', function () {
    try {
        $favicon = \App\Helpers\CompanyHelper::getCurrentUserCompanyFavicon();
        return response()->json([
            'favicon_url' => $favicon,
            'user' => auth()->user() ? auth()->user()->toArray() : 'No autenticado',
            'company' => auth()->user() && auth()->user()->empresa_id ? \App\Models\Company::find(auth()->user()->empresa_id) : 'Sin empresa'
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Edición de secciones oficiales del centro de ayuda
Route::middleware(['auth', 'role:admin,agent'])->group(function () {
    Route::get('help/sections/{type}/edit', [HelpSectionController::class, 'edit'])->name('help.sections.edit');
    Route::put('help/sections/{type}', [HelpSectionController::class, 'update'])->name('help.sections.update');
});

// Rutas generales de usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets propios y comentarios
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])
        ->name('tickets.comments.store');
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->name('tickets.assign');

    // Feedback al cerrar ticket
    Route::post('tickets/{ticket}/feedback', [TicketFeedbackController::class, 'store'])
        ->name('tickets.feedback.store');

    // Notificaciones
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::post('notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::delete('notifications/{id}', [NotificationController::class, 'delete'])
        ->name('notifications.delete');
    Route::delete('notifications', [NotificationController::class, 'deleteAll'])
        ->name('notifications.delete-all');

    // Centro de ayuda (público)
    Route::get('help', [HelpEntryController::class, 'index'])->name('help.index');
    Route::get('help/{entry}', [HelpEntryController::class, 'show'])->name('help.show');

    // Crear nueva entrada (solo agentes y admins, control en el controlador/policy)
    Route::middleware('role:admin,agent')->group(function () {
        Route::get('help/create', [HelpEntryController::class, 'create'])->name('help.create');
        Route::post('help', [HelpEntryController::class, 'store'])->name('help.store');
    });
});

// -----------------------------------------------------------
// Dashboard y rutas **solo para agentes** (role:agent)
// -----------------------------------------------------------
Route::middleware(['auth', 'role:agent'])
    ->prefix('agent')
    ->name('agent.')
    ->group(function () {
        Route::get('dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

        // Tickets disponibles para asignar
        Route::get('tickets/available', [AgentTicketController::class, 'available'])->name('tickets.available');

        // Crear ticket para usuario
        Route::get('tickets/create-user', [TicketCreateController::class, 'create'])->name('tickets.create-user');
        Route::post('tickets/store-user', [TicketCreateController::class, 'store'])->name('tickets.store-user');

        // CRUD de tickets del agente
        Route::resource('tickets', AgentTicketController::class)
            ->only(['index', 'show', 'update']);

        // Asignar ticket específico
        Route::post('tickets/{ticket}/assign', [AgentTicketController::class, 'assign'])->name('tickets.assign');

        // Tomar siguiente ticket automáticamente
        Route::post('tickets/next', [AgentTicketController::class, 'next'])->name('tickets.next');

        // Liberar ticket (desasignar)
        Route::post('tickets/{ticket}/release', [AgentTicketController::class, 'release'])->name('tickets.release');

        // Agregar tickets a pendientes
        Route::post('tickets/add-to-pending', [AgentTicketController::class, 'addToPending'])->name('tickets.add-to-pending');

        // Asignar múltiples tickets
        Route::post('tickets/assign-multiple', [AgentTicketController::class, 'assignMultiple'])->name('tickets.assign-multiple');

        // Gestión de usuarios (index, show, edit, update)
        Route::resource('users', \App\Http\Controllers\UserController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Gestión de computadoras
        Route::resource('computers', AgentComputerController::class)
            ->only(['index', 'show', 'edit', 'update']);

        // Gestión del estado del sistema
        Route::resource('system-status', SystemStatusController::class);
        Route::post('system-status/{systemStatus}/quick-update', [SystemStatusController::class, 'quickUpdate'])
            ->name('system-status.quick-update');
    });

// -----------------------------------------------------------
// Dashboard y rutas **solo para administradores** (role:admin)
// -----------------------------------------------------------
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Panel de administrador
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Gestión de usuarios
        Route::resource('users', \App\Http\Controllers\UserController::class);

        // Ruta para asignar roles a un usuario
        Route::post('users/{user}/roles', [AdminUserController::class, 'assignRoles'])->name('users.roles');

        // Crear ticket para usuario
        Route::get('tickets/create-user', [TicketCreateController::class, 'create'])->name('tickets.create-user');
        Route::post('tickets/store-user', [TicketCreateController::class, 'store'])->name('tickets.store-user');

        // Estadísticas generales
        Route::get('stats', [AdminStatsController::class, 'index'])->name('stats.index');

        // Gestión de computadoras
        Route::resource('computers', AdminComputerController::class);

        // Gestión de companies (empresas)
        Route::resource('companies', CompanyController::class);

        // Gestión del estado del sistema
        Route::resource('system-status', SystemStatusController::class);
        Route::post('system-status/{systemStatus}/quick-update', [SystemStatusController::class, 'quickUpdate'])
            ->name('system-status.quick-update');

        // Revisión y aprobación de entradas de ayuda
        Route::get('help/review', [HelpEntryController::class, 'review'])->name('help.review');
        Route::patch('help/{entry}/status', [HelpEntryController::class, 'updateStatus'])->name('help.updateStatus');
    });

require __DIR__ . '/auth.php';
