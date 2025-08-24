<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\GameList;
use App\Http\Livewire\GameLobby;
use App\Http\Livewire\GameRound;
use App\Http\Livewire\GameResults;

Route::get('/', GameList::class)->name('game.list');
Route::get('/game/{game}/lobby', GameLobby::class)->name('game.lobby');
Route::get('/game/{game}/round/{round}', GameRound::class)->name('game.round');
Route::get('/game/{game}/results', GameResults::class)->name('game.results');
