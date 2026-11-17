<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use App\Models\VolunteerCampaign;
use App\Observers\VolunteerCampaignObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Force HTTPS in production
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
        \URL::forceRootUrl(config('app.url'));
    }

    // HAPUS SEMUA LOGIKA FORCE SQLITE YANG TADI

    // Biarkan Gate tetap ada untuk keamanan DonGiv kamu
    Gate::define('admin', function ($user) {
        return $user->role === 'admin';
    });

    Gate::define('donatur', function ($user) {
        return $user->role === 'donatur';
    });

    // Register Volunteer Campaign Observer untuk Smart Recommendation
    VolunteerCampaign::observe(VolunteerCampaignObserver::class);
}
}
