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
        'start_time',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function addCategory($category)
    {
        // Get the current settings array
        $settings = json_decode($this->attributes['settings'] ?? '[]', true);

        // Ensure categories key exists
        if (!isset($settings['categories'])) {
            $settings['categories'] = [];
        }

        // Check if the category already exists
        if (!in_array($category, $settings['categories'])) {
            // Add the category to the settings array
            $settings['categories'][] = $category;

            // Update the settings attribute
            $this->attributes['settings'] = json_encode($settings);
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

    public function createFirstRound()
    {
        // Get the first letter from the settings
        $letters = $this->settings['letters'] ?? ['A', 'B', 'C', 'D', 'E'];
        $letter = $letters[array_rand($letters)];

        // Create the first round
return $this->rounds()->create([
    'round_number' => $this->rounds()->count() + 1,
    'letter' => $letter,
    'status' => 'in_progress',
]);
    }
}
