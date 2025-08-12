<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AbsensiMagangController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================= USER =================
    Route::middleware('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Lowongan (user)
        Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
        Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])->name('lowongan.show');
        Route::post('/lowongan/{lowongan}/apply', [LowonganController::class, 'apply'])->name('lowongan.apply');

        // Applications (user)
        Route::get('/applications', [ApplicationController::class, 'userIndex'])->name('user.applications');
        Route::get('/user/applications/{uuid}/qrcode', [ApplicationController::class, 'showQrCode'])->name('user.application.qrcode');

        // Absensi Magang (user)
        Route::get('/absensi-magang', [AbsensiMagangController::class, 'userIndex'])->name('user.absensi.index');
        Route::get('/absensi-magang/qr', [AbsensiMagangController::class, 'userQr'])->name('user.absensi.qr');

        // (opsional lama - jika masih ingin dipakai user absen mandiri)
        Route::get('/absensi-magang/absen/{uuid}', [AbsensiMagangController::class, 'formAbsen'])->name('absen.magang.qr');
        Route::post('/absensi-magang/absen/{uuid}', [AbsensiMagangController::class, 'submitAbsen'])->name('absen.magang.submit');
    });

    // ================= ADMIN =================
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Lowongan (admin)
        Route::get('/lowongan', [LowonganController::class, 'adminIndex'])->name('lowongan.index');
        Route::get('/lowongan/create', [LowonganController::class, 'create'])->name('lowongan.create');
        Route::post('/lowongan', [LowonganController::class, 'store'])->name('lowongan.store');
        Route::get('/lowongan/{lowongan}/edit', [LowonganController::class, 'edit'])->name('lowongan.edit');
        Route::put('/lowongan/{lowongan}', [LowonganController::class, 'update'])->name('lowongan.update');
        Route::delete('/lowongan/{lowongan}', [LowonganController::class, 'destroy'])->name('lowongan.destroy');

        // For showing the application form (GET)
        Route::get('lowongan/{lowongan}/apply', [LowonganController::class, 'showApplyForm'])->name('lowongan.apply.form');
        // For submitting the application (POST)
        Route::post('lowongan/{lowongan}/apply', [LowonganController::class, 'apply'])->name('lowongan.apply');

        // Applications (admin)
        Route::get('/applications', [ApplicationController::class, 'adminIndex'])->name('application.index');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('application.show');
        Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('application.status');

        // Absensi Magang (admin)
        Route::get('/absensi-magang', [AbsensiMagangController::class, 'adminIndex'])->name('absensi.index');

        // === Tambahan untuk flow SCAN oleh admin ===
        Route::get('/absensi-magang/scan', [AbsensiMagangController::class, 'adminScanPage'])->name('absensi.scan');
        Route::post('/absensi-magang/scan', [AbsensiMagangController::class, 'adminScanStore'])->name('absensi.scan.store');
    });
});
