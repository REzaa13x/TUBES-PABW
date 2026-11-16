<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'title',
        'message',
        'category',
        'priority',
        'icon',
        'type',
        'action_label',
        'action_url',
        'secondary_action_label',
        'secondary_action_url',
        'related_campaign_id',
        'related_user_id',
        'related_donation_id',
        'related_volunteer_id',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime',
        'metadata' => 'json',
    ];

    /**
     * Relasi ke Campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'related_campaign_id');
    }

    /**
     * Relasi ke User (pembuat kampanye atau pengguna terkait)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    /**
     * Relasi ke Donation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class, 'related_donation_id');
    }

    /**
     * Relasi ke Volunteer
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class, 'related_volunteer_id');
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'semua') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan prioritas
     */
    public function scopeByPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDateRange($query, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('message', 'like', "%{$search}%");
        });
    }

    /**
     * Tandai sebagai dibaca
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return $this;
    }

    /**
     * Tandai sebagai belum dibaca
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
        return $this;
    }

    /**
     * Get semua unread notifications count
     */
    public static function getUnreadCount()
    {
        return self::unread()->count();
    }

    /**
     * Get kategori dengan style
     */
    public function getCategoryStyleAttribute()
    {
        $styles = [
            'kampanye' => ['label' => 'Kampanye', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
            'donasi' => ['label' => 'Donasi', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
            'relawan' => ['label' => 'Relawan', 'color' => 'purple', 'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'laporan' => ['label' => 'Laporan', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
            'smart_matching' => ['label' => 'Smart Matching', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'sistem' => ['label' => 'Sistem', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'],
        ];

        return $styles[$this->category] ?? $styles['sistem'];
    }

    /**
     * Get prioritas dengan style
     */
    public function getPriorityStyleAttribute()
    {
        $styles = [
            'high' => ['label' => 'Tinggi', 'color' => 'red', 'icon' => '🔴', 'border' => 'border-l-4 border-red-500'],
            'medium' => ['label' => 'Sedang', 'color' => 'yellow', 'icon' => '🟡', 'border' => 'border-l-4 border-yellow-500'],
            'low' => ['label' => 'Rendah', 'color' => 'green', 'icon' => '🟢', 'border' => 'border-l-4 border-green-500'],
        ];

        return $styles[$this->priority] ?? $styles['low'];
    }

    /**
     * Get time ago string
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Resolve the action URL dynamically to distinguish between donation campaigns and volunteer campaigns.
     */
    public function getResolvedActionUrlAttribute()
    {
        $url = $this->action_url;
        if (!$url) {
            return null;
        }

        // Check if URL is for /admin/campaigns/{id}
        if (preg_match('/\/admin\/campaigns\/(\d+)/', $url, $matches)) {
            $campaignId = $matches[1];

            $isVolunteer = false;
            if ($this->category === 'relawan' || $this->category === 'smart_matching' || str_contains(strtolower($this->title), 'relawan') || str_contains(strtolower($this->message), 'relawan')) {
                $isVolunteer = true;
            }

            if ($isVolunteer) {
                return route('admin.relawan.show', $campaignId);
            }

            // Fallback: check if the campaign does not exist in donation campaigns but exists in volunteer campaigns
            if (!\App\Models\Campaign::where('id', $campaignId)->exists() && \App\Models\VolunteerCampaign::where('id', $campaignId)->exists()) {
                return route('admin.relawan.show', $campaignId);
            }
        }

        // Check if category is 'relawan' or type is 'volunteer_registered' and points to a volunteer or user
        if ($this->category === 'relawan' || $this->type === 'volunteer_registered') {
            if ($this->related_volunteer_id) {
                // If it's a volunteer application ID, check if we can go to verifikasi-relawan
                if (\App\Models\VolunteerApplication::where('id', $this->related_volunteer_id)->exists()) {
                    return route('admin.verifikasi-relawan.show', $this->related_volunteer_id);
                }
            }
            
            // Try to find volunteer application if user and campaign ids are available
            if ($this->related_campaign_id && $this->related_user_id) {
                $app = \App\Models\VolunteerApplication::where('volunteer_campaign_id', $this->related_campaign_id)
                    ->where('user_id', $this->related_user_id)
                    ->first();
                if ($app) {
                    return route('admin.verifikasi-relawan.show', $app->id);
                }
            }
        }

        return $url;
    }
};
