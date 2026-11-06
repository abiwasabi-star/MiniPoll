<?php

namespace App\Models;

use App\Models\Skater;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =[
    'name',
    'slug',
    ];

    public function skaters()
    {
        return $this->hasMany(Skater::class);
    }
}
