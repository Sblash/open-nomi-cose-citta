<?php

namespace App\Http\Livewire;

use App\Models\Game;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class GameList extends Component
{
    public $games;
    public $playerName;

    public function mount()
    {
        Log::info('GameList mount method called');
        $this->loadGames();
        $this->playerName = Session::get('player_name', 'Player_' . rand(1000, 9999));
    }

    public function loadGames()
    {
        $this->games = Game::where('status', 'lobby')->get();
    }

    public function createGame()
    {
        $game = Game::create([
            'status' => 'lobby',
            'settings' => [
                'letters' => range('A', 'Z'),
                'rounds' => 3,
                'categories' => [
                    'Nomi',
                    'Cose',
                    'Città',
                    'Animali',
                    'Piante',
                    'Paesi',
                    'Fiumi',
                    'Mestieri',
                    'Cibi',
                    'Film'
                ]
            ]
        ]);

        return redirect()->route('game.lobby', ['game' => $game->id]);
    }

    public function joinGame($gameId)
    {
        Session::put('player_name', $this->playerName);
        return redirect()->route('game.lobby', ['game' => $gameId]);
    }

    public function render()
    {
        return view('livewire.game-list');
    }

}
