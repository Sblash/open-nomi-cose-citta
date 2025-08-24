<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'player_id',
        'category',
        'word',
        'status',
        'votes',
    ];

    protected $casts = [
        'votes' => 'array',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
