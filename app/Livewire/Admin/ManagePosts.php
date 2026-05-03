<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ManagePosts extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;
    public $deleteId = null;
    public $showDeleteModal = false;

    // Comments Modal
    public $showCommentsModal = false;
    public $currentPostIdForComments = null;
    public $currentPostTitle = '';
    public $postComments = [];

    // Form fields
    public $title = '';
    public $slug = '';
    public $excerpt = '';
    public $content = '';
    public $category = 'Tech';
    public $status = 'draft';
    public $published_at = '';

    // SEO fields
    public $meta_title = '';
    public $meta_description = '';
    public $tags = [];
    public $tagsInput = ''; // comma-separated raw input for tags

    // AI SEO state
    public $isGeneratingSeo = false;
    public $seoErrorMessage = '';

    // Supported Categories
    public $categories = ['Tech', 'Tutorial', 'Insight', 'Life', 'Personal', 'Other'];

    // Image Upload
    public $featured_image = ''; // Existing image URL/Path
    public $new_featured_image = null; // Temporary upload

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('posts', 'slug')->ignore($this->editingId),
            ],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string|max:50',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'new_featured_image' => 'nullable|image|max:2048', // 2MB Max
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tagsInput' => 'nullable|string',
        ];
    }

    public function updatedTitle($value)
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $post = Post::findOrFail($id);

        $this->editingId = $id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->category = $post->category ?? 'Tech';
        $this->status = $post->status;
        $this->published_at = $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '';
        $this->featured_image = $post->featured_image;
        $this->meta_title = $post->meta_title ?? '';
        $this->meta_description = $post->meta_description ?? '';
        $this->tags = $post->tags ?? [];
        $this->tagsInput = is_array($post->tags) ? implode(', ', $post->tags) : '';

        $this->isEditing = true;
        $this->showModal = true;

        // Dispatch event to refresh Trix content
        $this->dispatch('refresh-markdown', content: $this->content);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Generate SEO metadata (Meta Title, Meta Description, Tags) using Ollama AI.
     */
    public function generateSeoAndTags()
    {
        if (empty($this->title) && empty($this->content)) {
            $this->seoErrorMessage = 'Please fill in the post title or content first.';
            return;
        }

        $this->isGeneratingSeo = true;
        $this->seoErrorMessage = '';

        try {
            $apiKey  = config('services.ollama.key');
            $baseUrl = config('services.ollama.base_url');
            // Use the reliable gemma3:27b model (gpt-oss:120b returns empty content for this task)
            $model   = config('services.ollama.model', 'gemma3:27b');

            if (!$apiKey) {
                throw new \Exception('Ollama API key not configured.');
            }

            // Strip HTML tags from content for cleaner text
            $plainContent    = strip_tags($this->content);
            $truncatedContent = Str::limit($plainContent, 2000);

            $systemMsg = 'You are an expert SEO specialist. You ONLY respond with valid JSON objects — no markdown, no explanation, no code fences.';

            $userMsg = <<<MSG
Based on this blog post, generate SEO metadata.

POST TITLE: {$this->title}

POST CONTENT:
{$truncatedContent}

Respond ONLY with this exact JSON structure:
{
  "meta_title": "SEO title max 60 chars, keyword-rich",
  "meta_description": "Compelling description max 155 chars",
  "tags": ["tag1", "tag2", "tag3", "tag4", "tag5"]
}

Rules:
- meta_title must be ≤ 60 characters
- meta_description must be ≤ 155 characters
- tags: 4-6 short keyword tags (Title Case or lowercase)
- Match the language of the post (English or Indonesian)
- Return ONLY the JSON object, nothing else
MSG;

            $response = Http::withToken($apiKey)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post("{$baseUrl}/chat/completions", [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemMsg],
                        ['role' => 'user',   'content' => $userMsg],
                    ],
                    'temperature' => 0.2,
                    'max_tokens'  => 512,
                    'stream'      => false,
                    // Note: response_format not supported by gemma3:27b on Ollama cloud
                ]);

            if ($response->failed()) {
                $errBody = $response->json();
                $errMsg  = $errBody['error']['message'] ?? $response->body();
                throw new \Exception('Ollama API error: ' . $errMsg);
            }

            $result  = $response->json();
            $rawText = $result['choices'][0]['message']['content']
                    ?? $result['message']['content']
                    ?? '';

            // Log raw response for debugging
            Log::debug('AI SEO raw response', ['model' => $model, 'raw' => mb_substr($rawText, 0, 500)]);

            // Strip markdown code fences (gemma3 wraps JSON in ```json ... ```)
            $cleanText = preg_replace('/^```(?:json)?\s*/i', '', trim($rawText));
            $cleanText = preg_replace('/\s*```\s*$/i', '', $cleanText);
            $cleanText = trim($cleanText);

            // Try direct JSON decode first
            $seoData = json_decode($cleanText, true);

            // Fallback: extract JSON object from anywhere in the response
            if (!$seoData || !isset($seoData['meta_title'])) {
                preg_match('/\{[^{}]*"meta_title"[^{}]*\}/s', $rawText, $m);
                if (!empty($m[0])) {
                    $seoData = json_decode($m[0], true);
                }
            }

            if (!$seoData || !isset($seoData['meta_title'])) {
                Log::error('AI SEO: Could not parse JSON', ['raw' => mb_substr($rawText, 0, 500)]);
                throw new \Exception('AI returned an invalid response. Please try again.');
            }

            $this->meta_title       = $seoData['meta_title'] ?? '';
            $this->meta_description = $seoData['meta_description'] ?? '';
            $this->tags             = is_array($seoData['tags']) ? $seoData['tags'] : [];
            $this->tagsInput        = implode(', ', $this->tags);

        } catch (\Exception $e) {
            $this->seoErrorMessage = $e->getMessage();
            Log::error('AI SEO Generation Error: ' . $e->getMessage());
        } finally {
            $this->isGeneratingSeo = false;
        }
    }

    /**
     * Remove a tag chip.
     */
    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
        $this->tagsInput = implode(', ', $this->tags);
    }

    /**
     * Sync tags array from comma-separated input.
     */
    public function updatedTagsInput($value)
    {
        $this->tags = collect(explode(',', $value))
            ->map(fn($t) => trim($t))
            ->filter()
            ->values()
            ->toArray();
    }

    public function save()
    {
        $this->validate();

        // Sync tags from input before saving
        if (!empty($this->tagsInput)) {
            $this->tags = collect(explode(',', $this->tagsInput))
                ->map(fn($t) => trim($t))
                ->filter()
                ->values()
                ->toArray();
        }

        $data = [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category' => $this->category,
            'status' => $this->status,
            'published_at' => $this->published_at ?: ($this->status === 'published' ? now() : null),
            'meta_title' => $this->meta_title ?: null,
            'meta_description' => $this->meta_description ?: null,
            'tags' => !empty($this->tags) ? $this->tags : null,
        ];

        // Handle Image Upload
        if ($this->new_featured_image) {
            $path = $this->new_featured_image->store('posts', 'public');
            $data['featured_image'] = Storage::url($path);

            // Delete old image if editing and replacing
            if ($this->isEditing && $this->featured_image) {
                $oldPath = str_replace('/storage/', '', $this->featured_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        if ($this->isEditing && $this->editingId) {
            Post::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Post updated successfully!');
        } else {
            Post::create($data);
            session()->flash('message', 'Post created successfully!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);

        // Delete image if exists
        if ($post->featured_image) {
            $oldPath = str_replace('/storage/', '', $post->featured_image);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $post->delete();
        session()->flash('message', 'Post deleted successfully!');
    }

    // --- Comments Management ---
    public function openCommentsModal($postId)
    {
        $post = Post::with('comments')->findOrFail($postId);
        $this->currentPostIdForComments = $post->id;
        $this->currentPostTitle = $post->title;
        $this->postComments = $post->comments()->latest()->get();
        $this->showCommentsModal = true;
    }

    public function closeCommentsModal()
    {
        $this->showCommentsModal = false;
        $this->currentPostIdForComments = null;
        $this->currentPostTitle = '';
        $this->postComments = [];
    }

    public function deleteComment($commentId)
    {
        \App\Models\Comment::findOrFail($commentId)->delete();
        // Refresh comments list
        if ($this->currentPostIdForComments) {
            $this->postComments = \App\Models\Comment::where('post_id', $this->currentPostIdForComments)->latest()->get();
        }
        session()->flash('message', 'Komentar berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->content = '';
        $this->category = 'Tech';
        $this->status = 'draft';
        $this->published_at = '';
        $this->featured_image = '';
        $this->new_featured_image = null;
        $this->meta_title = '';
        $this->meta_description = '';
        $this->tags = [];
        $this->tagsInput = '';
        $this->seoErrorMessage = '';
        $this->isGeneratingSeo = false;
        $this->resetErrorBag();

        // Reset Trix editor
        $this->dispatch('refresh-markdown', content: '');
    }

    public function render()
    {
        return view('livewire.admin.manage-posts', [
            'posts' => Post::withCount('comments')->orderBy('created_at', 'desc')->paginate(10),
        ])->layout('layouts.admin', ['title' => 'Manage Posts']);
    }
}
