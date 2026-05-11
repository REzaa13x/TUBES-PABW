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
        if (!$value) return null;
        return asset('storage/' . $value);
    }
}
