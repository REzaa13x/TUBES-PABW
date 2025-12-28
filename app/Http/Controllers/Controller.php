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
        $campaigns = Campaign::where('status', 'Active')
            ->select(['id', 'title', 'description', 'image', 'target_amount', 'current_amount', 'end_date', 'status', 'kategori', 'slug'])
            ->get();

        // Get urgent campaigns: campaigns that have less than 3 days remaining and have collected at least 50% of their target
        $urgentCampaigns = Campaign::where('status', 'Active')
            ->select(['id', 'title', 'description', 'image', 'target_amount', 'current_amount', 'end_date', 'status', 'kategori', 'slug'])
            ->whereRaw('DATEDIFF(end_date, NOW()) <= 3')
            ->whereRaw('(current_amount / target_amount) >= 0.5')
            ->orderByRaw('DATEDIFF(end_date, NOW()) ASC')
            ->orderByRaw('(current_amount / target_amount) DESC')
            ->get();

        // Limit to 4 urgent campaigns or take all if less than 4
        $urgentCampaigns = $urgentCampaigns->take(4);

        // Get active volunteer campaigns from the database
        $volunteerCampaigns = \App\Models\VolunteerCampaign::where('status', 'Aktif')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('home', compact('campaigns', 'urgentCampaigns', 'volunteerCampaigns'));
    }
}
