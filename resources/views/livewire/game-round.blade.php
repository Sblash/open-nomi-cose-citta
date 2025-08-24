<div>
    <h1 class="text-2xl font-bold mb-4">Round {{ $round->round_number }}</h1>
    <h2 class="text-xl font-bold mb-4">Letter: {{ $letter }}</h2>

    @if(!$showVote)
    <form class="mb-4">
        @foreach($categories as $category)
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">{{ $category }}:</label>
            <input type="text" wire:model="words.{{ $category }}" required class="border rounded py-2 px-3 w-full">
        </div>
        @endforeach
        <button
            type="submit"
            wire:click="submitWords"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            @if(in_array('', $this->words, true)) disabled @endif
            >
            Bong!
        </button>

    </form>
    <div class="text-lg font-bold">Time left: {{ $timeLeft }}</div>
    @else
    <h2 class="text-xl font-bold mb-4">Voting</h2>
    <form wire:submit.prevent="submitVotes" class="mb-4">
        @foreach($votes as $wordId => $vote)
        @php
        $word = App\Models\Word::find($wordId);
        @endphp
        <div class="mb-2">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                {{ $word->player->name }} - {{ $word->category }}: {{ $word->word }}
            </label>
            <select wire:model="votes.{{ $wordId }}" class="border rounded py-2 px-3 w-full">
                <option value="">Select</option>
                <option value="1">Correct</option>
                <option value="0">Incorrect</option>
            </select>
        </div>
        @endforeach
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Submit Votes
        </button>
    </form>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('startTimer', () => {
            setTimeout(() => { // Ritardo di 2 secondi
                let timeLeft = 60;
                let timer = setInterval(() => {
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        @this.call('submitWords');
                    }
                    @this.call('decrementTimer');
                }, 1000);
            }, 2000);
        });
    });
</script>
