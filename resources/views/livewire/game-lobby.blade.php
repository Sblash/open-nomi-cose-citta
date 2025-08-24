<div>
    <h1 class="text-2xl font-bold mb-4">Game Lobby</h1>

    <!-- Impostazioni del gioco -->
    <div class="mb-4 p-4 border rounded-lg">
        <h2 class="text-xl font-bold mb-2">Game Settings</h2>

        <!-- Selezione delle lettere -->
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Available Letters:</label>
            <div class="flex flex-wrap gap-2">
                @foreach(range('A', 'Z') as $letter)
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        wire:model="settings.letters"
                        value="{{ $letter }}"
                        class="form-checkbox">
                    <span class="ml-2">{{ $letter }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Numero di round -->
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Number of Rounds:</label>
            <input
                type="number"
                wire:model="settings.rounds"
                min="1"
                max="10"
                class="border rounded py-2 px-3 w-full">
        </div>

        <!-- Selezione delle categorie -->
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Categories:</label>
            <div class="flex flex-wrap gap-2">
                @foreach($settings['categories'] as $category)
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        wire:model="settings.categories"
                        value="{{ $category }}"
                        class="form-checkbox">
                    <span class="ml-2">{{ $category }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Form per aggiungere una nuova categoria -->
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">Add New Category:</label>
            <form wire:submit.prevent="addCategory" class="flex">
                <input
                    type="text"
                    wire:model="newCategory"
                    placeholder="New category"
                    class="border rounded py-2 px-3 mr-2">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add
                </button>
            </form>
        </div>
    </div>

    <!-- Lista dei giocatori -->
    <div class="mb-4">
        <h2 class="text-xl font-bold mb-2">Players</h2>
        <ul>
            @foreach($players as $player)
            <li class="mb-1">{{ $player->name }} (Score: {{ $player->score }})</li>
            @endforeach
        </ul>
    </div>

    <!-- Pulsante per iniziare il gioco -->
    @if($players->count() > 0)
    <button wire:click="startGame" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Start Game
    </button>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', function() {
        // Polling per controllare lo stato del gioco ogni 2 secondi
        const gameStatusInterval = setInterval(function() {
            @this.call('checkGameStatus');
        }, 2000);

        // Polling per aggiornare la lista dei giocatori ogni 2 secondi
        const playerInterval = setInterval(function() {
            @this.call('loadPlayers');
        }, 2000);

        // No longer needed as redirect is handled in the GameLobby component
    });
</script>
