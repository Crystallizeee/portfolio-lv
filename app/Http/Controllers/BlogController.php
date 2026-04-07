<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        
        $posts = Post::published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('published_at', 'desc')
            ->paginate(9);
            
        return view('blog.index', compact('posts', 'category'));
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
