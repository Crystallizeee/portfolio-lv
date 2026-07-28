<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\RateLimiter;

class PostAttachmentController extends Controller
{
    public function upload(Request $request)
    {
        $throttleKey = 'post-attachment-upload:' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json(['error' => "Too many upload attempts. Please try again in {$seconds} seconds."], 429);
        }

        RateLimiter::hit($throttleKey, 60);

        $request->validate([
            'attachment' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('posts/attachments', 'public');

            Log::debug('Post attachment uploaded', ['path' => $path]);

            return response()->json([
                'url' => Storage::url($path),
            ]);
        }

        Log::error('Post attachment upload: no file provided');
        return response()->json(['error' => 'No file provided'], 400);
    }
}
