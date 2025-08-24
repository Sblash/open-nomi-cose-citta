<?php

namespace App\Http\Livewire;

use App\Models\Game;
use App\Models\Round;
use App\Models\Player;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
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

    protected $polling = [
        'interval' => 2000, // 2 seconds
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
            $this->settings = $this->game->settings ?? [
                'letters' => ['A', 'B', 'C', 'D', 'E'], // Lettere predefinite
                'rounds' => 3, // Numero di round predefinito
                'categories' => ['Nomi', 'Cose', 'Città'] // Categorie predefinite
            ];
            $this->newCategory = '';
        }
    }

    public function loadPlayers()
    {
        $this->players = $this->game->players()->orderBy('score', 'desc')->get();
    }

    public function joinGame()
    {
        // Only validate if the name field is present
        if ($this->name) {
            $this->validate(['name' => 'required|min:3']);

            // Check if the player already exists
            $existingPlayer = Player::where('game_id', $this->game->id)
                ->where('name', $this->name)
                ->first();

            if (!$existingPlayer) {
                Player::create([
                    'game_id' => $this->game->id,
                    'name' => $this->name,
                    'score' => 0,
                ]);
            }

            $this->loadPlayers();
        }
    }

    public function startGame()
    {
        Log::info("Game starting");
        // Salva le impostazioni nel gioco
        $this->game->update([
            'status' => 'starting', // Stato temporaneo per sincronizzare l'avvio
            'settings' => $this->settings,
            'start_time' => now() // Aggiunge un timestamp di avvio
        ]);

        // Log the updated game status and start time
        Log::info("Game status set to starting");
        Log::info("Start time set to " . $this->game->start_time);

        // Create the first round
        $this->game->createFirstRound();

        // Update the game status to in_progress immediately
        $this->game->update(['status' => 'in_progress']);
        Log::info("Game status updated to in_progress");

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

        // Log the current game status
        Log::info('Game status: ' . $this->game->status);

        // Log the current time and start time
        Log::info('Current time: ' . now());
        Log::info('Start time: ' . $this->game->start_time);

        // Controlla se sono passati 5 secondi dal timestamp di avvio
        if ($this->game->status === 'starting' && now()->diffInSeconds($this->game->start_time) >= 5) {
            // Cambia lo stato a 'in_progress' dopo 5 secondi
            $this->game->update(['status' => 'in_progress']);
            Log::info('Game status updated to in_progress');
        }

        if ($this->game->status === 'in_progress' && !is_null($this->game->id)) {
            // Redirect to the game round
            $round = Round::where("game_id", $this->game->id)->where('round_number', 1)->first();
            return redirect()->route('game.round', ['game' => $this->game->id, 'round' => $round->id]);
        }
    }

}
