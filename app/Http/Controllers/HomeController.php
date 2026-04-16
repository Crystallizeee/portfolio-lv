<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Analytics;
use App\Models\User;

class HomeController extends Controller
{
    private function applyProfileVariations($owner, $profile)
    {
        if ($profile) {
            $owner->professional_title = $profile->professional_title ?? $owner->professional_title;
            $owner->summary = $profile->summary ?? $owner->summary;
            $owner->about_grc_list = $profile->about_grc_list ?? $owner->about_grc_list;
            $owner->about_tech_list = $profile->about_tech_list ?? $owner->about_tech_list;
        }
        return $owner;
    }

    public function index()
    {
        // Cache portfolio owner for 1 hour to reduce DB queries
        $owner = Cache::remember('portfolio_owner', 3600, function () {
            $user = User::first();
            if ($user && class_exists('\App\Models\JobProfile')) {
                $landingContext = \App\Models\JobProfile::where('user_id', $user->id)
                    ->where('is_landing_page', true)
                    ->first();
                return $this->applyProfileVariations($user, $landingContext);
            }
            return $user;
        });

        if ($owner) {
            Analytics::track($owner->id, 'profile_view');
        }
        
        return view('welcome', compact('owner'));
    }

    public function profile($slug)
    {
        $user = User::firstOrFail();
        $jobProfile = \App\Models\JobProfile::where('slug', $slug)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $owner = $this->applyProfileVariations($user, $jobProfile);
        
        Analytics::track($owner->id, 'profile_view_variant_' . $slug);
        
        return view('welcome', compact('owner'));
    }
}
