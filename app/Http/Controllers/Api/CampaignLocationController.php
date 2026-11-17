<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CampaignGeocodingService;
use Illuminate\Http\Request;

class CampaignLocationController extends Controller
{
    public function search(Request $request, CampaignGeocodingService $geocoder)
    {
        $data = $request->validate(['q' => 'required|string|min:3|max:200']);
        return response()->json(['data' => $geocoder->search(trim($data['q']))]);
    }

    public function reverse(Request $request, CampaignGeocodingService $geocoder)
    {
        $data = $request->validate(['latitude' => 'required|numeric|between:-90,90', 'longitude' => 'required|numeric|between:-180,180']);
        return response()->json(['data' => $geocoder->reverse((float) $data['latitude'], (float) $data['longitude'])]);
    }
}
