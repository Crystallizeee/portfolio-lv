<?php

namespace App\Livewire\Admin;

use App\Models\Analytics;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Services\AiSecurityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AiCoverLetter extends Component
{
    use WithFileUploads;

    public $jobUrl = '';
    public $manualJobDescription = '';
    public $screenshot;
    public $driveScreenshotPath = '';
    public $driveScreenshotName = '';
    public $signatureUpload;
    public $language = 'id';
    public $companyName = '';
    public $jobTitle = '';
    public $coverLetter = '';
    public $isGenerating = false;
    public $errorMessage = '';
    public $driveStatusMessage = '';
    public $signatureSavedMessage = '';

    protected const SIGNATURE_PATH = 'cover-letter-signature.png';

    protected function rules(): array
    {
        return [
            'jobUrl' => 'nullable|url',
            'manualJobDescription' => 'nullable|string',
            'screenshot' => 'nullable|image|max:5120',
            'language' => 'in:id,en',
        ];
    }

    public function updatedSignatureUpload(): void
    {
        $this->validate([
            'signatureUpload' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        Storage::disk('local')->put(
            self::SIGNATURE_PATH,
            file_get_contents($this->signatureUpload->getRealPath())
        );

        $this->signatureUpload = null;
        $this->signatureSavedMessage = 'Tanda tangan berhasil disimpan.';
    }

    public function removeSignature(): void
    {
        Storage::disk('local')->delete(self::SIGNATURE_PATH);
        $this->signatureSavedMessage = 'Tanda tangan dihapus.';
    }

    public function getIsRcloneConfiguredProperty(): bool
    {
        return ! empty(trim((string) config('services.rclone.remote')));
    }

    protected function rcloneRemoteBase(): string
    {
        $remote = rtrim(trim((string) config('services.rclone.remote')), ':');
        $basePath = trim((string) config('services.rclone.cover_letter_path', 'Automation/Project Cover Letter'), '/');

        return "{$remote}:{$basePath}";
    }

    public function fetchLatestFromDrive(): void
    {
        $this->errorMessage = '';
        $this->driveStatusMessage = '';

        if (! $this->isRcloneConfigured) {
            $this->errorMessage = 'RCLONE_REMOTE belum diset di .env.';
            return;
        }

        $bin = (string) config('services.rclone.bin', 'rclone');
        $screenshootDir = $this->rcloneRemoteBase() . '/Screenshoot';

        $list = Process::timeout(30)->run([$bin, 'lsjson', $screenshootDir, '--files-only']);
        if (! $list->successful()) {
            $this->errorMessage = 'Gagal membaca folder GDrive via rclone: ' . trim($list->errorOutput() ?: $list->output());
            return;
        }

        $items = json_decode($list->output(), true);
        if (! is_array($items)) {
            $this->errorMessage = 'Respons rclone lsjson tidak valid.';
            return;
        }

        $images = array_values(array_filter($items, function ($item) {
            $name = strtolower((string) ($item['Name'] ?? ''));
            return preg_match('/\.(png|jpe?g|webp)$/', $name) === 1;
        }));

        if (empty($images)) {
            $this->errorMessage = 'Tidak ada file screenshot gambar di folder Screenshoot GDrive.';
            return;
        }

        usort($images, fn ($a, $b) => strcmp((string) ($b['ModTime'] ?? ''), (string) ($a['ModTime'] ?? '')));
        $latest = $images[0]['Name'];
        $ext = strtolower(pathinfo($latest, PATHINFO_EXTENSION)) ?: 'png';
        $localRelPath = "cover-letter-drive-screenshot.{$ext}";

        Storage::disk('local')->makeDirectory('.');
        $localAbsPath = Storage::disk('local')->path($localRelPath);

        $copy = Process::timeout(45)->run([$bin, 'copyto', "{$screenshootDir}/{$latest}", $localAbsPath]);
        if (! $copy->successful() || ! file_exists($localAbsPath)) {
            $this->errorMessage = 'Gagal mengunduh screenshot dari GDrive: ' . trim($copy->errorOutput() ?: $copy->output());
            return;
        }

        $this->screenshot = null;
        $this->driveScreenshotPath = $localRelPath;
        $this->driveScreenshotName = $latest;
        $this->driveStatusMessage = "Screenshot diambil dari GDrive: {$latest}";
    }

    public function clearDriveScreenshot(): void
    {
        if ($this->driveScreenshotPath) {
            Storage::disk('local')->delete($this->driveScreenshotPath);
        }
        $this->driveScreenshotPath = '';
        $this->driveScreenshotName = '';
        $this->driveStatusMessage = '';
    }

    public function getDriveScreenshotPreviewUrlProperty(): ?string
    {
        if (! $this->driveScreenshotPath || ! Storage::disk('local')->exists($this->driveScreenshotPath)) {
            return null;
        }

        $bytes = Storage::disk('local')->get($this->driveScreenshotPath);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? (finfo_buffer($finfo, $bytes) ?: 'image/png') : 'image/png';
        if ($finfo) {
            finfo_close($finfo);
        }

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }

    public function getHasSignatureProperty(): bool
    {
        return Storage::disk('local')->exists(self::SIGNATURE_PATH);
    }

    public function getSignaturePreviewUrlProperty(): ?string
    {
        if (! $this->hasSignature) {
            return null;
        }

        $bytes = Storage::disk('local')->get(self::SIGNATURE_PATH);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? (finfo_buffer($finfo, $bytes) ?: 'image/png') : 'image/png';
        if ($finfo) {
            finfo_close($finfo);
        }

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }

    public function generate()
    {
        // 🛡️ Sentinel: Apply rate limiting to prevent abuse of the external API endpoint
        $throttleKey = 'ai-cover-letter:' . auth()->id();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Too many requests. Please try again in {$seconds} seconds.";
            return;
        }

        RateLimiter::hit($throttleKey, 60);
        $this->validate();

        if (! $this->screenshot && ! $this->hasDriveScreenshot() && empty($this->jobUrl) && empty($this->manualJobDescription)) {
            $this->errorMessage = 'Upload screenshot loker, ambil dari GDrive, isi link loker, atau paste deskripsi pekerjaan.';
            return;
        }

        $this->isGenerating = true;
        $this->errorMessage = '';
        $this->coverLetter = '';

        try {
            $cvData = $this->gatherCvData();
            $prompt = $this->constructPrompt($cvData);

            $geminiKey = config('services.gemini.key');
            if (($this->screenshot || $this->hasDriveScreenshot()) && ! $geminiKey) {
                throw new \Exception('GEMINI_API_KEY belum diset di .env untuk membaca screenshot loker.');
            }

            $rawJson = $geminiKey
                ? $this->callGemini($geminiKey, $prompt)
                : $this->callOllama($prompt);

            $this->applyGeneratedPayload($rawJson);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            Log::error('AI Cover Letter Generation Error: ' . $e->getMessage());
        } finally {
            $this->isGenerating = false;
        }
    }

    protected function hasDriveScreenshot(): bool
    {
        return ! empty($this->driveScreenshotPath) && Storage::disk('local')->exists($this->driveScreenshotPath);
    }

    protected function callGemini(string $apiKey, string $prompt): string
    {
        $parts = [['text' => $prompt]];

        if ($this->screenshot) {
            $bytes = file_get_contents($this->screenshot->getRealPath());
            $mime = $this->screenshot->getMimeType() ?: 'image/png';
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mime,
                    'data' => base64_encode($bytes),
                ],
            ];
        } elseif ($this->hasDriveScreenshot()) {
            $bytes = Storage::disk('local')->get($this->driveScreenshotPath);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? (finfo_buffer($finfo, $bytes) ?: 'image/png') : 'image/png';
            if ($finfo) {
                finfo_close($finfo);
            }
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mime,
                    'data' => base64_encode($bytes),
                ],
            ];
        }

        $response = Http::timeout(45)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
            [
                'contents' => [['parts' => $parts]],
                'generationConfig' => [
                    'temperature' => 0.6,
                    'maxOutputTokens' => 2048,
                    'responseMimeType' => 'application/json',
                ],
            ]
        );

        if ($response->failed()) {
            $errMsg = $response->json('error.message') ?? $response->body();
            throw new \Exception('Gemini API error: ' . $errMsg);
        }

        return $response->json('candidates.0.content.parts.0.text')
            ?? throw new \Exception('Gemini mengembalikan respons kosong.');
    }

    protected function callOllama(string $prompt): string
    {
        $apiKey = config('services.ollama.key');
        $baseUrl = config('services.ollama.base_url', 'https://ollama.com/v1');
        $model = config('services.ollama.model', 'gemma4:31b');

        if (! $apiKey) {
            throw new \Exception('GEMINI_API_KEY dan OLLAMA_API_KEY tidak ditemukan di .env.');
        }

        $response = Http::withToken($apiKey)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(45)
            ->post("{$baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.7,
                'max_tokens' => 2048,
                'stream' => false,
            ]);

        if ($response->failed()) {
            $errMsg = $response->json('error.message') ?? $response->body();
            throw new \Exception('Ollama API error: ' . $errMsg);
        }

        return $response->json('choices.0.message.content')
            ?? $response->json('message.content')
            ?? throw new \Exception('Ollama mengembalikan respons kosong.');
    }

    protected function applyGeneratedPayload(string $raw): void
    {
        $security = app(AiSecurityService::class);
        $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($raw));
        $decoded = json_decode($cleaned, true);

        if (is_array($decoded) && ! empty($decoded['body'])) {
            $this->companyName = trim((string) ($decoded['company_name'] ?? ''));
            $this->jobTitle = trim((string) ($decoded['job_title'] ?? ''));
            $this->coverLetter = $security->sanitizeOutput((string) $decoded['body']);
            return;
        }

        $this->coverLetter = $security->sanitizeOutput($raw);
    }

    protected function buildPdf(): array
    {
        $user = auth()->user();
        $lang = $this->language === 'en' ? 'en' : 'id';

        if ($lang === 'id') {
            \Carbon\Carbon::setLocale('id');
            $date = now()->translatedFormat('d F Y');
        } else {
            $date = now()->format('F d, Y');
        }

        $pdf = Pdf::loadHtml(view('pdf.cover-letter', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'linkedin' => $user->linkedin,
            'website' => $user->website,
            'companyName' => $this->companyName,
            'jobTitle' => $this->jobTitle,
            'body' => $this->coverLetter,
            'language' => $lang,
            'date' => $date,
            'signatureDataUri' => $this->signaturePreviewUrl,
        ])->render())->setPaper('a4', 'portrait');

        $slug = \Illuminate\Support\Str::slug(
            trim($this->companyName . '-' . $this->jobTitle) ?: 'application',
            '_'
        );

        return [$pdf, "Cover_Letter_{$slug}.pdf"];
    }

    public function downloadPdf()
    {
        if (empty(trim($this->coverLetter))) {
            $this->errorMessage = 'Generate atau isi cover letter terlebih dahulu sebelum export PDF.';
            return;
        }

        [$pdf, $filename] = $this->buildPdf();
        Analytics::track(auth()->id(), 'cover_letter_pdf_download');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function savePdfToDrive(): void
    {
        $this->errorMessage = '';
        $this->driveStatusMessage = '';

        if (! $this->isRcloneConfigured) {
            $this->errorMessage = 'RCLONE_REMOTE belum diset di .env.';
            return;
        }

        if (empty(trim($this->coverLetter))) {
            $this->errorMessage = 'Generate atau isi cover letter terlebih dahulu sebelum upload ke GDrive.';
            return;
        }

        [$pdf, $filename] = $this->buildPdf();
        $localRelPath = "tmp-{$filename}";
        Storage::disk('local')->put($localRelPath, $pdf->output());
        $localAbsPath = Storage::disk('local')->path($localRelPath);

        $bin = (string) config('services.rclone.bin', 'rclone');
        $dest = $this->rcloneRemoteBase() . "/Output/{$filename}";

        $upload = Process::timeout(45)->run([$bin, 'copyto', $localAbsPath, $dest]);
        Storage::disk('local')->delete($localRelPath);

        if (! $upload->successful()) {
            $this->errorMessage = 'Gagal upload PDF ke GDrive: ' . trim($upload->errorOutput() ?: $upload->output());
            return;
        }

        Analytics::track(auth()->id(), 'cover_letter_pdf_gdrive_upload');
        $this->driveStatusMessage = "PDF tersimpan di GDrive: Output/{$filename}";
    }

    protected function gatherCvData(): array
    {
        $experiences = Experience::orderBy('sort_order')
            ->get(['role', 'company', 'date_range', 'description'])
            ->map(fn ($e) => "- {$e->role} at {$e->company} ({$e->date_range}): {$e->description}")
            ->implode("\n");

        $skills = Skill::orderBy('level', 'desc')
            ->get(['name', 'category'])
            ->map(fn ($s) => $s->category ? "{$s->name} ({$s->category})" : $s->name)
            ->implode(', ');

        $projects = Project::where('status', 'online')
            ->get(['title', 'description'])
            ->map(fn ($p) => "- {$p->title}: " . strip_tags((string) $p->description))
            ->implode("\n");

        $certificates = Certificate::where('user_id', auth()->id())
            ->orderBy('sort_order')
            ->get(['name', 'issuer'])
            ->map(fn ($c) => $c->issuer ? "{$c->name} ({$c->issuer})" : $c->name)
            ->implode(', ');

        return [
            'name' => auth()->user()->name,
            'experiences' => $experiences,
            'skills' => $skills,
            'projects' => $projects,
            'certificates' => $certificates,
        ];
    }

    protected function constructPrompt(array $cvData): string
    {
        $security = app(AiSecurityService::class);
        $sanitizedDesc = $security->sanitizeInput((string) $this->manualJobDescription);
        $sanitizedUrl = $security->sanitizeInput((string) $this->jobUrl);

        $jobContextParts = [];
        if ($this->screenshot || $this->hasDriveScreenshot()) {
            $jobContextParts[] = 'Read the attached job vacancy screenshot carefully for company name, role, and requirements.';
        }
        if ($sanitizedDesc) {
            $jobContextParts[] = "Job description:\n{$sanitizedDesc}";
        }
        if ($sanitizedUrl) {
            $jobContextParts[] = "Job link: {$sanitizedUrl}";
        }
        $jobContext = implode("\n\n", $jobContextParts);

        $langInstruction = $this->language === 'en'
            ? "Write in natural, professional English. Start with 'Dear Hiring Team,' (or specific company/recipient)."
            : "Write in natural, formal Indonesian. Start with 'Yth. Tim Rekrutmen PT [Nama Perusahaan],' (or 'Yth. Tim Hiring,' if company name is unknown).";

        return "You are a professional career consultant. Write a tailored 1-page cover letter matching the candidate's CV data to the job requirements.

CANDIDATE CV DATA:
Name: {$cvData['name']}
Skills: {$cvData['skills']}
Certifications: {$cvData['certificates']}
Experiences:
{$cvData['experiences']}
Key Projects:
{$cvData['projects']}

JOB CONTEXT:
{$jobContext}

GUIDELINES:
1. {$langInstruction}
2. Highlight only relevant experiences, technical skills, and certifications that directly match the job requirements.
3. Keep it concise (3-4 tight paragraphs) so it fits cleanly on a single A4 page.
4. DO NOT include any closing ('Hormat saya', 'Sincerely') or signature/name at the end — the PDF template adds those automatically.
5. Return ONLY valid JSON with keys: \"company_name\" (string), \"job_title\" (string), \"body\" (string with paragraphs separated by double newlines \\n\\n).";
    }

    public function render()
    {
        return view('livewire.admin.ai-cover-letter')
            ->layout('layouts.admin', ['title' => 'AI Cover Letter']);
    }
}
