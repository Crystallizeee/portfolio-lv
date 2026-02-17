<x-layouts.app>
    <div class="pt-32 pb-20 relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-20 left-1/4 w-96 h-96 bg-cyan-500/5 blur-[150px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/5 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <div class="max-w-2xl mx-auto text-center mb-16">
                <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-mono mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span>~/blog</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-5 leading-tight">
                    Thoughts & <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Tutorials</span>
                </h1>
                <p class="text-slate-400 text-lg leading-relaxed">
                    Sharing my journey in Cybersecurity, Laravel, and everything in between.
                </p>
            </div>

            <!-- Featured Post (First Post) -->
            @if ($posts->count() > 0)
                @php $featured = $posts->first(); @endphp
                <a href="{{ route('blog.show', $featured->slug) }}" class="group block mb-16">
                    <article class="relative rounded-2xl overflow-hidden border border-slate-700/50 hover:border-cyan-500/30 transition-all duration-500 bg-gradient-to-br from-slate-900/80 to-slate-800/40 backdrop-blur-sm">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <!-- Featured Image -->
                            <div class="aspect-video lg:aspect-auto lg:h-full overflow-hidden bg-slate-800 relative">
                                @if ($featured->featured_image)
                                    <img src="{{ $featured->featured_image }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full min-h-[280px] bg-gradient-to-br from-slate-800 via-slate-900 to-cyan-950 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="w-20 h-20 mx-auto rounded-2xl bg-cyan-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                                <i data-lucide="shield-check" class="w-10 h-10 text-cyan-500/60"></i>
                                            </div>
                                            <div class="text-slate-600 font-mono text-xs">FEATURED</div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Featured Badge -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full bg-cyan-500/20 backdrop-blur-md border border-cyan-500/30 text-cyan-300 text-xs font-semibold tracking-wide uppercase">
                                        Featured
                                    </span>
                                </div>
                            </div>

                            <!-- Featured Content -->
                            <div class="p-8 lg:p-10 flex flex-col justify-center">
                                <div class="flex items-center space-x-3 text-xs font-mono text-slate-500 mb-4">
                                    <div class="flex items-center space-x-1.5">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                        <span>{{ $featured->published_at ? $featured->published_at->format('M d, Y') : 'Draft' }}</span>
                                    </div>
                                    <span class="text-slate-700">•</span>
                                    <div class="flex items-center space-x-1.5">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        <span>{{ $featured->user->name ?? 'Admin' }}</span>
                                    </div>
                                </div>

                                <h2 class="text-2xl lg:text-3xl font-bold text-white mb-4 leading-tight group-hover:text-cyan-400 transition-colors duration-300">
                                    {{ $featured->title }}
                                </h2>

                                <p class="text-slate-400 leading-relaxed mb-6 line-clamp-3">
                                    {{ $featured->excerpt ?? Str::limit(strip_tags(Str::markdown($featured->content)), 200) }}
                                </p>

                                <div class="flex items-center text-cyan-400 text-sm font-semibold group-hover:text-cyan-300 transition-colors">
                                    <span>Read Full Article</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform duration-300"></i>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            @endif

            <!-- Rest of Posts -->
            @if ($posts->count() > 1)
                <div class="mb-10">
                    <h2 class="text-lg font-semibold text-white flex items-center space-x-3">
                        <span class="w-8 h-[2px] bg-gradient-to-r from-cyan-500 to-transparent"></span>
                        <span>All Articles</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    @foreach ($posts->skip(1) as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                            <article class="relative h-full rounded-xl overflow-hidden border border-slate-800/80 hover:border-cyan-500/30 transition-all duration-300 bg-slate-900/50 backdrop-blur-sm hover:bg-slate-800/50">
                                <div class="flex flex-col sm:flex-row h-full">
                                    <!-- Thumbnail -->
                                    <div class="sm:w-48 sm:min-h-full flex-shrink-0 overflow-hidden bg-slate-800">
                                        @if ($post->featured_image)
                                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-40 sm:h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                                        @else
                                            <div class="w-full h-40 sm:h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                                <div class="w-12 h-12 rounded-xl bg-slate-700/50 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                                    <i data-lucide="file-text" class="w-6 h-6 text-cyan-500/40"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5 flex-1 flex flex-col justify-center">
                                        <div class="flex items-center space-x-2 text-xs font-mono text-slate-500 mb-2.5">
                                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                        </div>

                                        <h3 class="text-base font-bold text-white mb-2 leading-snug group-hover:text-cyan-400 transition-colors duration-300 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>

                                        <p class="text-slate-500 text-sm line-clamp-2 mb-3">
                                            {{ $post->excerpt ?? Str::limit(strip_tags(Str::markdown($post->content)), 100) }}
                                        </p>

                                        <div class="flex items-center text-cyan-400/70 text-xs font-medium group-hover:text-cyan-400 transition-colors">
                                            <span>Read More</span>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-1 group-hover:translate-x-1 transition-transform duration-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Empty State -->
            @if ($posts->count() === 0)
                <div class="text-center py-24">
                    <div class="w-24 h-24 mx-auto mb-8 rounded-3xl bg-slate-800/50 border border-slate-700/50 flex items-center justify-center">
                        <i data-lucide="pen-tool" class="w-12 h-12 text-slate-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No posts yet</h3>
                    <p class="text-slate-400 max-w-sm mx-auto">Articles on cybersecurity, development, and tech are coming soon. Stay tuned.</p>
                </div>
            @endif

            <!-- Pagination -->
            @if ($posts->hasPages())
                <div class="flex justify-center pt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
