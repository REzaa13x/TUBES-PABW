<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerRegistrationController;

// Controller Admin
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\VolunteerAdminController; // INI YANG BIKIN ERROR TADI

    
// Rute Autentikasi (Login/Register)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.process');

// Rute Halaman Utama
Route::get('/', [Controller::class, 'home'])->name('home');

// Rute Donation Details - Using modern design
Route::get('/donation-details/{campaign?}', [DonationController::class, 'index'])->name('donation.details');
Route::get('/donation-checkout/{campaign?}', [DonationController::class, 'checkout'])->name('donation.checkout');
Route::get('/transaction/download/{order_id}', [DonationController::class, 'downloadTransactionPDF'])->name('transaction.download.pdf');

// Rute Halaman Relawan
Route::get('/relawan', function () {
    return view('volunteer.index');
})->name('volunteer');

// Rute untuk menampilkan semua kampanye - arahkan ke halaman home
Route::get('/campaigns', function () {
    return redirect('/');
})->name('campaigns.all');

// Rute Halaman Relawan
Route::get('/relawan', function () {
    return view('volunteer.index');
})->name('volunteer');

// Rute Form Pendaftaran Relawan
Route::get('/relawan/daftar', [VolunteerRegistrationController::class, 'create'])->name('volunteer.register');
Route::post('/relawan/daftar', [VolunteerRegistrationController::class, 'store'])->name('volunteer.store');

Route::middleware(['auth'])->group(function () {

    // Rute Donation Process
    Route::post('/donation-process', [DonationController::class, 'process'])->name('donation.process');
    Route::get('/donation/manual-transfer/{order_id}', [DonationController::class, 'manualTransfer'])->name('donation.manual.transfer');
    Route::post('/donation/upload-proof/{order_id}', [DonationController::class, 'uploadProof'])->name('donation.upload.proof');

    // Rute Profil User (Bisa diakses user biasa & admin)
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('/profiles', [ProfileController::class, 'update'])->name('profiles.update');
    Route::get('/profiles/transactions', [ProfileController::class, 'showTransactionHistory'])->name('profiles.transactions');

    // Rute Khusus Admin
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard Admin
        Route::get('dashboard', function () {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Akses ditolak. Anda bukan admin.');
            }
            return view('admin.dashboard');
        })->name('dashboard');

        // Settings Admin
        Route::get('settings', function () {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Akses ditolak. Anda bukan admin.');
            }
            return view('admin.settings');
        })->name('settings');

        // Rute CRUD Admin (Resource)
        Route::resource('campaigns', CampaignController::class)->middleware('role:admin');
        Route::resource('notifications', NotifikasiController::class)->middleware('role:admin');
        Route::resource('volunteers', VolunteerAdminController::class)->middleware('role:admin');

        // Donation Transactions Management
        Route::get('donation-transactions', [DonationController::class, 'adminIndex'])->name('donations.index')->middleware('role:admin');
        Route::put('donation-transactions/{order_id}/status', [DonationController::class, 'updateStatus'])->name('donations.updateStatus')->middleware('role:admin');
        Route::delete('donation-transactions/{order_id}', [DonationController::class, 'destroy'])->name('donations.destroy')->middleware('role:admin');
    });
});

// ADMIN – Kampanye Relawan
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/relawan', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'index'])->name('relawan.index');
    Route::get('/relawan/tambah', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'create'])->name('relawan.create');
    Route::post('/relawan/store', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'store'])->name('relawan.store');
    Route::get('/relawan/{id}', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'show'])->name('relawan.show');
    Route::get('/relawan/{id}/edit', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'edit'])->name('relawan.edit');
    Route::put('/relawan/{id}', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'update'])->name('relawan.update');
    Route::post('/relawan/{id}/toggle-status', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'toggleStatus'])->name('relawan.toggle-status');
    Route::delete('/relawan/{id}', [\App\Http\Controllers\Admin\VolunteerCampaignController::class, 'destroy'])->name('relawan.delete');

    // ADMIN - Verifikasi Pendaftaran Relawan
    Route::prefix('verifikasi-relawan')->name('verifikasi-relawan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'index'])->name('index');
        Route::get('/campaign/{campaignId}', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'showByCampaign'])->name('by-campaign');
        Route::get('/{id}', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'verify'])->name('verify');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\VolunteerVerificationController::class, 'destroy'])->name('destroy');
    });
});

