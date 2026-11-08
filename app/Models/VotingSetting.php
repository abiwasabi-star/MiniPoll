<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingSetting extends Model
{
    protected $fillable = [
        'voting_starts_at',
        'voting_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'voting_starts_at' => 'datetime',
            'voting_ends_at' => 'datetime',
        ];
    }
}