<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameFirstRoundTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateFirstRound()
    {
        // Create a new game
        $game = Game::factory()->create([
            'settings' => [
                'letters' => ['A', 'B', 'C', 'D', 'E'],
                'rounds' => 3,
                'categories' => ['Nomi', 'Cose', 'Città']
            ]
        ]);

        // Create the first round
        $round = $game->createFirstRound();

        // Check if the round was created
        $this->assertNotNull($round);

        // Check if the round has the correct attributes
        $this->assertEquals(1, $round->round_number);
        $this->assertContains($round->letter, ['A', 'B', 'C', 'D', 'E']);
        $this->assertEquals('in_progress', $round->status);

        // Check if the round is associated with the game
        $this->assertEquals($game->id, $round->game_id);
    }
}
