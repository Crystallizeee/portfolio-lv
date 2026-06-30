<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Certificate;
use App\Models\Language;

class BackupController extends Controller
{
    public function export()
    {
        $user = Auth::user();
        
        $data = [
            'user' => $user->only(['name', 'email', 'phone', 'address', 'linkedin', 'github', 'website', 'summary']),
            'projects' => Project::all()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'experiences' => Experience::all()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'skills' => Skill::all()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'educations' => Education::where('user_id', $user->id)->get()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'certifications' => Certificate::where('user_id', $user->id)->get()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'languages' => Language::where('user_id', $user->id)->get()->makeHidden(['created_at', 'updated_at'])->toArray(),
            'exported_at' => now()->toIso8601String(),
            'version' => '1.0'
        ];

        $filename = 'portfolio-backup-' . now()->format('Y-m-d-His') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename);
    }

    public function import(Request $request)
    {
        $userId = Auth::id();
        $throttleKey = 'backup-import:' . $userId;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Too many restore attempts. Please try again in {$seconds} seconds.");
        }

        RateLimiter::hit($throttleKey, 60);

        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:5120'
        ]);

        try {
            $json = file_get_contents($request->file('backup_file')->getRealPath());
            $data = json_decode($json, true);

            if (!$data) {
                throw new \Exception('Invalid JSON file');
            }

            DB::transaction(function () use ($data) {
                $user = Auth::user();

                // Restore User Profile
                if (isset($data['user'])) {
                    $allowedFields = ['name', 'phone', 'address', 'linkedin', 'github', 'website', 'summary'];
                    $userData = array_intersect_key($data['user'], array_flip($allowedFields));
                    $user->update($userData);
                }

                // Restore Projects (Delete existing and re-create)
                if (isset($data['projects'])) {
                    $allowedProjectFields = ['title', 'slug', 'description', 'status', 'type', 'tech_stack', 'url', 'github_url', 'image', 'show_on_landing', 'proxmox_vmid', 'sort_order'];
                    Project::truncate();
                    foreach ($data['projects'] as $item) {
                        Project::create(array_intersect_key($item, array_flip($allowedProjectFields)));
                    }
                }

                // Restore Experiences
                if (isset($data['experiences'])) {
                    $allowedExpFields = ['company', 'role', 'date_range', 'description', 'sort_order'];
                    Experience::truncate();
                    foreach ($data['experiences'] as $item) {
                        Experience::create(array_intersect_key($item, array_flip($allowedExpFields)));
                    }
                }

                // Restore Skills
                if (isset($data['skills'])) {
                    $allowedSkillFields = ['name', 'level', 'category', 'sort_order'];
                    Skill::truncate();
                    foreach ($data['skills'] as $item) {
                        Skill::create(array_intersect_key($item, array_flip($allowedSkillFields)));
                    }
                }

                // Restore Education
                if (isset($data['educations'])) {
                    $allowedEduFields = ['school', 'degree', 'year', 'thesis', 'sort_order'];
                    Education::where('user_id', $user->id)->delete();
                    foreach ($data['educations'] as $item) {
                        $safeItem = array_intersect_key($item, array_flip($allowedEduFields));
                        $safeItem['user_id'] = $user->id;
                        Education::create($safeItem);
                    }
                }

                // Restore Certificates
                if (isset($data['certifications'])) {
                    $allowedCertFields = ['name', 'issuer', 'year', 'description', 'credential_url', 'sort_order'];
                    Certificate::where('user_id', $user->id)->delete();
                    foreach ($data['certifications'] as $item) {
                        $safeItem = array_intersect_key($item, array_flip($allowedCertFields));
                        $safeItem['user_id'] = $user->id;
                        Certificate::create($safeItem);
                    }
                }

                // Restore Languages
                if (isset($data['languages'])) {
                    $allowedLangFields = ['name', 'level', 'sort_order'];
                    Language::where('user_id', $user->id)->delete();
                    foreach ($data['languages'] as $item) {
                        $safeItem = array_intersect_key($item, array_flip($allowedLangFields));
                        $safeItem['user_id'] = $user->id;
                        Language::create($safeItem);
                    }
                }
            });

            return back()->with('success', 'Backup restored successfully!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Backup restore failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Restore failed. Please check the backup file format and try again.');
        }
    }
}
