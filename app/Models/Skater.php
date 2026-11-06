<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Vote;

use Illuminate\Database\Eloquent\Model;

class Skater extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'photo',
    ]; //tells Laravel which model attributes are allowed to be assigned through mass assignment
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    
}
