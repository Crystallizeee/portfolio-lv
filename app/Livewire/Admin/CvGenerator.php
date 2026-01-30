<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Certificate;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Analytics;
use Illuminate\Support\Facades\App;

class CvGenerator extends Component
{
    public $locale = 'en'; // Added locale
    // Personal Info
    public $name;
    public $email;
    public $phone;
    public $address;
    public $linkedin;
    public $github; // Added
    public $website;
    public $summary;

    // Toggles
    public $useDbExperiences = true;
    public $useDbSkills = true;
    public $useDbEducations = true;
    public $useDbCertifications = true;
    public $useDbLanguages = true;
    public $useDbProjects = true;

    // Dynamic Lists (for manual input)
    public $educations = [];
    public $certifications = [];
    public $manualExperiences = [];
    public $manualSkills = [];
    public $manualLanguages = []; // Added

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user ? $user->name : 'Developer Name';
        $this->email = $user ? $user->email : 'email@example.com';
        $this->phone = $user ? $user->phone : '';
        $this->address = $user ? $user->address : '';
        $this->linkedin = $user ? $user->linkedin : ''; // Corrected from edit
        $this->github = $user ? $user->github : ''; // Added
        $this->website = $user ? $user->website : ''; // Corrected from edit
        $this->summary = $user ? $user->summary : ''; // Corrected from edit
        
        // Initialize with one empty item for manual input
        $this->educations = [
            ['school' => '', 'degree' => '', 'year' => '', 'thesis' => '']
        ];
        
        $this->certifications = [
            ['name' => '', 'issuer' => '', 'year' => '', 'description' => '']
        ];
        
        $this->manualLanguages = [
            ['name' => '', 'level' => '']
        ];
    }

    public function addEducation()
    {
        $this->educations[] = ['school' => '', 'degree' => '', 'year' => '', 'thesis' => ''];
    }

    public function removeEducation($index)
    {
        unset($this->educations[$index]);
        $this->educations = array_values($this->educations);
    }

    public function addCertification()
    {
        $this->certifications[] = ['name' => '', 'issuer' => '', 'year' => '', 'description' => ''];
    }

    public function removeCertification($index)
    {
        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }
    
    public function addManualLanguage() // Added
    {
        $this->manualLanguages[] = ['name' => '', 'level' => ''];
    }

    public function removeManualLanguage($index) // Added
    {
        unset($this->manualLanguages[$index]);
        $this->manualLanguages = array_values($this->manualLanguages);
    }

    public function addManualExperience()
    {
        $this->manualExperiences[] = ['company' => '', 'role' => '', 'date_range' => '', 'description' => ''];
    }

    public function removeManualExperience($index)
    {
        unset($this->manualExperiences[$index]);
        $this->manualExperiences = array_values($this->manualExperiences);
    }

    public function addManualSkill()
    {
        $this->manualSkills[] = ['name' => ''];
    }

    public function removeManualSkill($index)
    {
        unset($this->manualSkills[$index]);
        $this->manualSkills = array_values($this->manualSkills);
    }

    public function generatePdf()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $userId = Auth::id();

        $data = [
            'personal' => [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'linkedin' => $this->linkedin,
                'github' => $this->github, // Added
                'website' => $this->website,
                'summary' => $this->summary,
            ],
            'educations' => $this->useDbEducations 
                ? Education::where('user_id', $userId)->orderBy('sort_order')->get()->toArray() 
                : array_filter($this->educations, fn($e) => !empty($e['school'])),
            'certifications' => $this->useDbCertifications 
                ? Certificate::where('user_id', $userId)->orderBy('sort_order')->get()->toArray() 
                : array_filter($this->certifications, fn($c) => !empty($c['name'])),
            'experiences' => $this->useDbExperiences 
                ? Experience::orderBy('sort_order')->get()->toArray() 
                : $this->manualExperiences, 
            'skills' => $this->useDbSkills 
                ? Skill::orderBy('level', 'desc')->get()->toArray() 
                : $this->manualSkills,
            'languages' => $this->useDbLanguages
                ? \App\Models\Language::where('user_id', $userId)->orderBy('sort_order')->get()->toArray()
                : array_filter($this->manualLanguages, fn($l) => !empty($l['name'])),
            'projects' => $this->useDbProjects
                ? Project::where('status', 'online')->orderBy('created_at', 'desc')->get()->toArray()
                : [],
        ];

        App::setLocale($this->locale);

        $html = view('pdf.cv-template', $data)->render();
        $pdf = Pdf::loadHtml($html);
        
        // Track download
        Analytics::track($userId, 'cv_download');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'cv-' . strtolower(str_replace(' ', '-', $this->name)) . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.cv-generator')
            ->layout('layouts.admin', ['title' => 'CV Generator']);
    }
}
