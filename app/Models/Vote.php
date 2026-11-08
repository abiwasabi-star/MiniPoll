<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Skater;

class Vote extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skater()
    {
        return $this->belongsTo(Skater::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
    'user_id',
    'category_id',
    'skater_id',
];
}