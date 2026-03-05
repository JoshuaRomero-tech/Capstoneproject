<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\BlotterController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect('/login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Residents
    Route::resource('residents', ResidentController::class);

    // Households
    Route::resource('households', HouseholdController::class);

    // Officials
    Route::resource('officials', OfficialController::class);

    // Certificates
    Route::resource('certificates', CertificateController::class)->except(['edit', 'update']);
    Route::get('/certificates/{certificate}/print', [CertificateController::class, 'print'])->name('certificates.print');

    // Blotters
    Route::resource('blotters', BlotterController::class);
});
