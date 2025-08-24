<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Facades\Session;

class GameLobby extends Component
{
    public $game;
    public $players = [];
    public $name;
    public $settings;
    public $newCategory = '';

    protected $rules = [
        'name' => 'required|min:3',
        'newCategory' => 'required|min:3',
    ];

    public function mount(Game $game)
    {
        $this->game = $game;
        $this->settings = $game->settings ?? [
            'letters' => ['A', 'B', 'C', 'D', 'E'], // Lettere predefinite
            'rounds' => 3, // Numero di round predefinito
            'categories' => ['Nomi', 'Cose', 'Città'] // Categorie predefinite
        ];
        $this->name = Session::get('player_name', '');
        $this->joinGame();
        $this->loadPlayers();
    }

    public function addCategory()
    {
        $this->validate(['newCategory' => 'required|min:3']);

        if (!in_array($this->newCategory, $this->settings['categories'])) {
            $this->game->addCategory($this->newCategory);
            $this->newCategory = '';
        }
    }

    public function loadPlayers()
    {
        $this->players = $this->game->players()->orderBy('score', 'desc')->get();
    }

    public function joinGame()
    {
        $this->validate();

        Player::create([
            'game_id' => $this->game->id,
            'name' => $this->name,
            'score' => 0,
        ]);

        $this->loadPlayers();
    }

    public function startGame()
    {
        // Salva le impostazioni nel gioco
        $this->game->update([
            'status' => 'starting', // Stato temporaneo per sincronizzare l'avvio
            'settings' => $this->settings
        ]);

        // Avvia un polling per verificare quando tutti i giocatori sono pronti
        $this->dispatch('game-starting');
    }

    public function render()
    {
        $this->loadPlayers(); // Aggiorna la lista dei giocatori ad ogni render
        return view('livewire.game-lobby');
    }

    public function checkGameStatus()
    {
        $this->game->refresh(); // Ricarica lo stato del gioco dal database

        if ($this->game->status === 'in_progress') {
            // Reindirizza alla pagina del round
            return redirect()->route('game.round', ['game' => $this->game->id, 'round' => 1]);
        }
    }

}
