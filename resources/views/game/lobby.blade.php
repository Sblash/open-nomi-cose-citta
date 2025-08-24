<!-- resources/views/game/lobby.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Lobby: {{ $game->id }}</h1>
    @livewire('game-lobby', ['game' => $game])
@endsection
