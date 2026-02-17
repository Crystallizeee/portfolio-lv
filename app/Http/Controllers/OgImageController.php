<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class OgImageController extends Controller
{
    public function generate($type, $slug)
    {
        $cacheKey = "{$type}_{$slug}";
        $cachePath = "og-images/{$cacheKey}.png";
        $fullPath = storage_path("app/public/{$cachePath}");

        // Return cached image if exists
        if (file_exists($fullPath)) {
            return response()->file($fullPath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=604800',
            ]);
        }

        // Get title based on type
        $title = '';
        $subtitle = '';
        $date = '';

        switch ($type) {
            case 'post':
                $post = Post::where('slug', $slug)->firstOrFail();
                $title = $post->title;
                $subtitle = $post->excerpt ?? 'Blog Post';
                $date = $post->published_at?->format('M d, Y') ?? '';
                break;
            case 'project':
                $project = Project::where('slug', $slug)->firstOrFail();
                $title = $project->title;
                $subtitle = $project->description ?? 'Project';
                $date = '';
                break;
            default:
                $title = 'Benidictus Tri Wibowo';
                $subtitle = 'Cybersecurity & ICT Risk Professional';
        }

        // Generate image
        $image = $this->createOgImage($title, $subtitle, $date, $type);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Save and return
        imagepng($image, $fullPath);
        imagedestroy($image);

        return response()->file($fullPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    private function createOgImage(string $title, string $subtitle, string $date, string $type): \GdImage
    {
        $width = 1200;
        $height = 630;

        $image = imagecreatetruecolor($width, $height);

        // Colors - Cyberpunk dark theme matching website
        $bgColor = imagecolorallocate($image, 10, 15, 29);        // --color-cyber-dark
        $accentCyan = imagecolorallocate($image, 34, 211, 238);   // cyan-400
        $accentPurple = imagecolorallocate($image, 168, 85, 247); // purple-400
        $white = imagecolorallocate($image, 255, 255, 255);
        $gray = imagecolorallocate($image, 148, 163, 184);        // slate-400
        $darkGray = imagecolorallocate($image, 51, 65, 85);       // slate-700
        $subtleGray = imagecolorallocate($image, 30, 41, 59);     // slate-800

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Top gradient bar (cyan to purple)
        for ($x = 0; $x < $width; $x++) {
            $ratio = $x / $width;
            $r = (int)(34 + ($ratio * (168 - 34)));
            $g = (int)(211 + ($ratio * (85 - 211)));
            $b = (int)(238 + ($ratio * (247 - 238)));
            $gradientColor = imagecolorallocate($image, $r, $g, $b);
            imagefilledrectangle($image, $x, 0, $x, 4, $gradientColor);
        }

        // Subtle grid pattern
        for ($x = 0; $x < $width; $x += 40) {
            imageline($image, $x, 0, $x, $height, $subtleGray);
        }
        for ($y = 0; $y < $height; $y += 40) {
            imageline($image, 0, $y, $width, $y, $subtleGray);
        }

        // Decorative circle (top-right)
        imageellipse($image, $width - 100, 100, 300, 300, $darkGray);

        // Border rectangle
        imagerectangle($image, 40, 40, $width - 40, $height - 40, $darkGray);

        // Type label
        $typeLabel = strtoupper($type === 'post' ? 'BLOG' : ($type === 'project' ? 'PROJECT' : 'PORTFOLIO'));
        
        // Terminal prefix
        $prefix = '~/benidictus';
        imagestring($image, 3, 80, 80, '> ' . $prefix, $accentCyan);

        // Type badge background
        $badgeX = 80;
        $badgeY = 110;
        imagefilledrectangle($image, $badgeX, $badgeY, $badgeX + strlen($typeLabel) * 10 + 20, $badgeY + 28, $darkGray);
        imagestring($image, 4, $badgeX + 10, $badgeY + 6, $typeLabel, $accentCyan);

        // Title - word wrap manually using built-in font
        $titleLines = $this->wordWrap($title, 35);
        $y = 180;
        foreach ($titleLines as $i => $line) {
            // Use font size 5 (largest built-in) for title
            imagestring($image, 5, 80, $y + ($i * 28), $line, $white);
        }

        // Subtitle
        $subtitleTruncated = mb_strlen($subtitle) > 80 ? mb_substr($subtitle, 0, 80) . '...' : $subtitle;
        $subtitleY = $y + count($titleLines) * 28 + 20;
        $subtitleLines = $this->wordWrap($subtitleTruncated, 60);
        foreach ($subtitleLines as $i => $line) {
            imagestring($image, 3, 80, $subtitleY + ($i * 18), $line, $gray);
        }

        // Date (if available)
        if ($date) {
            imagestring($image, 3, 80, $subtitleY + count($subtitleLines) * 18 + 15, $date, $gray);
        }

        // Bottom branding bar
        imagefilledrectangle($image, 40, $height - 90, $width - 40, $height - 40, $subtleGray);
        
        // Site name
        imagestring($image, 4, 80, $height - 75, 'great-x-attach.xyz', $accentCyan);
        
        // Author name
        imagestring($image, 3, 80, $height - 55, 'Benidictus Tri Wibowo | Cybersecurity & ICT Risk Professional', $gray);

        // Accent dots (bottom-right decoration)
        for ($dx = 0; $dx < 4; $dx++) {
            for ($dy = 0; $dy < 4; $dy++) {
                imagefilledellipse($image, $width - 120 + ($dx * 15), $height - 120 + ($dy * 15), 4, 4, $accentCyan);
            }
        }

        return $image;
    }

    private function wordWrap(string $text, int $maxChars): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) > $maxChars && $currentLine !== '') {
                $lines[] = trim($currentLine);
                $currentLine = $word;
            } else {
                $currentLine .= ($currentLine ? ' ' : '') . $word;
            }
        }
        
        if ($currentLine) {
            $lines[] = trim($currentLine);
        }

        // Max 4 lines
        return array_slice($lines, 0, 4);
    }
}
