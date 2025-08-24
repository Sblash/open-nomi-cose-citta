<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Game;

class GameResults extends Component
{
    public $game;
    public $players;

    public function mount(Game $game)
    {
        $this->game = $game;
        $this->players = $game->players()->orderBy('score', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.game-results');
    }
}
