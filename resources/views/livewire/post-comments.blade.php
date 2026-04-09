<div class="mt-16 pt-8 border-t border-slate-800">
    <h3 class="text-2xl font-bold text-white mb-8">Komentar ({{ $comments->count() }})</h3>

    {{-- Comment Form --}}
    <form wire:submit.prevent="addComment" class="mb-12 bg-slate-800/20 p-6 rounded-2xl border border-slate-700/50">
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-400 bg-green-500/10 rounded-lg border border-green-500/20" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-slate-300">Nama</label>
            <input type="text" id="name" wire:model="name" class="bg-slate-800 border border-slate-700 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 placeholder-slate-500" placeholder="Masukkan nama Anda" required>
            @error('name') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="mb-4">
            <label for="content" class="block mb-2 text-sm font-medium text-slate-300">Komentar</label>
            <textarea id="content" wire:model="content" rows="4" class="bg-slate-800 border border-slate-700 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 placeholder-slate-500" placeholder="Tulis komentar Anda di sini..." required></textarea>
            @error('content') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:outline-none focus:ring-cyan-800 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center transition-colors">
            Kirim Komentar
        </button>
    </form>

    {{-- Comments List --}}
    <div class="space-y-6">
        @forelse ($comments as $comment)
            <div class="p-6 bg-slate-800/30 rounded-2xl border border-slate-700/50">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($comment->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-white font-medium">{{ $comment->name }}</h4>
                        <p class="text-xs text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-slate-300 leading-relaxed text-sm lg:text-base">
                    {{ $comment->content }}
                </p>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-slate-400">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            </div>
        @endforelse
    </div>
</div>
