<div class="mt-16 pt-8 border-t border-slate-800">
    <h3 class="text-2xl font-bold text-white mb-8">Komentar ({{ $comments->count() }})</h3>

    {{-- Admin: Pending Comments --}}
    @auth
        @if ($pendingCount > 0)
            <div class="mb-8 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-2xl">
                <h4 class="text-sm font-semibold text-yellow-400 uppercase tracking-wider mb-4 flex items-center">
                    <i data-lucide="shield-alert" class="w-4 h-4 mr-2"></i>
                    Menunggu Moderasi ({{ $pendingCount }})
                </h4>
                <div class="space-y-4">
                    @foreach ($pendingComments as $pending)
                        <div class="p-4 bg-slate-800/30 rounded-xl border border-slate-700/50">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-500 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($pending->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h5 class="text-white font-medium text-sm">{{ $pending->name }}</h5>
                                        <div class="flex items-center space-x-2 text-xs text-slate-400 font-mono">
                                            <span>{{ $pending->created_at->diffForHumans() }}</span>
                                            <span class="text-yellow-500">• spam: {{ $pending->spam_score }}%</span>
                                            @if ($pending->honeypot_triggered)
                                                <span class="text-red-400">• 🍯 honeypot</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="approveComment({{ $pending->id }})" class="text-green-400 hover:text-green-500 p-1.5 rounded-lg hover:bg-green-500/10 transition-colors" title="Setujui">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    <button wire:click="deleteComment({{ $pending->id }})" wire:confirm="Yakin ingin menghapus komentar ini?" class="text-rose-400 hover:text-rose-500 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-slate-300 text-sm leading-relaxed">{{ $pending->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    {{-- Comment Form --}}
    <form wire:submit.prevent="addComment" class="mb-12 bg-slate-800/20 p-6 rounded-2xl border border-slate-700/50">
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-400 bg-green-500/10 rounded-lg border border-green-500/20" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('comment_pending'))
            <div class="p-4 mb-4 text-sm text-yellow-400 bg-yellow-500/10 rounded-lg border border-yellow-500/20" role="alert">
                <i data-lucide="clock" class="w-4 h-4 inline mr-1"></i>
                Komentar Anda sedang menunggu moderasi dan akan tampil setelah disetujui.
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-rose-400 bg-rose-500/10 rounded-lg border border-rose-500/20" role="alert">
                {{ session('error') }}
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

        {{-- Honeypot: hidden field to trap bots --}}
        <div class="absolute overflow-hidden" style="position: absolute; left: -9999px; top: -9999px; opacity: 0; height: 0; width: 0;" aria-hidden="true" tabindex="-1">
            <label for="website">Website</label>
            <input type="text" id="website" wire:model="website" autocomplete="off" tabindex="-1">
        </div>

        <button type="submit" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:outline-none focus:ring-cyan-800 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center transition-colors">
            Kirim Komentar
        </button>
    </form>

    {{-- Approved Comments List --}}
    <div class="space-y-6">
        @forelse ($comments as $comment)
            <div class="p-6 bg-slate-800/30 rounded-2xl border border-slate-700/50">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($comment->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-white font-medium">{{ $comment->name }}</h4>
                            <p class="text-xs text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @auth
                        <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Yakin ingin menghapus komentar ini?" class="text-rose-400 hover:text-rose-500 p-1.5 rounded-lg hover:bg-rose-500/10 transition-colors" title="Hapus Komentar">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    @endauth
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
