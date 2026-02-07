<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Analytics;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Cache portfolio owner for 1 hour to reduce DB queries
        $owner = Cache::remember('portfolio_owner', 3600, function () {
            return User::first();
        });
        if ($owner) {
            Analytics::track($owner->id, 'profile_view');
        }
        
        return view('welcome', compact('owner'));
    }
}
