<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Donation.php
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

}
