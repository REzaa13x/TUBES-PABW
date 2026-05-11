<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;

// Kampanye (Donasi) dari Profil User
Route::middleware(['auth'])->prefix('profiles/campaign')->name('profiles.campaign.')->group(function () {
    Route::get('/history', [CampaignController::class, 'history'])->name('history');
    Route::post('/store', [CampaignController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CampaignController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CampaignController::class, 'update'])->name('update');
    Route::delete('/{id}', [CampaignController::class, 'destroy'])->name('destroy');
});
