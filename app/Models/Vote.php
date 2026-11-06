<?php

namespace App\Models;

use App\Models\Skater;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function skater()
    {
        return $this->belongsTo(Skater::class);
    }
}
