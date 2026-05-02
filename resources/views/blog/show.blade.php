<x-layouts.app 
    :title="$post->title" 
    :description="$post->excerpt ?? Str::limit(strip_tags(Str::markdown($post->content)), 160)"
    :og_image="$post->featured_image ? $post->featured_image : route('og-image', ['type' => 'post', 'slug' => $post->slug])"
    og_type="article"
>
    @include('partials.jsonld-blogposting', ['post' => $post])

    <!-- Progress Bar -->
    <div x-data="{ width: '0%' }" x-on:scroll.window="width = ((window.scrollY) / (document.body.scrollHeight - window.innerHeight) * 100) + '%'" class="fixed top-0 left-0 h-1 bg-cyan-500 z-[60]" :style="`width: ${width}`"></div>

    <article class="pt-32 pb-24 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-cyan-500/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-purple-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <header class="mb-12 text-center">
                    <div class="flex flex-wrap items-center gap-4 text-sm font-mono text-slate-400 mb-8 border-b border-slate-700/50 pb-8">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-cyan-500"></i>
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                        </div>
                        <span class="text-slate-700">•</span>
                        <div class="flex items-center space-x-2">
                            <i data-lucide="user" class="w-4 h-4 text-cyan-500"></i>
                            <span>{{ $post->user->name ?? 'Admin' }}</span>
                        </div>
                        <span class="text-slate-700">•</span>
                        <div class="flex items-center">
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $post->category === 'Tech' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                {{ $post->category }}
                            </span>
                        </div>
                    </div>              
                
                <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight mb-8">
                    {{ $post->title }}
                </h1>
                
                @if ($post->featured_image)
                    <div class="rounded-2xl overflow-hidden border border-slate-700/50 shadow-2xl shadow-cyan-500/10 mb-12">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-auto" loading="lazy" decoding="async">
                    </div>
                @endif
            </header>

            <!-- Content -->
            <style>
                /* Custom Alignment Tags */
                align-left { text-align: left; display: block; }
                align-center { text-align: center; display: block; }
                align-right { text-align: right; display: block; }
                align-justify { text-align: justify; display: block; }
                
                .prose align-center figure, .prose align-center img { margin-left: auto !important; margin-right: auto !important; }
                .prose align-right figure, .prose align-right img { margin-left: auto !important; margin-right: 0 !important; }
                .prose align-center figcaption { text-align: center; }
                .prose align-right figcaption { text-align: right; }
            </style>
            <div class="prose prose-invert prose-lg max-w-none break-words prose-headings:text-white prose-p:text-slate-300 prose-a:text-cyan-400 prose-a:no-underline hover:prose-a:underline prose-code:text-cyan-300 prose-code:bg-slate-800/50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-pre:bg-[#0d1117] prose-pre:border prose-pre:border-slate-800 prose-blockquote:border-l-cyan-500 prose-blockquote:bg-slate-800/20 prose-blockquote:py-2 prose-blockquote:px-6 prose-img:rounded-xl">
                {!! Purifier::clean($post->content) !!}
            </div>

            <!-- Footer -->
            <footer class="mt-16 pt-8 border-t border-slate-800 flex items-center justify-between">
                <a href="{{ route('blog.index') }}" class="flex items-center space-x-2 text-slate-400 hover:text-white transition-colors group">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    <span>Back to Blog</span>
                </a>
                
                <div class="flex items-center space-x-4">
                    @livewire('post-like-button', ['post' => $post])
                    
                    <div x-data="{ copied: false }">
                        <button 
                            @click="
                                navigator.clipboard.writeText(window.location.href);
                                copied = true;
                                setTimeout(() => copied = false, 2000);
                            "
                            class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:text-cyan-400 transition-colors" 
                            title="Salin Tautan">
                            
                            <div x-show="!copied"><i data-lucide="link" class="w-4 h-4"></i></div>
                            <div x-cloak x-show="copied"><i data-lucide="check" class="w-4 h-4 text-green-400"></i></div>
                        </button>
                    </div>
                </div>
            </footer>

            <!-- Comments Section -->
            @livewire('post-comments', ['post' => $post])
        </div>
    </article>
</x-layouts.app>
