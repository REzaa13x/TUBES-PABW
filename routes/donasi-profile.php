<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;

// Tambah Donasi Baru (Kampanye) dari Profil User
Route::middleware(['auth'])->group(function () {
    Route::post('/profiles/donasi-baru', [CampaignController::class, 'storeFromProfile'])->name('profiles.donasi.store');
});
