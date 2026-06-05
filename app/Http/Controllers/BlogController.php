<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        
        // ⚡ Bolt Optimization: Eager load the 'user' relationship to prevent N+1 queries
        // when looping through posts in the views (which calls $post->user->name).
        $posts = Post::with('user')
            ->published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('published_at', 'desc')
            ->paginate(9);
            
        return view('blog.index', compact('posts', 'category'));
    }

    public function show($slug)
    {
        // ⚡ Bolt Optimization: Eager load the 'user' relationship for the show view
        // to prevent an additional query when rendering the author's name.
        $post = Post::with('user')
            ->where('slug', $slug)
            ->where(function($query) {
                $query->where('status', 'published')
                      ->orWhere(function($q) {
                          if (auth()->check()) {
                              $q->whereIn('status', ['draft', 'published'])
                                ->where('user_id', auth()->id());
                          }
                      });
            })
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
