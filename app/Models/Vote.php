<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'word_id',
        'player_id',
        'vote',
    ];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
