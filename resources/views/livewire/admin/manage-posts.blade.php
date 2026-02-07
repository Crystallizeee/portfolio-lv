<div x-data="{ showDeleteModal: @entangle('showDeleteModal'), deleteId: @entangle('deleteId') }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="terminal-text font-mono text-sm mb-1">
                <span class="text-slate-500">$</span> ls -la /posts
            </div>
            <p class="text-slate-400">Manage your blog posts and articles</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/50 rounded-xl text-purple-400 hover:from-purple-500/30 hover:to-pink-500/30 hover:border-purple-400 transition-all duration-300 shadow-lg shadow-purple-500/10"
        >
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span class="font-medium">New Post</span>
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/50 rounded-xl text-green-400 flex items-center space-x-3 animate-pulse">
            <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Posts Table -->
    <div class="glass-card overflow-hidden shadow-xl shadow-black/20">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-slate-800/80 to-slate-700/80">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Published At</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse ($posts as $post)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <div class="text-white font-semibold group-hover:text-purple-400 transition-colors">{{ $post->title }}</div>
                            <div class="text-slate-500 text-sm mt-1 truncate max-w-xs">/{{ $post->slug }}</div>
                        </td>
                        <td class="px-6 py-5">
                            @if ($post->status === 'published')
                                <span class="flex items-center text-green-400 text-sm font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-400 mr-2 status-dot shadow-lg shadow-green-500/50"></span>
                                    Published
                                </span>
                            @else
                                <span class="flex items-center text-yellow-400 text-sm font-medium">
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 mr-2"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-slate-400 text-sm">
                            {{ $post->published_at ? $post->published_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button 
                                    wire:click="openEditModal({{ $post->id }})"
                                    class="px-3 py-1.5 text-xs text-purple-400 hover:bg-purple-500/20 rounded-lg transition-all duration-200 border border-purple-500/30"
                                >
                                    Edit
                                </button>
                                <button 
                                    @click="deleteId = {{ $post->id }}; showDeleteModal = true"
                                    class="px-3 py-1.5 text-xs text-red-400 hover:bg-red-500/20 rounded-lg transition-all duration-200 border border-red-500/30"
                                >
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-500">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-700/50 flex items-center justify-center">
                                <i data-lucide="file-text" class="w-8 h-8 opacity-50"></i>
                            </div>
                            <p class="text-lg font-medium mb-1">No posts found</p>
                            <p class="text-sm">Click "New Post" to start writing.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-700/50">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Styles for EasyMDE -->
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <style>
        /* EasyMDE & CodeMirror Customization */
        .editor-toolbar {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #94a3b8 !important;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 0.75rem 1rem !important;
            opacity: 1 !important;
            z-index: 50 !important;
        }
        .editor-toolbar i {
            color: #94a3b8 !important;
            transition: all 0.2s;
        }
        .editor-toolbar button:hover i {
            color: #22d3ee !important;
        }
        .editor-toolbar button.active, .editor-toolbar button:hover {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 0.375rem !important;
        }
        
        .CodeMirror {
            background-color: #020617 !important;
            border-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-radius: 0 0 0.75rem 0.75rem !important;
            padding: 1.5rem !important;
            font-family: 'JetBrains Mono', monospace !important;
            font-size: 0.95rem !important;
            line-height: 1.7 !important;
            min-height: 400px !important;
            z-index: 0 !important;
        }
        .CodeMirror-selected {
            background-color: #1e293b !important;
        }
        .CodeMirror-cursor {
            border-left: 2px solid #a855f7 !important;
        }
        
        /* Preview Styling */
        .editor-preview {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            padding: 2rem !important;
            line-height: 1.8 !important;
        }
        .editor-preview h1, .editor-preview h2, .editor-preview h3 {
            color: #fff !important;
            font-weight: 700 !important;
            margin-top: 1.5em !important;
            margin-bottom: 0.5em !important;
            border-bottom: 1px solid #1e293b !important;
            padding-bottom: 0.5em !important;
        }
        .editor-preview h1 { font-size: 2em !important; }
        .editor-preview h2 { font-size: 1.5em !important; color: #e2e8f0 !important; }
        .editor-preview h3 { font-size: 1.25em !important; }
        
        .editor-preview a {
            color: #22d3ee !important;
            text-decoration: none !important;
        }
        .editor-preview pre {
            background-color: #1e293b !important;
            padding: 1rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #334155 !important;
        }
        .editor-preview code {
            background-color: #1e293b !important;
            padding: 0.2rem 0.4rem !important;
            border-radius: 0.25rem !important;
            color: #e2e8f0 !important;
            font-family: 'JetBrains Mono', monospace !important;
        }
        .editor-preview blockquote {
            border-left: 4px solid #a855f7 !important;
            padding-left: 1rem !important;
            margin-left: 0 !important;
            color: #94a3b8 !important;
            background-color: #1e293b33 !important; /* low opacity bg */
            padding: 1rem !important;
            border-radius: 0 0.5rem 0.5rem 0 !important;
        }
        
        .editor-statusbar {
            display: none !important;
        }
        .EasyMDEContainer {
            z-index: 1 !important;
        }
    </style>

    <!-- Modal -->
    @if ($showModal)
        <div 
            class="fixed inset-0 z-[60] flex items-start justify-center p-4 pt-24"
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 10)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <!-- Backdrop -->
            <div 
                class="fixed inset-0 bg-black/95 backdrop-blur-sm"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                wire:click="closeModal"
            ></div>
            
            <!-- Modal Content -->
            <div 
                class="relative w-full max-w-7xl max-h-[90vh] overflow-y-auto"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            >
                <div class="bg-slate-900 border border-slate-700 shadow-2xl shadow-purple-500/10 overflow-hidden rounded-xl">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 border-b border-slate-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="font-mono text-sm text-slate-300">
                                    {{ $isEditing ? '~/edit-post.md' : '~/new-post.md' }}
                                </span>
                            </div>
                            <button 
                                wire:click="closeModal" 
                                class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-700 transition-all duration-200"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <form wire:submit="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Title</label>
                                    <input 
                                        wire:model.live="title"
                                        type="text" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all"
                                        placeholder="Post title..."
                                    >
                                    @error('title') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Slug -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Slug</label>
                                    <input 
                                        wire:model="slug"
                                        type="text" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-slate-300 focus:outline-none focus:border-purple-500 transition-all font-mono text-sm"
                                        placeholder="post-slug"
                                    >
                                    @error('slug') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300">Featured Image</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-700 border-dashed rounded-lg cursor-pointer bg-slate-950 hover:bg-slate-900 transition-colors relative overflow-hidden group">
                                        
                                        @if ($new_featured_image)
                                            <img src="{{ $new_featured_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-40 transition-opacity">
                                            <div class="relative z-10 flex flex-col items-center justify-center pt-5 pb-6">
                                                <i data-lucide="image" class="w-8 h-8 text-purple-500 mb-2 shadow-lg"></i>
                                                <p class="text-sm text-slate-200 font-medium">New image selected</p>
                                            </div>
                                        @elseif ($featured_image)
                                            <img src="{{ $featured_image }}" class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-40 transition-opacity">
                                            <div class="relative z-10 flex flex-col items-center justify-center pt-5 pb-6">
                                                <p class="text-sm text-slate-200 font-medium bg-black/50 px-3 py-1 rounded">Click to replace current image</p>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i data-lucide="upload-cloud" class="w-8 h-8 mb-3 text-slate-400"></i>
                                                <p class="text-sm text-slate-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-slate-500">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                                            </div>
                                        @endif

                                        <input id="dropzone-file" wire:model="new_featured_image" type="file" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                                <div wire:loading wire:target="new_featured_image" class="text-xs text-purple-400 animate-pulse">Uploading...</div>
                                @error('new_featured_image') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Excerpt -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-300">Excerpt (Optional)</label>
                                <textarea 
                                    wire:model="excerpt"
                                    rows="2"
                                    class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all"
                                    placeholder="Brief summary..."
                                ></textarea>
                                @error('excerpt') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Content Editor Section -->
                            <div class="space-y-4" x-data="{ mode: 'write' }">
                                <div class="flex items-center justify-between">
                                    <label class="text-sm font-medium text-slate-300">Content</label>
                                    
                                    <!-- Editor Mode Toggle -->
                                    <div class="flex bg-slate-950 p-1 rounded-lg border border-slate-700/50">
                                        <button 
                                            type="button"
                                            @click="mode = 'write'"
                                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all"
                                            :class="mode === 'write' ? 'bg-slate-800 text-white shadow-sm' : 'text-slate-400 hover:text-slate-300'"
                                        >
                                            <div class="flex items-center space-x-1.5">
                                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                                <span>Write</span>
                                            </div>
                                        </button>
                                        <button 
                                            type="button"
                                            @click="mode = 'preview'"
                                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all"
                                            :class="mode === 'preview' ? 'bg-cyan-900/30 text-cyan-400 shadow-sm' : 'text-slate-400 hover:text-slate-300'"
                                        >
                                            <div class="flex items-center space-x-1.5">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                <span>Preview</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Write Mode -->
                                <div x-show="mode === 'write'" class="relative group">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500/20 to-cyan-500/20 rounded-xl blur opacity-0 group-focus-within:opacity-100 transition duration-500"></div>
                                    <textarea 
                                        wire:model="content"
                                        rows="20"
                                        class="relative w-full px-5 py-4 bg-slate-950/80 backdrop-blur-sm border border-slate-700 rounded-xl text-slate-200 placeholder-slate-600 focus:outline-none focus:border-slate-600 focus:bg-slate-950 transition-all font-mono text-sm leading-relaxed"
                                        placeholder="Write your masterpiece here... (Markdown supported)"
                                    ></textarea>
                                    
                                    <!-- Helper Hint -->
                                    <div class="absolute bottom-4 right-4 text-xs text-slate-500 font-mono flex items-center space-x-2 pointer-events-none">
                                        <span>Markdown Supported</span>
                                        <i data-lucide="markdown" class="w-4 h-4 opacity-50"></i>
                                    </div>
                                </div>

                                <!-- Preview Mode -->
                                <div x-show="mode === 'preview'" class="relative min-h-[500px] border border-slate-700/50 rounded-xl bg-slate-950 p-8 overflow-y-auto max-h-[600px]">
                                    @if($content)
                                        <div class="prose prose-invert prose-lg max-w-none break-all prose-headings:text-white prose-p:text-slate-300 prose-a:text-cyan-400 prose-a:no-underline hover:prose-a:underline prose-code:text-cyan-300 prose-code:bg-slate-800/50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-pre:bg-[#0d1117] prose-pre:border prose-pre:border-slate-800 prose-blockquote:border-l-cyan-500 prose-blockquote:bg-slate-800/20 prose-blockquote:py-2 prose-blockquote:px-6 prose-img:rounded-xl">
                                            {!! Str::markdown($content) !!}
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-slate-500">
                                            <i data-lucide="eye-off" class="w-12 h-12 mb-3 opacity-20"></i>
                                            <p>Nothing to preview yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error('content') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
    @endif

    <!-- Delete Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div class="fixed inset-0 bg-black/80" @click="showDeleteModal = false"></div>
        <div class="relative bg-slate-800 rounded-lg p-6 border border-red-500/30 shadow-2xl max-w-sm w-full mx-4">
            <h3 class="text-lg font-bold text-white mb-2">Delete Post?</h3>
            <p class="text-slate-400 text-sm mb-6">Are you sure you want to delete this post? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button 
                    @click="showDeleteModal = false" 
                    class="px-4 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-700 rounded-lg transition-all"
                >
                    Cancel
                </button>
                <button 
                    @click="$wire.delete(deleteId); showDeleteModal = false" 
                    class="px-4 py-2 text-sm bg-red-600 hover:bg-red-500 text-white rounded-lg transition-all shadow-lg shadow-red-600/20"
                >
                    Delete Post
                </button>
            </div>
        </div>
    </div>
</div>
