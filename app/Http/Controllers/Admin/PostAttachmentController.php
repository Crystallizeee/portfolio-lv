<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostAttachmentController extends Controller
{
    public function upload(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Upload endpoint hit', $request->all());
        \Illuminate\Support\Facades\Log::info('Has file? ' . ($request->hasFile('attachment') ? 'Yes' : 'No'));
        
        $request->validate([
            'attachment' => 'required|image|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('posts/attachments', 'public');
            
            \Illuminate\Support\Facades\Log::info('Saved path: ' . $path);

            return response()->json([
                'url' => Storage::url($path),
            ]);
        }

        \Illuminate\Support\Facades\Log::error('No file provided');
        return response()->json(['error' => 'No file provided'], 400);
    }
}
