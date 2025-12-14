<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

// --- 1. CONTROLLERS FRONTEND ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerApplicationController;
// Alias Frontend
use App\Http\Controllers\CampaignController as FrontendCampaignController;

// --- 2. CONTROLLERS ADMIN ---
use App\Http\Controllers\Admin\NotifikasiController;
// Alias Admin
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\VolunteerVerificationController;
use App\Http\Controllers\Admin\VolunteerAdminController; 


// Halaman Utama
Route::get('/', [Controller::class, 'home'])->name('home');

// Autentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');

// Donasi (Frontend Public)
Route::get('/donation-details/{campaign?}', [DonationController::class, 'index'])->name('donation.details');
Route::get('/donation-checkout/{campaign?}', [DonationController::class, 'checkout'])->name('donation.checkout');
Route::get('/transaction/download/{order_id}', [DonationController::class, 'downloadTransactionPDF'])->name('transaction.download.pdf');

// Relawan (Frontend Public)
// 1. Landing Page Relawan
Route::get('/relawan', [FrontendCampaignController::class, 'landing'])->name('volunteer.landing'); 
// 2. List Semua Kampanye (Pencarian)
Route::get('/volunteer/campaigns', [FrontendCampaignController::class, 'index'])->name('volunteer.campaigns.index');
Route::get('/campaigns', [FrontendCampaignController::class, 'index'])->name('campaigns.all');
// 3. Detail Kampanye
Route::get('/volunteer/campaigns/{slug}', [FrontendCampaignController::class, 'show'])->name('volunteer.campaigns.show');


Route::middleware(['auth'])->group(function () {

    // Profil & Riwayat User (Frontend)
    Route::prefix('profiles')->name('profiles.')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/invoice/{id}', [ProfileController::class, 'invoice'])->name('invoice');
    });

    // Fitur Notifikasi (PENTING: Untuk fitur notifikasi kita tadi)
    Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    // Route untuk melihat status lamaran (Halaman "Surat")
    Route::get('/volunteer/status/{slug}', [VolunteerApplicationController::class, 'checkStatus'])
        ->name('volunteer.application.status');

    // Proses Pendaftaran Relawan
    Route::get('/volunteer/{slug}/register', [VolunteerApplicationController::class, 'create'])->name('volunteer.register.create');
    Route::post('/volunteer/{slug}/register', [VolunteerApplicationController::class, 'store'])->name('volunteer.register.store');

    // Proses Donasi
    Route::post('/donation-process', [DonationController::class, 'process'])->name('donation.process');
    Route::get('/donation/manual-transfer/{order_id}', [DonationController::class, 'manualTransfer'])->name('donation.manual.transfer');
    Route::post('/donation/upload-proof/{order_id}', [DonationController::class, 'uploadProof'])->name('donation.upload.proof');

    // Profil User
    Route::prefix('profiles')->name('profiles.')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/transactions', [ProfileController::class, 'showTransactionHistory'])->name('transactions');
    });

    // --- ROUTE ADMIN ---
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Dashboard & Settings
        Route::get('dashboard', function () { return view('admin.dashboard'); })->name('dashboard');
        Route::get('settings', function () { return view('admin.settings'); })->name('settings');
        Route::resource('profiles', \App\Http\Controllers\Admin\UserController::class);

        Route::resource('relawan', AdminCampaignController::class);

        // Manajemen Kampanye (Resource)
        Route::resource('campaigns', AdminCampaignController::class);

        // Manajemen Notifikasi & Relawan (Master Data)
        Route::resource('notifications', NotifikasiController::class);
        Route::resource('volunteers', VolunteerAdminController::class);

        // Manajemen Transaksi Donasi
        Route::get('donation-transactions', [DonationController::class, 'adminIndex'])->name('donations.index');
        Route::put('donation-transactions/{order_id}/status', [DonationController::class, 'updateStatus'])->name('donations.updateStatus');
        Route::delete('donation-transactions/{order_id}', [DonationController::class, 'destroy'])->name('donations.destroy');

        // === VERIFIKASI PENDAFTAR RELAWAN ===
        // Route ini disesuaikan dengan Controller yang baru kita buat
        Route::prefix('verifikasi-relawan')->name('verifikasi-relawan.')->group(function () {
            Route::get('/', [VolunteerVerificationController::class, 'index'])->name('index');
            Route::get('/{id}', [VolunteerVerificationController::class, 'show'])->name('show');
            
            // Method UPDATE menangani Approve DAN Reject (Sesuai kode Controller Anda)
            Route::patch('/{id}', [VolunteerVerificationController::class, 'update'])->name('update'); 
            
            // Route Hapus
            Route::delete('/{id}', [VolunteerVerificationController::class, 'destroy'])->name('destroy');
        });
        
        // Route tambahan untuk list pendaftar (jika VolunteerAdminController punya method ini)
        Route::get('/daftar-pendaftar', [VolunteerAdminController::class, 'pendaftarIndex'])->name('pendaftar.list');
    });
});