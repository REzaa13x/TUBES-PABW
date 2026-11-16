<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'amount',
        'recipient',
        'description',
        'proof_image',
        'status',
        'admin_note',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getProofImageAttribute($value)
    {
        if (!$value) return asset('images/placeholder.jpg');
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        if (file_exists(public_path($value))) {
            return asset($value);
        }
        if (file_exists(public_path('storage/' . $value))) {
            return asset('storage/' . $value);
        }
        $pathParts = explode('/', $value);
        if (count($pathParts) >= 2) {
            $folder = $pathParts[0];
            $filename = implode('/', array_slice($pathParts, 1));
            return url("/api/images/{$folder}/{$filename}");
        }
        return asset($value);
    }
}
