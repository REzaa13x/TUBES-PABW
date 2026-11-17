<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

// --- 1. CONTROLLERS FRONTEND ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerApplicationController;
// Alias Frontend
use App\Http\Controllers\CampaignController as FrontendCampaignController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Admin\AdminNotificationController;



// --- Validator Distribution Routes (Tanpa Login, Menggunakan Token) ---
use App\Http\Controllers\ValidatorDistributionController;
Route::prefix('validator/{token}')->name('validator.')->group(function () {
    Route::get('/dashboard', [ValidatorDistributionController::class, 'dashboard'])->name('dashboard');
    Route::post('/verify', [ValidatorDistributionController::class, 'verifyCampaign'])->name('verify');
    Route::get('/campaign', [ValidatorDistributionController::class, 'campaign'])->name('campaign');
    Route::get('/upload', [ValidatorDistributionController::class, 'upload'])->name('upload');
    Route::post('/upload', [ValidatorDistributionController::class, 'store'])->name('store');
    Route::get('/history', [ValidatorDistributionController::class, 'history'])->name('history');
    Route::get('/status', [ValidatorDistributionController::class, 'status'])->name('status');
});

// --- 2. CONTROLLERS ADMIN ---
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\VolunteerVerificationController;
use App\Http\Controllers\Admin\VolunteerAdminController; 
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\DistributionController as AdminDistributionController;
use App\Http\Controllers\Admin\ValidatorContactController;
use App\Http\Controllers\Admin\DonationCampaignController as AdminDonationCampaignController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\CertificateController;


// Halaman Utama
Route::get('/', [Controller::class, 'home'])->name('home');

// Autentikasi - Updated to use new auth views
Route::get('/login', function() {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware(['guest', 'throttle:10,1']);
Route::post('/register', [RegisterController::class, 'register'])->name('register.post')->middleware(['guest', 'throttle:10,1']);

Route::get('/register', function() {
    return view('auth.register');
})->name('register')->middleware('guest');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Donasi (Frontend Public)
Route::get('/donation-details/{campaign?}', [DonationController::class, 'index'])->name('donation.details');

// NEW: Donasi Detail dengan slug - menggunakan CampaignController@showDonation
Route::get('/donasi/{slug}', [FrontendCampaignController::class, 'showDonation'])->name('donations.details');

// Donation checkout and processing routes (require authentication to link to user account)
Route::middleware(['auth'])->group(function () {
    Route::get('/donation-checkout/{campaign?}', [DonationController::class, 'checkout'])->name('donation.checkout');
    Route::post('/donation-process', [DonationController::class, 'process'])->name('donation.process');
});

// Donation success and finish routes
Route::get('/donation-success', function () {
    return view('donation-success');
})->name('donation.success');

Route::get('/donation-success/{order_id}', function ($order_id) {
    return view('donation-success', ['order_id' => $order_id]);
})->name('donation.success.with.order');

// Midtrans Callback
Route::post('/midtrans/callback', [App\Http\Controllers\MidtransController::class, 'callback'])->name('midtrans.callback');

// Relawan (Frontend Public)
// 1. Landing Page Relawan
Route::get('/relawan', [FrontendCampaignController::class, 'landing'])->name('volunteer.landing');
// 2. List Semua Kampanye Relawan (Pencarian)
Route::get('/volunteer/campaigns', [FrontendCampaignController::class, 'volunteerIndex'])->name('volunteer.campaigns.index');
Route::get('/campaigns', [FrontendCampaignController::class, 'index'])->name('campaigns.all');
// 3. Detail Kampanye
// NOTE: This route is moved inside auth middleware group below because registration requires login

// ── PUBLIC: Verifikasi Sertifikat (tanpa login) ─────────────────────────────
Route::get('/certificate/verify/{certificateNumber}', [CertificateController::class, 'verify'])
    ->name('certificate.verify');


Route::middleware(['auth'])->group(function () {

    // Relawan - Detail Kampanye (memerlukan login untuk pendaftaran)
    Route::get('/volunteer/campaigns/{slug}', [FrontendCampaignController::class, 'show'])->name('volunteer.campaigns.show');

    // ── Sertifikat Relawan ───────────────────────────────────────────────────
    Route::get('/volunteer/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/volunteer/certificates/{id}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/volunteer/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // Profil & Riwayat User (Frontend) - Allow all authenticated users (including admins)
    Route::prefix('profiles')->name('profiles.')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/invoice/{id}', [ProfileController::class, 'invoice'])->name('invoice');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::post('/upload-proof/{order_id}', [ProfileController::class, 'uploadProof'])->name('upload.proof');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
    });

    // Authenticated donation routes (require login) - Prevent admin access
    Route::middleware(['auth'])->group(function () {
        Route::get('/donation/manual-transfer/{order_id}', [DonationController::class, 'manualTransfer'])->name('donation.manual.transfer');
        Route::post('/donation/upload-proof/{order_id}', [DonationController::class, 'uploadProof'])->name('donation.upload.proof');
        Route::get('/transaction/download/{order_id}', [DonationController::class, 'downloadTransactionPDF'])->name('transaction.download.pdf');

        // Route untuk melihat status lamaran (Halaman "Surat")
        Route::get('/volunteer/status/{slug}', [VolunteerApplicationController::class, 'checkStatus'])
            ->name('volunteer.application.status');
    });

    // Fitur Notifikasi - Tidak menggunakan middleware prevent.admin.user.pages agar bisa diakses oleh admin juga
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/seed-demo', [App\Http\Controllers\NotificationController::class, 'seedDemoNotifications'])->name('notifications.seedDemo');
    Route::post('/notifications/recommendations/{id}/read', [App\Http\Controllers\NotificationController::class, 'markRecommendationRead'])->name('notifications.recommendations.read');
    Route::post('/notifications/recommendations/{id}/dismiss', [App\Http\Controllers\NotificationController::class, 'markRecommendationDismiss'])->name('notifications.recommendations.dismiss');

    // Proses Pendaftaran Relawan - tidak menggunakan middleware prevent.admin.user.pages untuk sementara
    Route::get('/volunteer/{slug}/register', [VolunteerApplicationController::class, 'create'])->name('volunteer.register.create');
    Route::post('/volunteer/{slug}/register', [VolunteerApplicationController::class, 'store'])->name('volunteer.register.store');


    // --- ROUTE ADMIN ---
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Route Penyaluran Dana (Finance)
        Route::get('/withdrawals', [App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::post('/withdrawals', [App\Http\Controllers\Admin\WithdrawalController::class, 'store'])->name('withdrawals.store');
        // Route History
        Route::get('/withdrawals/{id}/history', [App\Http\Controllers\Admin\WithdrawalController::class, 'history'])->name('withdrawals.history');
        
        // Dashboard & Settings
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('settings', function () { return view('admin.settings'); })->name('settings');
        Route::resource('profiles', \App\Http\Controllers\Admin\UserController::class);

        // === ADMIN NOTIFICATION CENTER (NEW) ===
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
            Route::get('/create', [AdminNotificationController::class, 'create'])->name('create');
            Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminNotificationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminNotificationController::class, 'update'])->name('update');
            Route::get('/dropdown', [AdminNotificationController::class, 'dropdown'])->name('dropdown');
            Route::post('/{id}/mark-as-read', [AdminNotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::post('/{id}/mark-as-unread', [AdminNotificationController::class, 'markAsUnread'])->name('markAsUnread');
            Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/', [AdminNotificationController::class, 'destroyAll'])->name('destroyAll');
            Route::get('/export/csv', [AdminNotificationController::class, 'export'])->name('export');
            Route::get('/api/stats', [AdminNotificationController::class, 'stats'])->name('stats');
            Route::get('/api/activity-feed', [AdminNotificationController::class, 'activityFeed'])->name('activityFeed');
        });

        Route::resource('relawan', AdminCampaignController::class);

        // ── Manajemen Sertifikat Relawan ──────────────────────────────────────────
        Route::prefix('certificates')->name('certificates.')->group(function () {
            Route::get('/', [AdminCertificateController::class, 'index'])->name('index');
            Route::get('/{id}', [AdminCertificateController::class, 'show'])->name('show');
            Route::get('/{id}/download', [AdminCertificateController::class, 'download'])->name('download');
            Route::post('/generate/{applicationId}', [AdminCertificateController::class, 'generate'])->name('generate');
            Route::post('/{id}/activate', [AdminCertificateController::class, 'activate'])->name('activate');
            Route::post('/{id}/revoke', [AdminCertificateController::class, 'revoke'])->name('revoke');
            Route::post('/activate-expired', [AdminCertificateController::class, 'activateExpired'])->name('activate-expired');
        });

        // Manajemen Kampanye Donasi (Resource)
        Route::resource('campaigns', AdminDonationCampaignController::class);
        Route::resource('validator-contacts', ValidatorContactController::class);

        // Manajemen Notifikasi & Relawan (Master Data)
        // Notifikasi admin sudah ditangani oleh prefix group 'notifications' di atas (AdminNotificationController)
        Route::resource('volunteers', VolunteerAdminController::class);

        // Manajemen Transaksi Donasi
        Route::get('donation-transactions', [DonationController::class, 'adminIndex'])->name('donations.index');
        Route::get('donation-transactions/{order_id}', [DonationController::class, 'showForAdmin'])->name('donations.show');
        Route::get('donation-transactions/{order_id}/invoice', [DonationController::class, 'showInvoiceForAdmin'])->name('donations.invoice');
        Route::put('donation-transactions/{order_id}/status', [DonationController::class, 'updateStatus'])->name('donations.updateStatus');
        Route::delete('donation-transactions/{order_id}', [DonationController::class, 'destroy'])->name('donations.destroy');

        // Manajemen Penyaluran Dana (Distribution)
        Route::get('distribution', [AdminDistributionController::class, 'index'])->name('distribution.index');
        Route::get('distribution/create', [AdminDistributionController::class, 'create'])->name('distribution.create');
        Route::post('distribution', [AdminDistributionController::class, 'store'])->name('distribution.store');
        Route::get('distribution/{id}', [AdminDistributionController::class, 'show'])->name('distribution.show');
        Route::put('distribution/{id}', [AdminDistributionController::class, 'update'])->name('distribution.update');
        Route::delete('distribution/{id}', [AdminDistributionController::class, 'destroy'])->name('distribution.destroy');
        Route::post('campaigns/{id}/generate-link', [AdminDistributionController::class, 'generateLink'])->name('campaigns.generateLink');

        // API Verifikasi Donasi untuk Admin
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('donation-transactions/{order_id}', [App\Http\Controllers\API\DonationVerificationController::class, 'getTransactionWithProof'])->name('donations.get');
            Route::put('donation-transactions/{order_id}/verify', [App\Http\Controllers\API\DonationVerificationController::class, 'verifyTransaction'])->name('donations.verify');
            Route::get('donation-transactions/pending', [App\Http\Controllers\API\DonationVerificationController::class, 'getPendingVerifications'])->name('donations.pending');
            Route::delete('donation-transactions/{order_id}/proof', [App\Http\Controllers\API\DonationVerificationController::class, 'deleteProof'])->name('donations.proof.delete');
        });


        // Halaman API Verifikasi Donasi
        Route::get('donation-verifications', [App\Http\Controllers\Admin\DonationVerificationController::class, 'index'])->name('donations.api.index');

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

// Route untuk testing gambar
Route::get('/test-image/{folder}/{filename}', [App\Http\Controllers\TestImageController::class, 'testImage']);
});

// Route untuk halaman tambah donasi
Route::middleware(['auth'])->group(function () {
    Route::get('/donation/create', [DonationController::class, 'create'])->name('donation.create');
    Route::post('/donation/store', [DonationController::class, 'store'])->name('donation.store');
});

// Route public image proxy untuk melayani gambar kampanye di model getImageAttribute()
Route::get('/api/images/{folder}/{filename}', [App\Http\Controllers\TestImageController::class, 'testImage']);

require __DIR__ . '/donasi-profile.php';

// Website reviews use the same controller and storage with session authentication.
Route::get('/ulasan', fn () => view('reviews.index'))->name('reviews.index');
Route::get('/ulasan/data', [\App\Http\Controllers\Api\ReviewController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::get('/ulasan/eligibility', [\App\Http\Controllers\Api\ReviewController::class, 'eligibility']);
    Route::post('/ulasan', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
    Route::put('/ulasan/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
    Route::delete('/ulasan/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
});
