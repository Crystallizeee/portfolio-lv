<!-- Post Notifications Bell -->
<div class="relative" x-data @click.away="$store.postNotif.dropdownOpen = false">
    <button
        type="button"
        @click="$store.postNotif.dropdownOpen = !$store.postNotif.dropdownOpen"
        class="relative p-2 rounded-lg text-slate-400 hover:text-cyan-400 hover:bg-slate-800/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-cyan-400 transition-colors"
        aria-label="New post notifications"
        :aria-expanded="$store.postNotif.dropdownOpen.toString()"
    >
        <i data-lucide="bell" class="w-5 h-5" aria-hidden="true"></i>
        <span
            x-show="$store.postNotif.unreadCount > 0"
            x-text="$store.postNotif.unreadCount"
            x-cloak
            class="absolute -top-0.5 -right-0.5 min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-cyan-500 text-[10px] font-mono font-bold text-slate-950 flex items-center justify-center shadow-[0_0_8px_rgba(34,211,238,0.6)]"
        ></span>
    </button>

    <!-- Notifications Dropdown -->
    <div
        x-show="$store.postNotif.dropdownOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        x-cloak
        class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] glass-card border border-slate-700/70 rounded-xl shadow-2xl overflow-hidden z-50 bg-slate-900/95 backdrop-blur-xl"
    >
        <div class="px-4 py-3 border-b border-slate-700/50 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-xs font-mono uppercase tracking-wider text-slate-200 font-semibold">Latest Posts</span>
            </div>
            <button
                type="button"
                x-show="$store.postNotif.unreadCount > 0"
                @click="$store.postNotif.markAllRead()"
                class="text-[11px] font-mono text-cyan-400 hover:text-cyan-300 transition-colors"
            >
                Mark all read
            </button>
        </div>

        <div class="max-h-80 overflow-y-auto divide-y divide-slate-800/70">
            <template x-if="$store.postNotif.posts.length === 0">
                <div class="px-4 py-6 text-center text-xs text-slate-500 font-mono">
                    No published posts yet.
                </div>
            </template>

            <template x-for="post in $store.postNotif.posts" :key="post.id">
                <a
                    :href="post.url"
                    @click="$store.postNotif.markAsRead(post.id)"
                    class="block px-4 py-3 hover:bg-slate-800/60 transition-colors group"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20" x-text="post.category"></span>
                                <span class="text-[10px] font-mono text-slate-500" x-text="post.published_human"></span>
                            </div>
                            <p class="text-sm font-medium text-slate-200 group-hover:text-cyan-400 transition-colors line-clamp-2" x-text="post.title"></p>
                        </div>
                        <span
                            x-show="$store.postNotif.isUnread(post.id)"
                            class="mt-1.5 w-2 h-2 rounded-full bg-cyan-400 shrink-0 shadow-[0_0_6px_rgba(34,211,238,0.8)]"
                            title="Unread"
                        ></span>
                    </div>
                </a>
            </template>
        </div>

        <div
            x-show="$store.postNotif.pushSupported"
            class="px-4 py-2.5 border-t border-slate-700/50 bg-slate-900/60 flex items-center justify-between"
        >
            <span class="text-[11px] font-mono text-slate-400">Push ke HP / Browser</span>
            <button
                type="button"
                @click="$store.postNotif.togglePush()"
                :disabled="$store.postNotif.pushBusy"
                class="px-2.5 py-1 rounded-md text-[11px] font-mono transition-colors disabled:opacity-50"
                :class="$store.postNotif.pushSubscribed
                    ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 hover:bg-rose-500/15 hover:text-rose-400 hover:border-rose-500/30'
                    : 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30 hover:bg-cyan-500/25'"
                x-text="$store.postNotif.pushBusy ? '...' : ($store.postNotif.pushSubscribed ? 'Active ✓' : 'Enable')"
            ></button>
        </div>

        <a
            href="{{ route('blog.index') }}"
            class="block px-4 py-2.5 text-center text-xs font-mono text-cyan-400 hover:text-cyan-300 bg-slate-900/80 border-t border-slate-700/50 transition-colors"
        >
            View all posts →
        </a>
    </div>
</div>
