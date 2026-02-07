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
        .editor-toolbar {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #fff !important;
        }
        .editor-toolbar i {
            color: #94a3b8 !important;
        }
        .editor-toolbar i:hover {
            color: #fff !important;
            background-color: #334155 !important;
        }
        .CodeMirror {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        .CodeMirror-cursor {
            border-left: 1px solid #a855f7 !important;
        }
        .editor-preview {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
        }
        .editor-statusbar {
            color: #64748b !important;
        }
    </style>

    <!-- Modal -->
    @if ($showModal)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
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
                class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto"
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

                            <!-- Content -->
                            <div class="space-y-2" wire:ignore>
                                <label class="text-sm font-medium text-slate-300">Content (Markdown)</label>
                                <textarea id="markdown-editor" wire:model="content"></textarea>
                            </div>
                            @error('content') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Status</label>
                                    <select 
                                        wire:model="status"
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all"
                                    >
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                    @error('status') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Published At -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Published Date</label>
                                    <input 
                                        wire:model="published_at"
                                        type="datetime-local" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all"
                                    >
                                    @error('published_at') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-slate-800">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-all"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    class="px-6 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-medium transition-all shadow-lg shadow-purple-600/20"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.remove>{{ $isEditing ? 'Update Post' : 'Create Post' }}</span>
                                    <span wire:loading>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EasyMDE Script -->
            <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const editorElement = document.getElementById('markdown-editor');
                    
                    if (editorElement) {
                        const easyMDE = new EasyMDE({
                            element: editorElement,
                            initialValue: @this.content,
                            spellChecker: false,
                            status: false,
                            toolbar: ["bold", "italic", "heading", "|", "quote", "code", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "side-by-side", "fullscreen"],
                        });

                        easyMDE.codemirror.on('change', () => {
                            @this.set('content', easyMDE.value());
                        });

                        Livewire.on('refresh-markdown', ({ content }) => {
                            // Only update if value is different to prevent cursor jumps if we were typing, 
                            // though in this case it's mostly for modal open/reset
                            if (easyMDE.value() !== content) {
                                easyMDE.value(content);
                            }
                            // Refresh layout to fix any rendering issues in modal
                            setTimeout(() => {
                                easyMDE.codemirror.refresh();
                            }, 100);
                        });
                    }
                });
            </script>
        </div>
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
