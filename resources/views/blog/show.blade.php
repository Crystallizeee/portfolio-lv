<x-layouts.app 
    :title="$post->title" 
    :description="$post->excerpt ?? Str::limit(strip_tags(Str::markdown($post->content)), 160)"
    :og_image="$post->featured_image"
>
    <!-- Progress Bar -->
    <div x-data="{ width: '0%' }" x-on:scroll.window="width = ((window.scrollY) / (document.body.scrollHeight - window.innerHeight) * 100) + '%'" class="fixed top-0 left-0 h-1 bg-cyan-500 z-[60]" :style="`width: ${width}`"></div>

    <article class="pt-32 pb-24 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-cyan-500/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-purple-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header -->
            <header class="mb-12 text-center">
                <div class="flex items-center justify-center space-x-2 text-sm font-mono text-cyan-400 mb-6">
                    <a href="{{ route('blog.index') }}" class="hover:underline">~/blog</a>
                    <span class="text-slate-600">/</span>
                    <span class="text-slate-400">{{ $post->published_at ? $post->published_at->format('Y-m-d') : 'Draft' }}</span>
                </div>
                
                <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight mb-8">
                    {{ $post->title }}
                </h1>
                
                @if ($post->featured_image)
                    <div class="rounded-2xl overflow-hidden border border-slate-700/50 shadow-2xl shadow-cyan-500/10 mb-12">
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-auto">
                    </div>
                @endif
            </header>

            <!-- Content -->
            <div class="prose prose-invert prose-lg max-w-none prose-headings:text-white prose-p:text-slate-400 prose-a:text-cyan-400 prose-a:no-underline hover:prose-a:underline prose-code:text-cyan-300 prose-code:bg-slate-800/50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-pre:bg-slate-900 prose-pre:border prose-pre:border-slate-800 prose-blockquote:border-l-cyan-500 prose-blockquote:bg-slate-800/20 prose-blockquote:py-2 prose-blockquote:px-6 prose-img:rounded-xl">
                {!! Str::markdown($post->content) !!}
            </div>

            <!-- Footer -->
            <footer class="mt-16 pt-8 border-t border-slate-800 flex items-center justify-between">
                <a href="{{ route('blog.index') }}" class="flex items-center space-x-2 text-slate-400 hover:text-white transition-colors group">
                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    <span>Back to Blog</span>
                </a>
                
                <div class="flex items-center space-x-4">
                    <button class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-twitter hover:text-white transition-colors" title="Share on Twitter">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-linkedin hover:text-white transition-colors" title="Share on LinkedIn">
                        <i data-lucide="linkedin" class="w-4 h-4"></i>
                    </button>
                </div>
            </footer>
        </div>
    </article>
</x-layouts.app>
