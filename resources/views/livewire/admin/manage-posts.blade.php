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

    <!-- Custom WYSIWYG Editor Styles -->
    <style>
        /* Editor Container */
        .wysiwyg-wrapper {
            border: 1px solid #1e293b;
            border-radius: 0.75rem;
            overflow: hidden;
            background: #020617;
        }
        .wysiwyg-wrapper:focus-within {
            border-color: #a855f7;
        }

        /* Toolbar */
        .wysiwyg-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
            padding: 0.5rem 0.75rem;
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            align-items: center;
        }
        .wysiwyg-toolbar .tb-sep {
            width: 1px;
            height: 24px;
            background: #334155;
            margin: 0 6px;
        }
        .wysiwyg-toolbar button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            color: #94a3b8;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.15s;
        }
        .wysiwyg-toolbar button:hover {
            background: #1e293b;
            color: #22d3ee;
        }
        .wysiwyg-toolbar button.active {
            background: #1e293b;
            color: #22d3ee;
        }
        .wysiwyg-toolbar select {
            background: #0f172a;
            color: #94a3b8;
            border: 1px solid #334155;
            border-radius: 0.375rem;
            padding: 4px 8px;
            font-size: 13px;
            cursor: pointer;
            outline: none;
            height: 32px;
        }
        .wysiwyg-toolbar select:hover {
            border-color: #22d3ee;
            color: #22d3ee;
        }

        /* Editable Area */
        .wysiwyg-editable {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
            padding: 1.5rem;
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.8;
            outline: none;
        }
        .wysiwyg-editable:empty::before {
            content: attr(data-placeholder);
            color: #475569;
            pointer-events: none;
        }

        /* Content Styling inside Editor */
        .wysiwyg-editable h2 { font-size: 1.5em; font-weight: 700; color: #fff; margin: 1em 0 0.5em; }
        .wysiwyg-editable h3 { font-size: 1.25em; font-weight: 600; color: #e2e8f0; margin: 0.8em 0 0.4em; }
        .wysiwyg-editable p { margin: 0.5em 0; }
        .wysiwyg-editable a { color: #22d3ee; text-decoration: underline; }
        .wysiwyg-editable b, .wysiwyg-editable strong { color: #f1f5f9; }
        .wysiwyg-editable blockquote {
            border-left: 4px solid #a855f7;
            padding: 0.75rem 1rem;
            margin: 0.75rem 0;
            background: rgba(30, 41, 59, 0.3);
            border-radius: 0 0.5rem 0.5rem 0;
            color: #94a3b8;
            font-style: italic;
        }
        .wysiwyg-editable pre {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            overflow-x: auto;
            margin: 0.75rem 0;
            border: 1px solid #334155;
        }
        .wysiwyg-editable code {
            background: #1e293b;
            color: #e2e8f0;
            padding: 0.15em 0.4em;
            border-radius: 0.25rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875em;
        }
        .wysiwyg-editable ul, .wysiwyg-editable ol {
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }
        .wysiwyg-editable li { margin: 0.25rem 0; }
        .wysiwyg-editable img {
            max-width: 100%;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }
        .wysiwyg-editable hr {
            border: none;
            border-top: 1px solid #334155;
            margin: 1.5rem 0;
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
                class="fixed inset-0 bg-black/50 backdrop-blur-md"
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

                            <!-- Status & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Status</label>
                                    <select 
                                        wire:model="status" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all appearance-none"
                                    >
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                    @error('status') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Published At -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Published At <span class="text-slate-500 text-xs">(Optional)</span></label>
                                    <input 
                                        wire:model="published_at"
                                        type="datetime-local" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all scheme-dark"
                                    >
                                    @error('published_at') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
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

                            <!-- Custom WYSIWYG Editor -->
                            <div class="space-y-2" wire:ignore>
                                <label class="text-sm font-medium text-slate-300">Content</label>
                                <div x-data="{
                                    exec(cmd, val = null) {
                                        document.execCommand(cmd, false, val);
                                        this.$refs.editable.focus();
                                        this.sync();
                                    },
                                    formatBlock(tag) {
                                        document.execCommand('formatBlock', false, tag);
                                        this.$refs.editable.focus();
                                        this.sync();
                                    },
                                    insertLink() {
                                        const url = prompt('Enter URL:', 'https://');
                                        if (url) {
                                            document.execCommand('createLink', false, url);
                                            this.$refs.editable.focus();
                                            this.sync();
                                        }
                                    },
                                    insertCode() {
                                        const sel = window.getSelection();
                                        if (sel.rangeCount) {
                                            const range = sel.getRangeAt(0);
                                            const code = document.createElement('code');
                                            range.surroundContents(code);
                                            sel.removeAllRanges();
                                            this.sync();
                                        }
                                    },
                                    insertCodeBlock() {
                                        const code = prompt('Enter code:');
                                        if (code) {
                                            document.execCommand('insertHTML', false, '<pre><code>' + code.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</code></pre><p><br></p>');
                                            this.sync();
                                        }
                                    },
                                    insertHR() {
                                        document.execCommand('insertHorizontalRule');
                                        this.$refs.editable.focus();
                                        this.sync();
                                    },
                                    sync() {
                                        @this.set('content', this.$refs.editable.innerHTML);
                                    },
                                    init() {
                                        this.$refs.editable.innerHTML = this.$refs.payload.value;
                                    }
                                }">
                                    <!-- Hidden payload for safe content transfer -->
                                    <textarea x-ref="payload" style="display:none">{{ $content }}</textarea>
                                    
                                    <div class="wysiwyg-wrapper">
                                        <!-- Toolbar -->
                                        <div class="wysiwyg-toolbar">
                                            <!-- Heading Dropdown -->
                                            <select @change="formatBlock($event.target.value); $event.target.value='';">
                                                <option value="">Heading</option>
                                                <option value="p">Paragraph</option>
                                                <option value="h2">Heading 2</option>
                                                <option value="h3">Heading 3</option>
                                            </select>
                                            <div class="tb-sep"></div>

                                            <!-- Text Formatting -->
                                            <button type="button" @click="exec('bold')" title="Bold"><b>B</b></button>
                                            <button type="button" @click="exec('italic')" title="Italic"><i>I</i></button>
                                            <button type="button" @click="exec('underline')" title="Underline"><u>U</u></button>
                                            <button type="button" @click="exec('strikeThrough')" title="Strikethrough"><s>S</s></button>
                                            <div class="tb-sep"></div>

                                            <!-- Lists -->
                                            <button type="button" @click="exec('insertUnorderedList')" title="Bullet List">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                                            </button>
                                            <button type="button" @click="exec('insertOrderedList')" title="Numbered List">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
                                            </button>
                                            <div class="tb-sep"></div>

                                            <!-- Block Elements -->
                                            <button type="button" @click="formatBlock('blockquote')" title="Quote">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/></svg>
                                            </button>
                                            <button type="button" @click="insertCode()" title="Inline Code">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                                            </button>
                                            <button type="button" @click="insertCodeBlock()" title="Code Block">{ }</button>
                                            <div class="tb-sep"></div>

                                            <!-- Insert -->
                                            <button type="button" @click="insertLink()" title="Insert Link">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                            </button>
                                            <button type="button" @click="insertHR()" title="Horizontal Rule">―</button>
                                            <div class="tb-sep"></div>

                                            <!-- Undo/Redo -->
                                            <button type="button" @click="exec('undo')" title="Undo">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                            </button>
                                            <button type="button" @click="exec('redo')" title="Redo">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                            </button>
                                        </div>

                                        <!-- Editable Content Area -->
                                        <div 
                                            x-ref="editable"
                                            contenteditable="true"
                                            class="wysiwyg-editable"
                                            data-placeholder="Write your content here..."
                                            @input="sync()"
                                            @paste.prevent="
                                                const text = $event.clipboardData.getData('text/html') || $event.clipboardData.getData('text/plain');
                                                document.execCommand('insertHTML', false, text);
                                                sync();
                                            "
                                        ></div>
                                    </div>
                                </div>
                            </div>
                            @error('content') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-800 mt-6">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-5 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all duration-200"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl text-white font-medium hover:from-purple-400 hover:to-pink-400 transition-all duration-200 shadow-lg shadow-purple-500/25 flex items-center space-x-2"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-wait"
                                >
                                    <span wire:loading.remove class="flex items-center space-x-2">
                                        <i data-lucide="{{ $isEditing ? 'check' : 'send' }}" class="w-4 h-4"></i>
                                        <span>{{ $isEditing ? 'Update Post' : 'Publish Post' }}</span>
                                    </span>
                                    <span wire:loading class="flex items-center space-x-2">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Saving...</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    <div 
        x-show="showDeleteModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center"
    >
        <div class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="showDeleteModal = false"></div>
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
