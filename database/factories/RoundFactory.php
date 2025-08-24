<?php

namespace Database\Factories;

use App\Models\Round;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoundFactory extends Factory
{
    protected $model = Round::class;

    public function definition()
    {
        return [
            'game_id' => \App\Models\Game::factory(),
            'round_number' => $this->faker->numberBetween(1, 10),
            'letter' => $this->faker->randomLetter,
            'status' => 'in_progress',
        ];
    }
}
