<!-- New Post Toast Notification -->
<div
    x-data
    x-show="$store.postNotif.showToast && $store.postNotif.latestPost"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    x-cloak
    role="status"
    aria-live="polite"
    class="fixed bottom-8 left-4 sm:left-8 z-40 w-80 max-w-[calc(100vw-2rem)] glass-card border border-cyan-500/40 rounded-xl p-4 shadow-2xl shadow-cyan-500/10 bg-slate-900/95 backdrop-blur-xl"
>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0 flex-1">
            <div class="flex items-center space-x-2 mb-1.5">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-[11px] font-mono uppercase tracking-wider text-cyan-400 font-semibold">New Post Published</span>
            </div>
            <p class="text-sm font-semibold text-white line-clamp-2 mb-2" x-text="$store.postNotif.latestPost?.title"></p>
            <div class="flex items-center space-x-3">
                <a
                    :href="$store.postNotif.latestPost?.url"
                    @click="$store.postNotif.markAsRead($store.postNotif.latestPost.id)"
                    class="inline-flex items-center space-x-1 text-xs font-mono text-cyan-400 hover:text-cyan-300 transition-colors"
                >
                    <span>Read Article</span>
                    <span>→</span>
                </a>
                <span class="text-[11px] font-mono text-slate-500" x-text="$store.postNotif.latestPost?.published_human"></span>
            </div>
        </div>
        <button
            type="button"
            @click="$store.postNotif.dismissToast()"
            class="p-1 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800/60 transition-colors shrink-0"
            aria-label="Dismiss new post notification"
        >
            <i data-lucide="x" class="w-4 h-4" aria-hidden="true"></i>
        </button>
    </div>
</div>
