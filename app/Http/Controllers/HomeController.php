<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analytics;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Track profile view for the first user (portfolio owner)
        $owner = User::first();
        if ($owner) {
            Analytics::track($owner->id, 'profile_view');
        }
        
        return view('welcome');
    }
}
