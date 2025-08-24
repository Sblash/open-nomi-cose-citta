<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'game_id' => \App\Models\Game::factory(),
            'name' => $this->faker->name,
            'score' => 0,
        ];
    }
}
