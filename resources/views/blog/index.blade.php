<x-layouts.app>
    <div class="pt-40 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="max-w-2xl mx-auto text-center mb-16">
                <div class="terminal-text font-mono text-sm text-cyan-400 mb-2">~/blog</div>
                <h1 class="text-4xl font-bold text-white mb-4">Thoughts & Tutorials</h1>
                <p class="text-slate-400 leading-relaxed">
                    Sharing my journey in Cybersecurity, Laravel, and everything in between.
                </p>
            </div>

            <!-- Posts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @forelse ($posts as $post)
                    <article class="glass-card group hover:scale-[1.02] transition-all duration-300 flex flex-col h-full rounded-2xl overflow-hidden border border-slate-700/50 hover:border-cyan-500/30">
                        <!-- Image -->
                        @if ($post->featured_image)
                            <div class="aspect-video w-full overflow-hidden bg-slate-800">
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="aspect-video w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center p-6">
                                <div class="w-16 h-16 rounded-2xl bg-slate-800 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="file-text" class="w-8 h-8 text-cyan-500/50"></i>
                                </div>
                            </div>
                        @endif

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center space-x-2 text-xs font-mono text-slate-500 mb-3">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                <span>•</span>
                                <span>{{ $post->user->name ?? 'Admin' }}</span>
                            </div>
                            
                            <h2 class="text-xl font-bold text-white mb-3 group-hover:text-cyan-400 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}" class="focus:outline-none">
                                    <span class="absolute inset-0"></span>
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-slate-400 text-sm line-clamp-3 mb-4 flex-1">
                                {{ $post->excerpt ?? Str::limit(strip_tags(Str::markdown($post->content)), 120) }}
                            </p>

                            <div class="flex items-center text-cyan-400 text-sm font-medium">
                                <span>Read Article</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-3xl bg-slate-800/50 flex items-center justify-center">
                            <i data-lucide="coffee" class="w-10 h-10 text-slate-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No posts yet</h3>
                        <p class="text-slate-400">Check back later for updates.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
