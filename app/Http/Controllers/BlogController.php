<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->paginate(9);
            
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where(function($query) {
                $query->where('status', 'published')
                      ->orWhere(function($q) {
                          if (auth()->check()) {
                              $q->whereIn('status', ['draft', 'published']);
                          }
                      });
            })
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
