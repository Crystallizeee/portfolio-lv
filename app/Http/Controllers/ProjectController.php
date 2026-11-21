<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->when(is_numeric($slug), fn ($query) => $query->orWhere('id', $slug))
            ->firstOrFail();

        // Check content visibility
        if ($project->status !== 'online' && !auth()->check()) {
            abort(404);
        }

        // 301 redirect numeric ID or non-canonical slug to canonical slug URL for SEO
        if ($project->slug && $slug !== $project->slug) {
            return redirect()->route('projects.show', $project->slug, 301);
        }

        return view('projects.show', compact('project'));
    }
}
