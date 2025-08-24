<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'round_number',
        'letter',
        'status',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}
