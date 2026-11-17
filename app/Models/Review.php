<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'activity_type', 'activity_id', 'reference_id',
        'campaign_title', 'rating', 'comment', 'is_anonymous',
    ];

    protected $casts = ['user_id' => 'integer', 'activity_id' => 'integer', 'rating' => 'integer', 'is_anonymous' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
