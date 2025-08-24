<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'score',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
