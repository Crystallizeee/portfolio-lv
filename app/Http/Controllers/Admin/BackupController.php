<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $request->validate([
            'backup_file' => 'required|file|mimes:json'
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
                    $user->update($data['user']);
                }

                // Restore Projects (Delete existing and re-create)
                if (isset($data['projects'])) {
                    Project::truncate();
                    foreach ($data['projects'] as $item) {
                        Project::create($item);
                    }
                }

                // Restore Experiences
                if (isset($data['experiences'])) {
                    Experience::truncate();
                    foreach ($data['experiences'] as $item) {
                        Experience::create($item);
                    }
                }

                // Restore Skills
                if (isset($data['skills'])) {
                    Skill::truncate();
                    foreach ($data['skills'] as $item) {
                        Skill::create($item);
                    }
                }

                // Restore Education
                if (isset($data['educations'])) {
                    Education::where('user_id', $user->id)->delete();
                    foreach ($data['educations'] as $item) {
                        $item['user_id'] = $user->id; // Ensure current user ID
                        Education::create($item);
                    }
                }

                // Restore Certificates
                if (isset($data['certifications'])) {
                    Certificate::where('user_id', $user->id)->delete();
                    foreach ($data['certifications'] as $item) {
                        $item['user_id'] = $user->id;
                        Certificate::create($item);
                    }
                }

                // Restore Languages
                if (isset($data['languages'])) {
                    Language::where('user_id', $user->id)->delete();
                    foreach ($data['languages'] as $item) {
                        $item['user_id'] = $user->id;
                        Language::create($item);
                    }
                }
            });

            return back()->with('success', 'Backup restored successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }
}
