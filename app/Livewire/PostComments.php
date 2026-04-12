<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Services\IpAnonymizer;
use Illuminate\Support\Facades\RateLimiter;

class PostComments extends Component
{
    public $post;
    public $name = '';
    public $content = '';
    public $website = ''; // Honeypot field — must remain empty

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'content' => 'required|min:5|max:1000',
    ];

    public function mount($post)
    {
        $this->post = $post;
    }

    public function addComment()
    {
        $this->validate();

        $ipHash = IpAnonymizer::hashRequest();

        // Honeypot check — bots fill hidden fields
        if (!empty($this->website)) {
            Comment::create([
                'post_id' => $this->post->id,
                'name' => $this->name,
                'content' => $this->content,
                'ip_hash' => $ipHash,
                'honeypot_triggered' => true,
                'spam_score' => 100,
                'is_approved' => false,
            ]);

            // Pretend success to the bot
            $this->name = '';
            $this->content = '';
            $this->website = '';
            session()->flash('comment_pending', true);
            return;
        }

        // Rate limiting: max 3 comments per IP per 10 minutes
        $rateLimitKey = 'comment:' . $ipHash;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            session()->flash('error', "Terlalu banyak komentar. Coba lagi dalam {$seconds} detik.");
            return;
        }
        RateLimiter::hit($rateLimitKey, 600); // 10 minute window

        // Calculate spam score
        $spamScore = $this->calculateSpamScore($this->name, $this->content);

        // Auto-approve if spam score is low (< 30)
        $isApproved = $spamScore < 30;

        Comment::create([
            'post_id' => $this->post->id,
            'name' => $this->name,
            'content' => $this->content,
            'ip_hash' => $ipHash,
            'spam_score' => $spamScore,
            'honeypot_triggered' => false,
            'is_approved' => $isApproved,
        ]);

        $this->name = '';
        $this->content = '';
        $this->website = '';

        if ($isApproved) {
            session()->flash('message', 'Komentar berhasil ditambahkan.');
        } else {
            session()->flash('comment_pending', true);
        }
    }

    /**
     * Calculate a basic spam score (0-100).
     * Higher = more likely spam.
     */
    private function calculateSpamScore(string $name, string $content): int
    {
        $score = 0;

        // Check for excessive links
        $linkCount = preg_match_all('/https?:\/\//', $content);
        if ($linkCount >= 3) $score += 40;
        elseif ($linkCount >= 1) $score += 15;

        // Check for excessive CAPS
        $totalChars = strlen(preg_replace('/\s/', '', $content));
        if ($totalChars > 10) {
            $capsCount = strlen(preg_replace('/[^A-Z]/', '', $content));
            $capsRatio = $capsCount / $totalChars;
            if ($capsRatio > 0.7) $score += 25;
            elseif ($capsRatio > 0.4) $score += 10;
        }

        // Known spam patterns
        $spamPatterns = [
            'buy now', 'click here', 'free money', 'casino', 'viagra',
            'crypto', 'earn money', 'make money online', 'work from home',
            'lottery', 'winner', 'congratulations', 'act now',
        ];
        $lowerContent = strtolower($content . ' ' . $name);
        foreach ($spamPatterns as $pattern) {
            if (str_contains($lowerContent, $pattern)) {
                $score += 30;
                break; // One match is enough
            }
        }

        // Repeated characters (e.g., "aaaaaaa")
        if (preg_match('/(.)\1{5,}/', $content)) {
            $score += 20;
        }

        // Very short name with long content (bot pattern)
        if (strlen($name) <= 2 && strlen($content) > 200) {
            $score += 15;
        }

        return min($score, 100);
    }

    public function deleteComment($commentId)
    {
        if (auth()->check()) {
            Comment::findOrFail($commentId)->delete();
            session()->flash('message', 'Komentar berhasil dihapus.');
        }
    }

    /**
     * Approve a pending comment (admin only).
     */
    public function approveComment($commentId)
    {
        if (auth()->check()) {
            Comment::findOrFail($commentId)->update(['is_approved' => true]);
            session()->flash('message', 'Komentar berhasil disetujui.');
        }
    }

    public function render()
    {
        $comments = $this->post->comments()->approved()->latest()->get();
        $pendingCount = auth()->check() 
            ? $this->post->comments()->pending()->count() 
            : 0;

        return view('livewire.post-comments', [
            'comments' => $comments,
            'pendingComments' => auth()->check() 
                ? $this->post->comments()->pending()->latest()->get() 
                : collect(),
            'pendingCount' => $pendingCount,
        ]);
    }
}
