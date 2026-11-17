<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CampaignGeocodingService
{
    private function request(string $action, array $params): array
    {
        $params += ['format' => 'jsonv2', 'addressdetails' => 1, 'accept-language' => 'id'];
        if (config('maps.contact_email')) $params['email'] = config('maps.contact_email');
        $endpoint = rtrim(config('maps.geocoding_url'), '/').'/'.$action;
        $key = 'campaign-geocode:'.hash('sha256', $endpoint.json_encode($params));
        $cached = Cache::get($key);
        if ($cached !== null) return $cached;
        $lock = Cache::lock('campaign-geocode-provider-lock', 20);
        if (!$lock->get()) throw new HttpException(429, 'Layanan alamat sedang sibuk. Coba lagi dalam beberapa detik.');
        try {
            // Shared by all users, not one request per user. No autocomplete.
            if (RateLimiter::tooManyAttempts('campaign-geocode-provider', 1)) {
                throw new HttpException(429, 'Layanan alamat sedang sibuk. Coba lagi dalam beberapa detik.');
            }
            RateLimiter::hit('campaign-geocode-provider', 1);
            try {
                $response = Http::acceptJson()->withUserAgent('DonGivMobile/1.0 (+'.config('app.url').')')
                    ->connectTimeout(5)->timeout(12)->get($endpoint, $params);
                if (!$response->successful() || !is_array($response->json()) || isset($response->json()['error'])) {
                    throw new \RuntimeException('Geocoder unavailable');
                }
                $body = $response->json();
            } catch (\Throwable $e) {
                throw new HttpException(503, 'Alamat belum dapat dimuat. Periksa koneksi dan coba lagi.', $e);
            }
            Cache::put($key, $body, now()->addDay());
            return $body;
        } finally {
            $lock->release();
        }
    }

    public function search(string $query): array
    {
        return array_map(fn ($item) => $this->format($item),
            $this->request('search', ['q' => $query, 'countrycodes' => 'id', 'limit' => 5]));
    }

    public function reverse(float $latitude, float $longitude): array
    {
        $item = $this->request('reverse', ['lat' => $latitude, 'lon' => $longitude, 'zoom' => 18]);
        // Reverse results may describe a nearby road centroid. Keep the actual
        // coordinate selected on the map/GPS, rather than moving the user's pin.
        return [...$this->format($item), 'latitude' => $latitude, 'longitude' => $longitude];
    }

    private function format(array $item): array
    {
        $address = $item['address'] ?? [];
        if (empty($item['display_name']) || !isset($item['lat'], $item['lon'])) {
            throw new HttpException(503, 'Data alamat dari penyedia tidak lengkap. Silakan pilih titik lain.');
        }
        return [
            'latitude' => (float) $item['lat'], 'longitude' => (float) $item['lon'],
            'address' => $item['display_name'],
            'district' => $address['city_district'] ?? $address['district'] ?? '',
            'city' => $address['city'] ?? $address['town'] ?? $address['county'] ?? '',
            'province' => $address['state'] ?? '', 'postal_code' => $address['postcode'] ?? '',
        ];
    }
}
