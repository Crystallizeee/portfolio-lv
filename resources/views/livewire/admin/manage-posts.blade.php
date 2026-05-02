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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Title / Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Comments</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Published At</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse ($posts as $post)
                    <tr class="hover:bg-slate-700/30 transition-all duration-200 group">
                        <td class="px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <div class="text-white font-semibold group-hover:text-purple-400 transition-colors">{{ $post->title }}</div>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono leading-none border {{ $post->category === 'Tech' ? 'bg-cyan-500/10 border-cyan-500/20 text-cyan-400' : 'bg-slate-700/50 border-slate-600/50 text-slate-400' }}">
                                            {{ strtoupper($post->category) }}
                                        </span>
                                        <span class="text-slate-600 text-xs font-mono">/{{ $post->slug }}</span>
                                    </div>
                                </div>
                            </div>
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
                        <td class="px-6 py-5 text-slate-300 text-sm w-32">
                            <button wire:click="openCommentsModal({{ $post->id }})" class="flex items-center space-x-1.5 px-2.5 py-1 rounded-lg hover:bg-slate-700/50 transition-colors {{ $post->comments_count > 0 ? 'text-cyan-400 font-medium' : 'text-slate-500' }}">
                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                <span>{{ $post->comments_count }}</span>
                            </button>
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

    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <script>
        // Add text alignment support to Trix Editor
        Trix.config.blockAttributes.alignLeft = {
            tagName: "align-left",
            exclusive: true,
            terminal: false,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignCenter = {
            tagName: "align-center",
            exclusive: true,
            terminal: false,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignRight = {
            tagName: "align-right",
            exclusive: true,
            terminal: false,
            breakOnReturn: true,
            group: false
        };
        Trix.config.blockAttributes.alignJustify = {
            tagName: "align-justify",
            exclusive: true,
            terminal: false,
            breakOnReturn: true,
            group: false
        };

        document.addEventListener("trix-initialize", function(event) {
            var toolbar = event.target.toolbarElement;
            if (toolbar.querySelector('.trix-button-group--alignment')) return;

            var blockTools = toolbar.querySelector(".trix-button-group--block-tools");
            if (!blockTools) return;

            var alignGroup = document.createElement("span");
            alignGroup.className = "trix-button-group trix-button-group--alignment";
            alignGroup.setAttribute("data-trix-button-group", "alignment");

            var alignments = [
                { name: 'alignLeft', icon: 'align-left', title: 'Align Left' },
                { name: 'alignCenter', icon: 'align-center', title: 'Align Center' },
                { name: 'alignRight', icon: 'align-right', title: 'Align Right' },
                { name: 'alignJustify', icon: 'align-justify', title: 'Justify' }
            ];

            alignments.forEach(function(align) {
                var btn = document.createElement("button");
                btn.type = "button";
                btn.className = "trix-button";
                btn.setAttribute("data-trix-attribute", align.name);
                btn.title = align.title;
                btn.style.textIndent = "0";
                btn.style.display = "flex";
                btn.style.alignItems = "center";
                btn.style.justifyContent = "center";
                btn.innerHTML = `<i data-lucide="${align.icon}" class="w-4 h-4 text-slate-400"></i>`;
                alignGroup.appendChild(btn);
            });

            blockTools.insertAdjacentElement("afterend", alignGroup);
            
            if (window.lucide) {
                window.lucide.createIcons({
                    root: alignGroup
                });
            }
        });
    </script>

    <!-- Custom Trix Editor Styles -->
    <style>
        /* Trix Editor Dark Theme */
        trix-toolbar {
            background: #0f172a;
            border-bottom: 1px solid #1e293b !important;
            border-radius: 0.75rem 0.75rem 0 0;
            padding: 0.5rem 0.75rem !important;
            margin-bottom: 0 !important;
        }
        trix-toolbar .trix-button-group {
            border: 1px solid #334155;
            background: #1e293b;
            margin-bottom: 0.25rem;
        }
        trix-toolbar .trix-button {
            border-bottom: none;
            color: #94a3b8;
            background: transparent;
        }
        trix-toolbar .trix-button.trix-active {
            background: #334155;
            color: #22d3ee;
        }
        trix-toolbar .trix-button::before {
            filter: invert(0.8);
        }
        trix-toolbar .trix-button.trix-active::before {
            filter: invert(0.5) sepia(1) hue-rotate(150deg) saturate(5);
        }
        trix-toolbar .trix-dialog {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        trix-toolbar .trix-dialog .trix-input {
            background: #0f172a;
            border: 1px solid #334155;
            color: #f8fafc;
        }
        
        /* Custom Alignment Tags */
        align-left { text-align: left; display: block; }
        align-center { text-align: center; display: block; }
        align-right { text-align: right; display: block; }
        align-justify { text-align: justify; display: block; }
        
        align-center figure, align-center img { margin-left: auto !important; margin-right: auto !important; }
        align-right figure, align-right img { margin-left: auto !important; margin-right: 0 !important; }
        align-center figcaption { text-align: center; }
        align-right figcaption { text-align: right; }
        
        trix-toolbar .trix-dialog input {
            background: #020617;
            border: 1px solid #334155;
            color: #fff;
            border-radius: 0.25rem;
        }
        trix-toolbar .trix-dialog .trix-button {
            background: #22d3ee;
            color: #000;
            border: none;
            border-radius: 0.25rem;
        }
        trix-toolbar .trix-button:not(:first-child) {
            border-left: 1px solid #334155;
        }
        trix-editor {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
            border: 1px solid #1e293b !important;
            border-top: none !important;
            border-radius: 0 0 0.75rem 0.75rem;
            background: #020617;
            padding: 1.5rem !important;
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.8;
            outline: none;
        }
        trix-editor:focus-within {
            border-color: #a855f7 !important;
            box-shadow: 0 0 0 1px #a855f7;
        }
        trix-editor h1 { font-size: 1.5em; font-weight: 700; color: #fff; margin: 1em 0 0.5em; }
        trix-editor a { color: #22d3ee; text-decoration: underline; }
        trix-editor blockquote {
            border-left: 4px solid #a855f7;
            padding: 0.75rem 1rem;
            margin: 0.75rem 0;
            background: rgba(30, 41, 59, 0.3);
            border-radius: 0 0.5rem 0.5rem 0;
            color: #94a3b8;
            font-style: italic;
        }
        trix-editor pre {
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
        trix-editor img {
            max-width: 100%;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }
        trix-editor figure.attachment {
            text-align: center;
        }
        trix-editor figure.attachment figcaption {
            color: #64748b;
            font-size: 0.8rem;
            margin-top: 0.25rem;
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

                            <!-- Status, Category & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                                <!-- Category -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-300">Category</label>
                                    <select 
                                        wire:model="category" 
                                        class="w-full px-4 py-2.5 bg-slate-950 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-purple-500 transition-all appearance-none"
                                    >
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
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

                            <!-- Trix WYSIWYG Editor -->
                            <div class="space-y-2" wire:ignore>
                                <label class="text-sm font-medium text-slate-300">Content</label>
                                
                                <div class="rounded-xl overflow-hidden transition-all relative" x-data="{
                                    uploadFileAttachment(attachment) {
                                        var file = attachment.file;
                                        if (!file) return;
                                        
                                        var formData = new FormData();
                                        formData.append('attachment', file);

                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', '{{ route('admin.posts.upload-image', [], false) }}', true);
                                        var csrfMeta = document.querySelector('meta[name=\'csrf-token\']');
                                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}');
                                        xhr.setRequestHeader('Accept', 'application/json');

                                        xhr.upload.onprogress = function(event) {
                                            var progress = event.loaded / event.total * 100;
                                            attachment.setUploadProgress(progress);
                                        };

                                        xhr.onload = function() {
                                            if (xhr.status >= 200 && xhr.status < 300) {
                                                try {
                                                    var response = JSON.parse(xhr.responseText);
                                                    attachment.setAttributes({
                                                        url: response.url,
                                                        href: response.url
                                                    });
                                                } catch (e) {
                                                    console.error('Invalid JSON response', xhr.responseText);
                                                    alert('Upload failed: Server returned invalid response.');
                                                    attachment.remove();
                                                }
                                            } else {
                                                console.error('Upload failed with status: ' + xhr.status);
                                                alert('Upload failed (Status ' + xhr.status + ')');
                                                attachment.remove();
                                            }
                                        };

                                        xhr.onerror = function() {
                                            alert('Network error occurred during upload.');
                                            console.error('Network error during XHR request');
                                            attachment.remove();
                                        };

                                        xhr.send(formData);
                                    }
                                }">
                                    <input id="x-content" type="hidden" name="content" wire:model="content" value="{{ $content }}">
                                    <trix-editor 
                                        input="x-content" 
                                        class="trix-content" 
                                        x-on:trix-change="document.getElementById('x-content').dispatchEvent(new Event('input'))"
                                        x-on:trix-attachment-add="uploadFileAttachment($event.attachment)"
                                    ></trix-editor>
                                </div>
                                
                                <script>
                                    // Binding and upload is now handled via AlpineJS above.
                                    
                                    // Refresh Trix content when Livewire events are dispatched
                                    window.addEventListener('refresh-markdown', event => {
                                        var editorElement = document.querySelector("trix-editor");
                                        if (editorElement) {
                                            var newContent = event.detail.content !== undefined ? event.detail.content : (event.detail[0] && event.detail[0].content !== undefined ? event.detail[0].content : '');
                                            editorElement.editor.loadHTML(newContent);
                                        }
                                    });
                                </script>
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

    <!-- Comments Management Modal -->
    @if ($showCommentsModal)
        <div 
            class="fixed inset-0 z-[60] flex items-start justify-center p-4 pt-24"
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 10)"
            x-show="show"
        >
            <div 
                class="fixed inset-0 bg-black/50 backdrop-blur-md"
                wire:click="closeCommentsModal"
            ></div>
            
            <div class="relative w-full max-w-4xl max-h-[80vh] overflow-y-auto bg-slate-900 border border-slate-700 rounded-xl shadow-2xl z-10 flex flex-col">
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 border-b border-slate-700 flex justify-between items-center sticky top-0 z-20">
                    <h3 class="text-lg font-bold text-white flex items-center space-x-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-cyan-500"></i>
                        <span>Comments on: <span class="text-cyan-400">{{ $currentPostTitle }}</span></span>
                    </h3>
                    <button wire:click="closeCommentsModal" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto" style="max-height: 60vh;">
                    @if(count($postComments) > 0)
                        <div class="space-y-4">
                            @foreach ($postComments as $comment)
                                <div class="bg-slate-800/50 border border-slate-700 rounded-lg p-5">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-cyan-400 font-bold">
                                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="text-white font-medium text-sm">{{ $comment->name }}</h4>
                                                <p class="text-xs text-slate-400">{{ $comment->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                        <button wire:click="deleteComment({{ $comment->id }})" wire:confirm="Are you sure you want to permanently delete this comment?" class="text-slate-500 hover:text-red-400 p-1.5 rounded bg-slate-800 hover:bg-slate-700 transition-colors" title="Delete Comment">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                    <div class="text-slate-300 text-sm mt-3 pl-11">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 flex flex-col items-center justify-center space-y-3">
                            <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center">
                                <i data-lucide="message-square-off" class="w-8 h-8 text-slate-500"></i>
                            </div>
                            <p class="text-slate-400">No comments yet for this post.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
