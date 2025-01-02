<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function donations()
    {
        return $this->hasMany(Donation::class , 'campaign_id');
    }
    protected $casts = [
        'gallery_images' => 'array',
    ];
}
