<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use App\Models\Word;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class GameRoundBongTest extends TestCase
{
    use RefreshDatabase;

    public function testBongAndSubmit()
    {
        // Create a new game
        $game = Game::factory()->create([
            'settings' => [
                'letters' => ['A', 'B', 'C', 'D', 'E'],
                'rounds' => 3,
                'categories' => ['Nomi', 'Cose', 'Città']
            ]
        ]);

        // Create a player
        $player = Player::factory()->create(['game_id' => $game->id]);

        // Create a round
        $round = Round::factory()->create([
            'game_id' => $game->id,
            'round_number' => 1,
            'letter' => 'A',
            'status' => 'in_progress'
        ]);

        // Render the Livewire component
        Livewire::test(\App\Http\Livewire\GameRound::class, ['game' => $game, 'round' => $round])
            ->set('words', ['Nomi' => 'Anna', 'Cose' => 'Albero', 'Città' => 'Amsterdam'])
            ->call('bongAndSubmit');

        // Check if the words were saved
        $this->assertDatabaseHas('words', [
            'round_id' => $round->id,
            'player_id' => $player->id,
            'category' => 'Nomi',
            'word' => 'Anna',
            'status' => 'pending'
        ]);
        $this->assertDatabaseHas('words', [
            'round_id' => $round->id,
            'player_id' => $player->id,
            'category' => 'Cose',
            'word' => 'Albero',
            'status' => 'pending'
        ]);
        $this->assertDatabaseHas('words', [
            'round_id' => $round->id,
            'player_id' => $player->id,
            'category' => 'Città',
            'word' => 'Amsterdam',
            'status' => 'pending'
        ]);

        // Check if the round status was updated
        $this->assertDatabaseHas('rounds', [
            'id' => $round->id,
            'status' => 'voting'
        ]);
    }
}
