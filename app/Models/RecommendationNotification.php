<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'matching_category',
        'reason',
        'is_read',
        'is_dismissed',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_dismissed' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke VolunteerCampaign
     */
    public function campaign()
    {
        return $this->belongsTo(VolunteerCampaign::class, 'campaign_id');
    }

    /**
     * Scope untuk mendapatkan notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk mendapatkan notifikasi yang tidak di-dismiss
     */
    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false);
    }

    /**
     * Scope untuk urutan terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark as dismissed
     */
    public function markAsDismissed()
    {
        $this->update(['is_dismissed' => true]);
    }
}
