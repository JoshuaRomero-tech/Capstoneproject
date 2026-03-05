<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PublicController;

// ─── Public Portal (No Login Required) ────────────────────
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/community/residents', [PublicController::class, 'residents'])->name('public.residents');
Route::get('/community/residents/{resident}', [PublicController::class, 'residentShow'])->name('public.residents.show');
Route::get('/community/officials', [PublicController::class, 'officials'])->name('public.officials');
Route::get('/community/services', [PublicController::class, 'services'])->name('public.services');
Route::get('/community/services/certificate-request', [PublicController::class, 'certificateRequest'])->name('public.certificate-request');
Route::post('/community/services/certificate-request', [PublicController::class, 'certificateRequestStore'])->name('public.certificate-request.store');
Route::get('/community/services/file-blotter', [PublicController::class, 'blotterFile'])->name('public.blotter-file');
Route::post('/community/services/file-blotter', [PublicController::class, 'blotterFileStore'])->name('public.blotter-file.store');

// Guest routes
Route::middleware('guest')->group(function () {
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

    // Certificates (Review workflow - no create/store/edit/update)
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::post('/certificates/{certificate}/approve', [CertificateController::class, 'approve'])->name('certificates.approve');
    Route::post('/certificates/{certificate}/disapprove', [CertificateController::class, 'disapprove'])->name('certificates.disapprove');
    Route::get('/certificates/{certificate}/print', [CertificateController::class, 'print'])->name('certificates.print');
    Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');

    // Blotters (Review workflow - no create/store)
    Route::get('/blotters', [BlotterController::class, 'index'])->name('blotters.index');
    Route::get('/blotters/{blotter}', [BlotterController::class, 'show'])->name('blotters.show');
    Route::get('/blotters/{blotter}/review', [BlotterController::class, 'review'])->name('blotters.review');
    Route::post('/blotters/{blotter}/update-status', [BlotterController::class, 'updateStatus'])->name('blotters.update-status');
    Route::delete('/blotters/{blotter}', [BlotterController::class, 'destroy'])->name('blotters.destroy');

    // Announcements
    Route::resource('announcements', AnnouncementController::class);
});
