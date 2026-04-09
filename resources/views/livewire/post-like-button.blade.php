<div class="flex items-center space-x-2">
    <button wire:click="toggleLike" 
            class="group flex items-center space-x-2 px-4 py-2 rounded-full transition-all duration-300 {{ $hasLiked ? 'bg-rose-500/10 text-rose-500 border border-rose-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700 hover:text-rose-400' }}"
            title="{{ $hasLiked ? 'Unlike' : 'Like' }}">
        <svg xmlns="http://www.w3.org/2000/svg" 
             class="h-5 w-5 transition-transform group-active:scale-90 {{ $hasLiked ? 'fill-current' : 'fill-none' }}" 
             viewBox="0 0 24 24" 
             stroke="currentColor" 
             stroke-width="{{ $hasLiked ? '0' : '2' }}">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span class="font-medium font-mono text-sm">{{ $likesCount }}</span>
    </button>
</div>
