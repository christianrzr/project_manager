<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Settings\AccountSettingsController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Guest Routes (Claude-style passwordless OTP & Google)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showLogin'])->name('register');
    Route::post('/login', [AuthController::class, 'sendCode'])->name('login.send');
    Route::post('/register', [AuthController::class, 'sendCode'])->name('register.store');
    
    Route::get('/verify', [AuthController::class, 'showVerify'])->name('auth.verify');
    Route::post('/verify', [AuthController::class, 'verifyCode'])->name('auth.verify.submit');
    Route::post('/verify/resend', [AuthController::class, 'resendCode'])->name('auth.verify.resend');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Confirmation route for account changes (signed URL or token)
Route::get('/account-changes/{change}/confirm', [AccountSettingsController::class, 'confirm'])
    ->name('account-changes.confirm');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')->name('verification.send');

    // Main Task Views
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::get('/board', [TaskController::class, 'board'])->name('tasks.board');
    Route::get('/calendar', [TaskController::class, 'calendar'])->name('calendar');

    // Task CRUD & Actions
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Subtasks
    Route::post('/tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::patch('/subtasks/{subtask}/toggle', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
    Route::delete('/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');

    // Categories Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Account & Profile Settings
    Route::get('/settings/account', [AccountSettingsController::class, 'edit'])->name('settings.account');
    Route::put('/settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/username', [AccountSettingsController::class, 'requestUsernameChange'])->middleware('throttle:3,10')->name('settings.username.request');
    Route::post('/settings/password', [AccountSettingsController::class, 'requestPasswordChange'])->middleware('throttle:3,10')->name('settings.password.request');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
