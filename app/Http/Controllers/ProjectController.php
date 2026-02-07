<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // Check content visibility
        if ($project->status !== 'online' && !auth()->check()) {
            abort(404);
        }

        return view('projects.show', compact('project'));
    }
}
