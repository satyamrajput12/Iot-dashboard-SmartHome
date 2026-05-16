<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\DeviceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\BillingController;
use App\Http\Controllers\User\EnergyController;
use App\Http\Controllers\User\RoomController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// =============================================
// PUBLIC ROUTES (no auth required)
// =============================================

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =============================================
// USER ROUTES (authenticated regular users)
// =============================================

Route::middleware(['auth', 'active.user'])->prefix('dashboard')->name('user.')->group(function () {

    // Main Dashboard
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

    // New Modules
    Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('energy', [EnergyController::class, 'index'])->name('energy.index');
    Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Device Management (RESTful)
    Route::resource('devices', DeviceController::class);

    // Device Control (AJAX)
    Route::post('devices/{device}/toggle', [DeviceController::class, 'toggle'])->name('devices.toggle');
    Route::post('devices/{device}/temperature', [DeviceController::class, 'updateTemperature'])->name('devices.temperature');
    Route::post('devices/{device}/brightness', [DeviceController::class, 'updateBrightness'])->name('devices.brightness');
    Route::post('devices/{device}/mode', [DeviceController::class, 'updateMode'])->name('devices.mode');

    // Profile Settings
    Route::post('profile/photo', [\App\Http\Controllers\User\ProfileController::class, 'updatePhoto'])->name('profile.photo');

    // Real-time polling endpoint
    Route::get('devices/statuses/live', [DeviceController::class, 'getStatuses'])->name('devices.statuses');
});

// =============================================
// ADMIN ROUTES (admin only)
// =============================================

Route::middleware(['auth', 'active.user', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Device Management
    Route::get('/devices', [AdminController::class, 'devices'])->name('devices');
    Route::patch('/devices/{device}/approve', [AdminController::class, 'approveDevice'])->name('devices.approve');
    Route::patch('/devices/{device}/reject', [AdminController::class, 'rejectDevice'])->name('devices.reject');
    Route::delete('/devices/{device}', [AdminController::class, 'deleteDevice'])->name('devices.delete');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Logs / Troubleshooting
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::post('/devices/{device}/simulate-error', [AdminController::class, 'simulateError'])->name('devices.simulate-error');
});
