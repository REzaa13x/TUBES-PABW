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
use App\Http\Controllers\Api\VolunteerRecommendationController;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('v1')->middleware('cors')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
    */

    // Auth
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

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

    // Public certificate verification
    Route::get('/certificate/verify/{certificateNumber}', [\App\Http\Controllers\Api\CertificateController::class, 'verify']);

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
        | GENERAL NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead']);
        Route::put('/notifications/{id}/mark-read', [NotificationController::class, 'markRead']);
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
        Route::post('/notifications/seed-demo', [NotificationController::class, 'seedDemoNotifications']);

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER CERTIFICATES
        |--------------------------------------------------------------------------
        */
        Route::prefix('certificates')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\CertificateController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\CertificateController::class, 'show']);
            Route::get('/{id}/download-url', [\App\Http\Controllers\Api\CertificateController::class, 'downloadUrl']);
        });

        /*
        |--------------------------------------------------------------------------
        | VOLUNTEER RECOMMENDATIONS & PREFERENCES
        |--------------------------------------------------------------------------
        */

        Route::prefix('recommendations')->group(function () {
            // Categories
            Route::get('/categories', [VolunteerRecommendationController::class, 'getAvailableCategories']);

            // Preferences
            Route::get('/preferences', [VolunteerRecommendationController::class, 'getMyPreferences']);
            Route::post('/preferences', [VolunteerRecommendationController::class, 'updatePreferences']);

            // Recommendations
            Route::get('/campaigns', [VolunteerRecommendationController::class, 'getRecommendations']);
            Route::get('/notifications', [VolunteerRecommendationController::class, 'getRecommendationNotifications']);
            Route::put('/notifications/{notificationId}/read', [VolunteerRecommendationController::class, 'markRecommendationAsRead']);
            Route::put('/notifications/{notificationId}/dismiss', [VolunteerRecommendationController::class, 'dismissRecommendation']);
        });

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
// Reviews shared by Flutter and the website.
Route::prefix('v1')->middleware('cors')->group(function () {
    Route::get('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/reviews/eligibility', [\App\Http\Controllers\Api\ReviewController::class, 'eligibility']);
        Route::post('/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
        Route::put('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'update']);
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    });
});
// Certificate PDFs: bearer-authenticated bytes or a short-lived signed browser link.
Route::prefix('v1')->middleware('cors')->group(function () {
    Route::get('/certificates/{id}/download', [\App\Http\Controllers\Api\CertificateFileController::class, 'download'])
        ->middleware('auth:sanctum');
    Route::get('/certificates/{id}/download-signed', [\App\Http\Controllers\Api\CertificateFileController::class, 'signedDownload'])
        ->middleware('signed:relative')->name('api.certificates.signed-download');
    Route::get('/certificate/qr/{certificateNumber}', [\App\Http\Controllers\Api\CertificateFileController::class, 'qr'])
        ->name('api.certificate.qr');
});
// Address lookup for authenticated campaign applicants. The provider is cached/rate-limited globally.
Route::prefix('v1')->middleware(['cors', 'auth:sanctum', 'throttle:30,1'])->group(function () {
    Route::get('/locations/search', [\App\Http\Controllers\Api\CampaignLocationController::class, 'search']);
    Route::get('/locations/reverse', [\App\Http\Controllers\Api\CampaignLocationController::class, 'reverse']);
});
Route::prefix('v1')->middleware(['cors', 'auth:sanctum', 'throttle:30,1'])->group(function () {
    Route::get('/locations/revese', [\App\Http\Controllers\Api\CampaignLocationController::class, 'reverse']);
});

// Public campaign information and DonGiv help, with per-client throttling.
Route::post('v1/chatbot/message', [\App\Http\Controllers\Api\ChatbotController::class, 'message'])
    ->middleware(['cors', 'throttle:20,1']);
