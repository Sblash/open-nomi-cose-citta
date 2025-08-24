<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function addCategory($category)
    {
        if (!in_array($category, $this->settings['categories'] ?? [])) {
            $this->settings['categories'][] = $category;
            $this->save();
        }
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }
}
