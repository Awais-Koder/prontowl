<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = [];

    // Donation.php
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

}
