<div>
    <h1 class="text-2xl font-bold mb-4">Game Results</h1>
    <h2 class="text-xl font-bold mb-2">Final Scores</h2>
    <ol class="list-decimal pl-5">
        @foreach($players as $player)
            <li class="mb-1">{{ $player->name }}: {{ $player->score }}</li>
        @endforeach
    </ol>
</div>
