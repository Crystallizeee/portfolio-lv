<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\Language;
use App\Models\Analytics;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CvDownloadController extends Controller
{
    /**
     * Download the latest CV as PDF.
     * 
     * Usage: GET /api/cv/download
     * Auth:  Authorization: Bearer <CV_API_TOKEN>
     * Params: ?locale=en|id (default: en)
     */
    public function download(Request $request)
    {
        // Validate bearer token
        $token = $request->bearerToken();
        $expectedToken = config('services.cv_api.token');

        if (!$expectedToken || $token !== $expectedToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get the first (owner) user
        $user = User::first();

        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }

        // Set locale
        $locale = $request->query('locale', 'en');
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
        }

        // Build CV data (same logic as CvGenerator)
        $data = [
            'personal' => [
                'name'     => $user->name,
                'email'    => $user->email,
                'phone'    => $user->phone ?? '',
                'address'  => $user->address ?? '',
                'linkedin' => $user->linkedin ?? '',
                'github'   => $user->github ?? '',
                'website'  => $user->website ?? '',
                'summary'  => $user->summary ?? '',
                'professional_title' => $user->professional_title ?? 'ICT Security Professional & Software Engineer',
            ],
            'educations'     => Education::where('user_id', $user->id)->orderBy('sort_order')->get()->toArray(),
            'certifications' => Certificate::where('user_id', $user->id)->orderBy('sort_order')->get()->toArray(),
            'experiences'    => Experience::orderBy('sort_order')->get()->toArray(),
            'skills'         => Skill::orderBy('level', 'desc')->get()->toArray(),
            'languages'      => Language::where('user_id', $user->id)->orderBy('sort_order')->get()->toArray(),
            'projects'       => Project::where('status', 'online')->orderBy('created_at', 'desc')->get()->toArray(),
        ];

        // Generate PDF
        $html = view('pdf.cv-template', $data)->render();
        $pdf = Pdf::loadHtml($html);

        // Track download
        Analytics::track($user->id, 'cv_api_download');

        // Return PDF as download
        $filename = 'cv-' . strtolower(str_replace(' ', '-', $user->name)) . '.pdf';

        return $pdf->download($filename);
    }
}
