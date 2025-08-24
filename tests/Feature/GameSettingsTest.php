<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testAddCategory()
    {
        // Create a new game
        $game = Game::factory()->create();

        // Add a category
        $game->addCategory('Animals');

        // Reload the game from the database
        $game->refresh();

        // Check if the category was added
        $this->assertContains('Animals', $game->settings['categories']);
    }
}
