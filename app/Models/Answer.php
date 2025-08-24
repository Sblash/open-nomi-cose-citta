<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['player_id', 'round_id', 'category', 'answer', 'score'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }
}
