<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostAttachmentController extends Controller
{
    public function upload(Request $request)
    {
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
