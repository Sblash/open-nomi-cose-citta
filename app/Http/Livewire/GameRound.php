<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Round;
use App\Models\Word;
use App\Models\Player;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class GameRound extends Component
{
    public $game;
    public $round;
    public $player;
    public $categories;
    public $words = [];
    public $timeLeft = 60;
    public $roundStatus;
    public $letter;
    public $showVote = false;
    public $votes = [];

    protected $listeners = ['timerTick' => 'decrementTimer'];

    public function mount(Game $game, Round $round)
    {
        $this->game = $game;
        $this->round = $round;
        $this->letter = $round->letter;
        $this->roundStatus = $round->status;
        $this->categories = $game->settings['categories'];

        // Per semplicità, assumiamo che il player sia identificato da un cookie o sessione
        // In un'applicazione reale, avremmo un sistema di autenticazione
        $this->player = Player::where('game_id', $game->id)->first(); // Questo è un placeholder

        // Inizializza le parole per ogni categoria
        foreach ($this->categories as $category) {
            $this->words[$category] = '';
        }

        // Se il round è in stato di votazione, carica le parole da votare
        if ($round->status === 'voting') {
            $this->showVote = true;
            $this->loadWordsForVoting();
        }

        // Avvia il timer se il round è in progress
        if ($round->status === 'in_progress') {
            $this->startTimer();
        }
    }

    public function startTimer()
    {
        $this->timeLeft = 60;
        $this->dispatch('startTimer');
    }

    public function decrementTimer()
    {
        $this->timeLeft--;
        if ($this->timeLeft <= 0) {
            $this->submitWords();
        }
    }

    public function submitWords()
    {
        // Salva le parole del giocatore nel database
        foreach ($this->words as $category => $word) {
            Word::create([
                'round_id' => $this->round->id,
                'player_id' => $this->player->id,
                'category' => $category,
                'word' => $word,
                'status' => 'pending',
            ]);
        }

        // Imposta il round come finito per questo giocatore
        // In un'applicazione reale, dovremmo sincronizzare questo con tutti i giocatori
        $this->round->update(['status' => 'voting']);
        $this->showVote = true;
        $this->loadWordsForVoting();
    }

    public function loadWordsForVoting()
    {
        // Carica tutte le parole per questo round
        $words = Word::where('round_id', $this->round->id)->get();

        foreach ($words as $word) {
            $this->votes[$word->id] = null; // Inizializza i voti a null
        }
    }

    public function submitVotes()
    {
        // Salva i voti nel database
        foreach ($this->votes as $wordId => $vote) {
            if ($vote !== null) {
                Vote::create([
                    'word_id' => $wordId,
                    'player_id' => $this->player->id,
                    'vote' => $vote,
                ]);
            }
        }

        // Calcola i punteggi e passa al round successivo o termina il gioco
        $this->calculateScores();

        if ($this->round->round_number < $this->game->settings['rounds']) {
            // Crea un nuovo round
            $nextRoundNumber = $this->round->round_number + 1;
            $newRound = Round::create([
                'game_id' => $this->game->id,
                'round_number' => $nextRoundNumber,
                'letter' => $this->game->settings['letters'][array_rand($this->game->settings['letters'])],
                'status' => 'in_progress',
            ]);

            return redirect()->route('game.round', ['game' => $this->game->id, 'round' => $nextRoundNumber]);
        } else {
            // Termina il gioco
            $this->game->update(['status' => 'finished']);
            return redirect()->route('game.results', ['game' => $this->game->id]);
        }
    }

    public function calculateScores()
    {
        // Logica per calcolare i punteggi basata sui voti
        $words = Word::where('round_id', $this->round->id)->get();

        foreach ($words as $word) {
            $votes = Vote::where('word_id', $word->id)->get();
            $correctVotes = $votes->where('vote', true)->count();
            $incorrectVotes = $votes->where('vote', false)->count();

            // Determina lo stato della parola basato sui voti
            if ($correctVotes > $incorrectVotes) {
                $word->status = 'correct';
            } else {
                $word->status = 'incorrect';
            }
            $word->save();

            // Aggiorna il punteggio del giocatore
            $player = $word->player;
            if ($word->status === 'correct') {
                // Controlla se la parola è unica
                $similarWords = Word::where('round_id', $this->round->id)
                                    ->where('category', $word->category)
                                    ->where('word', $word->word)
                                    ->get();
                if ($similarWords->count() === 1) {
                    $player->score += 10;
                } else {
                    $player->score += 5;
                }
            } else {
                $player->score -= 5;
            }
            $player->save();
        }
    }

    public function render()
    {
        return view('livewire.game-round');
    }
}
