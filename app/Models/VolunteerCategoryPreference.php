<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerCategoryPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'is_preferred',
        'auto_discovered',
        'engagement_score',
    ];

    protected $casts = [
        'is_preferred' => 'boolean',
        'auto_discovered' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk mendapatkan preference yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_preferred', true);
    }

    /**
     * Scope untuk mendapatkan kategori berdasarkan engagement score
     */
    public function scopeOrderByEngagement($query)
    {
        return $query->orderByDesc('engagement_score');
    }
}
