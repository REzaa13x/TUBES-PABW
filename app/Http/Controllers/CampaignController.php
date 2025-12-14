<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerCampaign;

class CampaignController extends Controller
{
    // FUNGSI 1: Untuk Landing Page (Halaman Utama /relawan)
    // Hanya menampilkan 3-6 kampanye terbaru sebagai teaser
    public function landing()
    {
        $campaigns = VolunteerCampaign::where('status', 'Aktif')
            ->latest()
            ->take(3) // Ambil 3 saja untuk teaser di depan
            ->get();

        // Return ke file Landing Page
        return view('volunteer.index', compact('campaigns'));
    }

    // FUNGSI 2: Untuk Halaman List Kampanye (/volunteer/campaigns)
    // Menampilkan SEMUA kampanye dengan pagination
    public function index()
    {
        $campaigns = VolunteerCampaign::where('status', 'Aktif')
            ->latest()
            ->paginate(9); // Gunakan pagination agar rapi

        // Return ke file List Kampanye (Pastikan file ini ada!)
        // Lokasi file: resources/views/volunteer/campaigns/index.blade.php
        return view('volunteer.campaigns.index', compact('campaigns'));
    }

    public function show($slug)
    {
        $campaign = VolunteerCampaign::where('slug', $slug)->firstOrFail();
        return view('volunteer.register', compact('campaign'));
    }
}
