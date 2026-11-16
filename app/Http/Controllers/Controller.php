<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Campaign;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function home()
    {
        // Get all active campaigns from the database with necessary relationships (will be limited in the view)
        $campaigns = Campaign::where('status', 'verified')
            ->where('end_date', '>=', now())
            ->select(['id', 'title', 'description', 'image', 'target_amount', 'current_amount', 'end_date', 'status', 'kategori', 'slug'])
            ->get();

        // Get urgent campaigns: campaigns that have less than 7 days remaining (regardless of funding level)
        $urgentCampaigns = Campaign::where('status', 'verified')
            ->select(['id', 'title', 'description', 'image', 'target_amount', 'current_amount', 'end_date', 'status', 'kategori', 'slug'])
            ->whereBetween('end_date', [now(), now()->addDays(7)])
            ->orderBy('end_date', 'asc')
            ->orderBy('current_amount', 'desc')
            ->limit(4)
            ->get();

        // Get active volunteer campaigns from the database
        $volunteerCampaigns = \App\Models\VolunteerCampaign::where('status', 'Aktif')
            ->where('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('home', compact('campaigns', 'urgentCampaigns', 'volunteerCampaigns'));
    }
}
