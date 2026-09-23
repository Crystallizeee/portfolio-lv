<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Education;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\JobProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Analytics;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;

class CvGenerator extends Component
{
    public $locale = 'en'; // Added locale
    public $template = 'professional'; // Template selection
    public $selectedProfileId = null;
    public $availableProfiles = [];

    /**
     * Available CV templates with metadata.
     */
    public const TEMPLATES = [
        'professional' => [
            'label' => 'Professional',
            'description' => 'Classic two-column layout with sidebar. Formal & clean.',
            'icon' => 'layout-grid',
            'color' => 'blue',
        ],
        'minimal' => [
            'label' => 'Minimal',
            'description' => 'Single-column, modern typography. Clean & elegant.',
            'icon' => 'minus',
            'color' => 'emerald',
        ],
        'executive' => [
            'label' => 'Executive',
            'description' => 'Bold navy & gold header. Senior & authoritative.',
            'icon' => 'crown',
            'color' => 'amber',
        ],
    ];

    // Personal Info
    public $name;
    public $email;
    public $phone;
    public $address;
    public $linkedin;
    public $github; // Added
    public $website;
    public $summary;
    public $professional_title;

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
        
        $this->availableProfiles = JobProfile::where('user_id', $user->id ?? 0)->get();

        $this->name = $user ? $user->name : 'Developer Name';
        $this->email = $user ? $user->email : 'email@example.com';
        $this->phone = $user ? $user->phone : '';
        $this->address = $user ? $user->address : '';
        $this->linkedin = $user ? $user->linkedin : ''; 
        $this->github = $user ? $user->github : ''; 
        $this->website = $user ? $user->website : ''; 
        $this->summary = $user ? $user->summary : ''; 
        $this->professional_title = $user ? $user->professional_title : 'ICT Security Professional & Software Engineer';
        
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

    public function updatedSelectedProfileId($value)
    {
        if ($value) {
            $profile = JobProfile::where('user_id', Auth::id())->find($value);
            if ($profile) {
                $this->professional_title = $profile->professional_title;
                $this->summary = $profile->summary;
                // Note: about lists are generally for the homepage, but we could add them to the CV if a template needs it.
            }
        }
    }

    public function addEducation()
    {
        $throttleKey = 'cv-add-education|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        $this->educations[] = ['school' => '', 'degree' => '', 'year' => '', 'thesis' => ''];
    }

    public function removeEducation($index)
    {
        $throttleKey = 'cv-remove-education|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        unset($this->educations[$index]);
        $this->educations = array_values($this->educations);
    }

    public function addCertification()
    {
        $throttleKey = 'cv-add-certification|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        $this->certifications[] = ['name' => '', 'issuer' => '', 'year' => '', 'description' => ''];
    }

    public function removeCertification($index)
    {
        $throttleKey = 'cv-remove-certification|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        unset($this->certifications[$index]);
        $this->certifications = array_values($this->certifications);
    }
    
    public function addManualLanguage() // Added
    {
        $throttleKey = 'cv-add-language|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        $this->manualLanguages[] = ['name' => '', 'level' => ''];
    }

    public function removeManualLanguage($index) // Added
    {
        $throttleKey = 'cv-remove-language|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        unset($this->manualLanguages[$index]);
        $this->manualLanguages = array_values($this->manualLanguages);
    }

    public function addManualExperience()
    {
        $throttleKey = 'cv-add-experience|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        $this->manualExperiences[] = ['company' => '', 'role' => '', 'date_range' => '', 'description' => ''];
    }

    public function removeManualExperience($index)
    {
        $throttleKey = 'cv-remove-experience|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        unset($this->manualExperiences[$index]);
        $this->manualExperiences = array_values($this->manualExperiences);
    }

    public function addManualSkill()
    {
        $throttleKey = 'cv-add-skill|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        $this->manualSkills[] = ['name' => ''];
    }

    public function removeManualSkill($index)
    {
        $throttleKey = 'cv-remove-skill|' . Auth::id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts. Please try again in {$seconds} seconds.");
            return;
        }
        RateLimiter::hit($throttleKey, 60);

        unset($this->manualSkills[$index]);
        $this->manualSkills = array_values($this->manualSkills);
    }

    /**
     * Resolve the Blade view name for the selected template.
     */
    protected function resolveTemplateView(): string
    {
        $allowed = array_keys(self::TEMPLATES);
        $template = in_array($this->template, $allowed) ? $this->template : 'professional';

        return "pdf.cv-{$template}";
    }

    public function generatePdf()
    {
        $userId = Auth::id();
        $throttleKey = 'cv-generator:' . $userId;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('generatePdf', "Too many requests. Please try again in {$seconds} seconds.");
            return;
        }

        RateLimiter::hit($throttleKey, 60);

        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

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
                'professional_title' => $this->professional_title,
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

        $viewName = $this->resolveTemplateView();
        $html = view($viewName, $data)->render();
        $pdf = Pdf::loadHtml($html);
        
        // Track download
        Analytics::track($userId, 'cv_download');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'cv-' . strtolower(str_replace(' ', '-', $this->name)) . '.pdf');
    }

    public function render()
    {
        return view('livewire.admin.cv-generator', [
            'templates' => self::TEMPLATES,
        ])
            ->layout('layouts.admin', ['title' => 'CV Generator']);
    }
}
