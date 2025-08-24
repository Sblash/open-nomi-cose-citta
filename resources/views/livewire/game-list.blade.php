<div>
    <h1 class="text-2xl font-bold mb-4">Game List</h1>
    <div class="mb-4">
        <label for="player_name" class="block text-gray-700 text-sm font-bold mb-2">Your Name:</label>
        <input type="text" id="player_name" wire:model="playerName" class="border rounded py-2 px-3 w-full">
    </div>
    <button wire:click="createGame" wire:navigate class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        + Crea Game
    </button>
    <!-- <div>
        Debug: {{ isset($games) ? print_r($games->toArray()) : 'null' }}
    </div> -->
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @if(isset($games) && count($games) > 0)
            @foreach($games as $game)
                @php
                    $title = "Game " . $game->id;
                    $description = "Join the game and have fun!";
                    $buttonText = "Go to Game";
                @endphp
                <div class="border rounded-lg p-4">
                    <h2 class="text-xl font-bold mb-2">{{ $title }}</h2>
                    <p class="mb-2">{{ $description }}</p>
                    <button wire:click="joinGame({{ $game->id }})" wire:navigate class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ $buttonText }}
                    </button>
                </div>
            @endforeach
        @else
            <p>No games available</p>
        @endif
    </div>
</div>
