<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EventInventoryController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\SustainabilityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// AUTHENTICATED ROUTES (All Logged-in Users)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/operations', [DashboardController::class, 'operations'])->name('dashboard.operations');
    Route::get('/dashboard/sales', [DashboardController::class, 'sales'])->name('dashboard.sales');
    Route::get('/dashboard/finance', [DashboardController::class, 'finance'])->name('dashboard.finance');
    Route::get('/dashboard/logistics', [DashboardController::class, 'logistics'])->name('dashboard.logistics');
    Route::get('/dashboard/client', [DashboardController::class, 'client'])->name('dashboard.client');

    // Client Dashboard (Client View Only)
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
});

// ==========================================
// CLIENTS: Admin (full) + Operations/Sales (view only)
// ==========================================

// Full CRUD (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});

// View only (Admin + Operations + Sales)
Route::middleware(['auth', 'role:admin,operations,sales'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
});

// ==========================================
// EVENTS: Admin (full) + Operations (view)
// ==========================================

// Full CRUD (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

// Events - View only (Admin + Operations + Finance + Logistics)
Route::middleware(['auth', 'role:admin,operations,finance,logistics'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

// ==========================================
// CHECKLIST: Admin + Operations
// ==========================================
Route::middleware(['auth', 'role:admin,operations'])->group(function () {
    Route::get('/events/{event}/checklists', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::put('/checklists/{eventChecklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::post('/checklists/{eventChecklist}/complete', [ChecklistController::class, 'markComplete'])->name('checklists.complete');
    Route::post('/events/{event}/checklists/bulk-complete', [ChecklistController::class, 'bulkComplete'])->name('checklists.bulk.complete');
});

// ==========================================
// SUSTAINABILITY: Admin + Operations
// ==========================================
Route::middleware(['auth', 'role:admin,operations'])->group(function () {
    Route::get('/events/{event}/sustainability', [SustainabilityController::class, 'index'])->name('sustainability.index');
    Route::get('/events/{event}/sustainability/create', [SustainabilityController::class, 'create'])->name('sustainability.create');
    Route::post('/events/{event}/sustainability', [SustainabilityController::class, 'store'])->name('sustainability.store');
    Route::get('/sustainability/{sustainability}/edit', [SustainabilityController::class, 'edit'])->name('sustainability.edit');
    Route::put('/sustainability/{sustainability}', [SustainabilityController::class, 'update'])->name('sustainability.update');
    Route::delete('/sustainability/{sustainability}', [SustainabilityController::class, 'destroy'])->name('sustainability.destroy');
    Route::post('/sustainability/{sustainability}/verify', [SustainabilityController::class, 'verify'])->name('sustainability.verify');
    Route::post('/sustainability/{sustainability}/unverify', [SustainabilityController::class, 'unverify'])->name('sustainability.unverify');
});

// ==========================================
// FINANCE: Admin + Finance
// ==========================================
Route::middleware(['auth', 'role:admin,finance'])->group(function () {
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/events/{event}/finance', [FinanceController::class, 'eventIndex'])->name('finance.event');
    Route::get('/events/{event}/finance/create', [FinanceController::class, 'create'])->name('finance.create');
    Route::post('/events/{event}/finance', [FinanceController::class, 'store'])->name('finance.store');
    Route::get('/finance/{finance}/edit', [FinanceController::class, 'edit'])->name('finance.edit');
    Route::put('/finance/{finance}', [FinanceController::class, 'update'])->name('finance.update');
    Route::delete('/finance/{finance}', [FinanceController::class, 'destroy'])->name('finance.destroy');
    Route::post('/finance/{finance}/approve', [FinanceController::class, 'approve'])->name('finance.approve');
    Route::post('/finance/{finance}/reject', [FinanceController::class, 'reject'])->name('finance.reject');
});

// ==========================================
// INVENTORY: Admin + Logistics
// ==========================================
Route::middleware(['auth', 'role:admin,logistics'])->group(function () {
    Route::resource('inventory', InventoryController::class);
    Route::get('/events/{event}/inventory', [EventInventoryController::class, 'index'])->name('event.inventory.index');
    Route::post('/events/{event}/inventory', [EventInventoryController::class, 'store'])->name('event.inventory.store');
    Route::put('/event-inventory/{eventInventory}', [EventInventoryController::class, 'update'])->name('event.inventory.update');
    Route::delete('/event-inventory/{eventInventory}', [EventInventoryController::class, 'destroy'])->name('event.inventory.destroy');
});

// ==========================================
// REPORTS: Admin only (full) + Operations (view only)
// ==========================================

// View reports (Admin + Operations)
Route::middleware(['auth', 'role:admin,operations'])->group(function () {
    Route::get('/events/{event}/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}/view', [ReportController::class, 'view'])->name('reports.view');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
});

// Reports - Client View (read-only access)
Route::middleware(['auth', 'role:client_view'])->group(function () {
    Route::get('/client/events/{event}/reports', [ReportController::class, 'clientIndex'])->name('client.reports.index');
    Route::get('/client/reports/{report}/view', [ReportController::class, 'view'])->name('client.reports.view');
    Route::get('/client/reports/{report}/download', [ReportController::class, 'download'])->name('client.reports.download');
});

// Full report access (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports', [ReportController::class, 'allReports'])->name('reports.all');
    Route::get('/events/{event}/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/events/{event}/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
});

// ==========================================
// STAFF MANAGEMENT: Admin only
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::post('/staff/{staff}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');
    Route::post('/staff/{staff}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
});

// ==========================================
// ACTIVITY LOG: Admin only
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::post('/activity-log/clear', [ActivityLogController::class, 'clear'])->name('activity.clear');
});

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    Route::delete('/profile/picture', [ProfileController::class, 'removePicture'])->name('profile.picture.remove');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

require __DIR__.'/auth.php';