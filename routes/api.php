<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VolunteerCampaignController;
use App\Http\Controllers\Api\VolunteerApplicationController;
use App\Http\Controllers\Api\AdminDonationCampaignController;
use App\Http\Controllers\Api\AdminController;

Route::prefix('v1')->middleware('cors')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
    */

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Campaigns public
    Route::get('/campaigns/urgent', [CampaignController::class, 'urgent']);

    Route::apiResource('campaigns', CampaignController::class)
        ->only(['index', 'show']);

    // Volunteer Campaigns public
    Route::apiResource('volunteer-campaigns', VolunteerCampaignController::class)
        ->only(['index', 'show']);

    // Validator Public API
    Route::prefix('validator/{token}')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\ValidatorPortalController::class, 'dashboard']);
        Route::post('/verify', [\App\Http\Controllers\Api\ValidatorPortalController::class, 'verifyCampaign']);
        Route::post('/report', [\App\Http\Controllers\Api\ValidatorPortalController::class, 'storeReport']);
        Route::get('/history', [\App\Http\Controllers\Api\ValidatorPortalController::class, 'history']);
    });

    /*
    |--------------------------------------------------------------------------
    | PROTECTED ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        // User data
        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        });

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        /*
        |--------------------------------------------------------------------------
        | CAMPAIGNS
        |--------------------------------------------------------------------------
        */

        // Protected campaign routes
        Route::apiResource('campaigns', CampaignController::class)
            ->except(['index', 'show']);

        // Protected volunteer campaigns
        Route::apiResource('volunteer-campaigns', VolunteerCampaignController::class)
            ->except(['index', 'show']);

        /*
        |--------------------------------------------------------------------------
        | DONATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/donations', [DonationController::class, 'index']);
        Route::get('/donations/history', [DonationController::class, 'getUserDonations']);
        Route::get('/donations/{order_id}', [DonationController::class, 'show']);
        Route::get('/my-donations', [DonationController::class, 'getUserDonations']);

        Route::post('/donations', [DonationController::class, 'store']);
        Route::put('/donations/{order_id}/status', [DonationController::class, 'updateStatus']);

        Route::post('/donations/{order_id}/proof', [DonationController::class, 'uploadProof']);

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);

        Route::get('/profile/donations', [ProfileController::class, 'getDonationHistory']);
        Route::get('/profile/volunteer', [ProfileController::class, 'getVolunteerHistory']);

        Route::get('/profile/history', [ProfileController::class, 'getCompleteHistory']);
        Route::get('/profile/stats', [ProfileController::class, 'getStats']);

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER APPLICATIONS
        |--------------------------------------------------------------------------
        */

        Route::apiResource('volunteer-applications', VolunteerApplicationController::class);

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        Route::prefix('admin')->group(function () {

            // Dashboard
            Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats']);
            Route::get('/dashboard/overview', [AdminController::class, 'dashboardOverview']);

            // Campaign stats
            Route::get('/campaigns/stats', [AdminController::class, 'campaignStats']);

            // Donation stats
            Route::get('/donations/stats', [AdminController::class, 'donationStats']);

            // Notifications
            Route::get('/notifications', [AdminController::class, 'getNotifications']);
            Route::get('/notifications/unread-count', [AdminController::class, 'getUnreadNotificationsCount']);

            Route::put('/notifications/mark-all-read', [AdminController::class, 'markAllAsRead']);
            Route::put('/notifications/{id}/mark-read', [AdminController::class, 'markAsRead']);

            Route::post('/notifications/send', [AdminController::class, 'sendNotification']);

            // Users
            Route::get('/users', [AdminController::class, 'getUsers']);
            Route::get('/users/{id}', [AdminController::class, 'getUser']);

            Route::put('/users/{id}', [AdminController::class, 'updateUser']);
            Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);

            // Volunteer Campaigns Admin
            Route::get('/volunteer-campaigns-admin', [AdminController::class, 'adminVolunteerCampaigns']);
            Route::get('/volunteer-campaigns-admin/{id}', [AdminController::class, 'adminVolunteerCampaign']);

            // Donation Campaigns
            Route::apiResource('donation-campaigns', AdminDonationCampaignController::class);
        });
    });
});