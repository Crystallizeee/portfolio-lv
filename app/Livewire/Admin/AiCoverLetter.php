<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCoverLetter extends Component
{
    public $jobUrl = '';
    public $manualJobDescription = '';
    public $coverLetter = '';
    public $isGenerating = false;
    public $errorMessage = '';

    protected $rules = [
        'jobUrl' => 'nullable|url',
        'manualJobDescription' => 'nullable|string',
    ];

    public function generate()
    {
        if (empty($this->jobUrl) && empty($this->manualJobDescription)) {
            $this->errorMessage = 'Please provide either a job URL or a manual job description.';
            return;
        }

        $this->isGenerating = true;
        $this->errorMessage = '';
        $this->coverLetter = '';

        try {
            $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');

            if (!$apiKey) {
                throw new \Exception('Gemini API key not found. Please set GEMINI_API_KEY in your .env file.');
            }

            // Gather CV Data
            $cvData = $this->gatherCvData();

            // Construct Prompt
            $prompt = $this->constructPrompt($cvData);

            // Call Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . ($response->json()['error']['message'] ?? 'Unknown error'));
            }

            $result = $response->json();
            $this->coverLetter = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Failed to generate cover letter.';

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            Log::error('AI Cover Letter Generation Error: ' . $e->getMessage());
        } finally {
            $this->isGenerating = false;
        }
    }

    protected function gatherCvData()
    {
        $experiences = Experience::orderBy('sort_order')->get()->map(fn($e) => "- {$e->role} at {$e->company}: {$e->description}")->implode("\n");
        $skills = Skill::orderBy('level', 'desc')->get()->map(fn($s) => "- {$s->name} ({$s->level}%)")->implode(", ");
        $projects = Project::where('status', 'online')->get()->map(fn($p) => "- {$p->title}: {$p->description}")->implode("\n");

        return [
            'name' => auth()->user()->name,
            'experiences' => $experiences,
            'skills' => $skills,
            'projects' => $projects,
        ];
    }

    protected function constructPrompt($cvData)
    {
        $jobContext = $this->manualJobDescription ?: "Job link: {$this->jobUrl}";

        return "
You are a professional hiring consultant. Your task is to write a compelling cover letter based on the user's CV data and a job description (or link provided).

USER CV DATA:
Name: {$cvData['name']}
Skills: {$cvData['skills']}
Experiences:
{$cvData['experiences']}
Key Projects:
{$cvData['projects']}

JOB CONTEXT:
{$jobContext}

STRICT GUIDELINES:
1. Start the letter with 'Yth. Tim Hiring PT [Nama Perusahaan yang relevan dari konteks]'. If company name is not found, use 'Yth. Tim Hiring'.
2. The content must be in Indonesian.
3. Align the user's skills and experiences with the job requirements mentioned in the context.
4. Keep it professional, concise, and persuasive.
5. DO NOT include a signature or 'Sincerely' at the end. End with the final paragraph.
6. Return ONLY the content of the cover letter.
";
    }

    public function render()
    {
        return view('livewire.admin.ai-cover-letter')
            ->layout('layouts.admin', ['title' => 'AI Cover Letter']);
    }
}
