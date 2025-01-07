<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}

