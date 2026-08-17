<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Normalizer;

/**
 * AI Security Service
 *
 * Layered defenses against:
 *  1. Direct & Indirect Prompt Injection
 *  2. Unicode & Zero-Width Obfuscation / Smuggling
 *  3. Insecure Output Handling (XSS / Zero-Click Markdown Image Exfiltration)
 *  4. Training Data Extraction / System Prompt Leakage
 *  5. DoS via Adversarial Resource Consumption (Rate Limiting, Entropy, Circuit Breaker)
 *  6. Data Poisoning (Sanitize DB context before prompt injection)
 */
class AiSecurityService
{
    // -------------------------------------------------------------------------
    // 1. PROMPT INJECTION DETECTION
    // -------------------------------------------------------------------------

    private const INJECTION_PATTERNS = [
        // Classic override attempts (flexible optional qualifiers)
        '/ignore\s+(all\s+)?(previous|prior|above|earlier|initial)?\s*(instructions?|rules?|prompts?|context|constraints?)/i',
        '/disregard\s+(all\s+)?(previous|prior|above|earlier)?\s*(instructions?|rules?)/i',
        '/forget\s+(everything|all|your|the)?\s*(above|rules?|instructions?|context|training)/i',

        // Role / persona hijacking
        '/you\s+are\s+now\s+(a|an|the)\s+/i',
        '/pretend\s+(you\s+are|to\s+be)\s+/i',
        '/act\s+as\s+(if|a|an|though)\s+/i',
        '/roleplay\s+as\s+/i',
        '/from\s+now\s+on[\s,]+you\s+(are|will|must)/i',

        // New instruction injection
        '/new\s+(instructions?|system\s+prompt|rules?|directive|objective)\s*:/i',
        '/updated\s+instructions?\s*:/i',
        '/\bsystem\s*:\s*\n/i',
        '/\[system\]/i',
        '/\[\s*INST\s*\]/i',
        '/<<\s*SYS\s*>>/i',
        '/<\|im_start\|>/i',
        '/<\|system\|>/i',

        // System prompt extraction (flexible optional qualifiers)
        '/reveal\s+(your|the)?\s*(system\s+prompt|instructions?|rules?|training)/i',
        '/print\s+(your|the)?\s*(system\s+prompt|instructions?|rules?)/i',
        '/repeat\s+(the\s+)?(above|previous|your)?\s*(instructions?|prompt|text)/i',
        '/what\s+(are|is|were)\s+(your|the)?\s*(exact\s+)?(system\s+prompt|instructions?|rules?)/i',
        '/show\s+me\s+(your|the)?\s*(system\s+prompt|instructions?|original\s+prompt)/i',
        '/translate\s+(the\s+)?(above|system\s+prompt|instructions?)\s+to\s+/i',
        '/summarize\s+(the\s+)?(above|system\s+prompt|initial\s+instructions?)/i',
        '/output\s+(your\s+)?(system\s+prompt|initialization|configuration)/i',

        // Jailbreak keywords
        '/\b(DAN|DUDE|AIM|STAN|KEVIN|JAILBREAK)\b/i',
        '/developer\s+mode/i',
        '/unrestricted\s+mode/i',
        '/bypass\s+(your\s+)?(safety|filters?|restrictions?|rules?)/i',
    ];

    /**
     * Normalize text (strip zero-width characters, homoglyphs, and collapse spaces)
     * before running security regex checks.
     */
    public function normalizeForInspection(string $input): string
    {
        // 1. Strip Zero-Width and invisible characters (ZWSP, ZWNJ, ZWJ, BOM, Soft-Hyphen)
        $clean = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}\x{2060}\x{180E}]/u', '', $input);

        // 2. Unicode NFKC Normalization (converts full-width/homoglyph characters to standard ASCII/Latin)
        if (class_exists('Normalizer')) {
            $normalized = Normalizer::normalize($clean, Normalizer::FORM_KC);
            if ($normalized !== false) {
                $clean = $normalized;
            }
        }

        // 3. Collapse multiple spaces
        $clean = preg_replace('/\s+/', ' ', $clean);

        return $clean;
    }

    /**
     * Returns true if the input matches any prompt injection or jailbreak pattern.
     */
    public function isPromptInjection(string $input): bool
    {
        $inspectedText = $this->normalizeForInspection($input);

        foreach (self::INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $inspectedText)) {
                Log::warning('AI Security: Prompt injection attempt detected', [
                    'pattern' => $pattern,
                    'input'   => mb_substr($input, 0, 200),
                ]);
                return true;
            }
        }

        // Detect Base64 encoded payload smuggling
        if ($this->hasBase64Payload($inspectedText)) {
            Log::warning('AI Security: Possible Base64 encoded injection', [
                'input' => mb_substr($input, 0, 100),
            ]);
            return true;
        }

        return false;
    }

    private function hasBase64Payload(string $input): bool
    {
        if (preg_match('/(?:[A-Za-z0-9+\/]{4}){8,}(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?/', $input, $matches)) {
            $decoded = @base64_decode($matches[0], true);
            if ($decoded !== false && mb_check_encoding($decoded, 'UTF-8')) {
                $normalizedDecoded = $this->normalizeForInspection($decoded);
                foreach (self::INJECTION_PATTERNS as $pattern) {
                    if (preg_match($pattern, $normalizedDecoded)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    // -------------------------------------------------------------------------
    // 2. INPUT SANITIZATION
    // -------------------------------------------------------------------------

    public function sanitizeInput(string $input): string
    {
        $input = str_replace("\0", '', $input);
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        $input = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}]/u', '', $input);
        $input = preg_replace('/\n{3,}/', "\n\n", $input);
        $input = strip_tags($input);
        $input = mb_substr(trim($input), 0, 500);

        return $input;
    }

    public function sanitizePromptData(string $data): string
    {
        $data = strip_tags($data);
        $data = str_replace("\0", '', $data);
        $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
        $data = preg_replace('/[ \t]{2,}/', ' ', $data);
        $data = preg_replace('/\n{3,}/', "\n\n", $data);
        $data = mb_substr(trim($data), 0, 300);

        return $data;
    }

    // -------------------------------------------------------------------------
    // 3. OUTPUT SANITIZATION (Insecure Output & Zero-Click Exfiltration)
    // -------------------------------------------------------------------------

    public function sanitizeOutput(string $output): string
    {
        // 1. Strip raw HTML tags
        $output = strip_tags($output);

        // 2. Neutralize all Markdown images ![]() to prevent Zero-Click data exfiltration
        $output = preg_replace('/!\[([^\]]*)\]\([^)]+\)/', '', $output);

        // 3. Neutralize dangerous link schemes (javascript:, data:, vbscript:, file:)
        $output = preg_replace('/\[([^\]]*)\]\s*\(\s*(javascript|data|vbscript|file|about):[^\)]*\)/i', '[$1](#)', $output);

        // 4. Neutralize dangerous HTML attributes
        $dangerousAttributes = '/\s+(on[a-z]+|formaction|style|background|srcdoc|contenteditable)\s*=/i';
        $output = preg_replace($dangerousAttributes, ' data-sanitized=', $output);

        // 5. Remove null bytes
        $output = str_replace("\0", '', $output);

        // 6. Hard cap length
        $output = mb_substr(trim($output), 0, 1500);

        return $output;
    }

    // -------------------------------------------------------------------------
    // 4. EXTRACTION DETECTION
    // -------------------------------------------------------------------------

    private const EXTRACTION_PATTERNS = [
        '/what\s+(information|data|context)\s+(do\s+you\s+have|were\s+you\s+given|was\s+provided)/i',
        '/what\s+do\s+you\s+know\s+about\s+(yourself|your\s+training|your\s+data)/i',
        '/(list|tell me|show)\s+(all|every)\s+(information|data)\s+(you|about|on)\s+/i',
        '/what\s+(is|are)\s+your\s+(exact\s+)?(rules?|constraints?|limitations?)\??/i',
        '/are\s+you\s+(allowed|able|permitted)\s+to\s+/i',
    ];

    public function isExtractionAttempt(string $input): bool
    {
        $clean = $this->normalizeForInspection($input);
        foreach (self::EXTRACTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $clean)) {
                Log::info('AI Security: Possible data extraction probe', [
                    'input' => mb_substr($input, 0, 200),
                ]);
                return true;
            }
        }
        return false;
    }

    // -------------------------------------------------------------------------
    // 5. DoS / ADVERSARIAL RESOURCE CONSUMPTION
    // -------------------------------------------------------------------------

    public function checkCircuitBreaker(string $ip, bool $failed = false): bool
    {
        $failKey   = "ai_cb_fail:{$ip}";
        $blockKey  = "ai_cb_block:{$ip}";
        $maxFails  = (int) config('services.ai_security.max_fails', 5);
        $windowSec = 120;
        $blockSec  = (int) config('services.ai_security.block_seconds', 300);

        if (Cache::has($blockKey)) {
            return true;
        }

        if ($failed) {
            $fails = (int) Cache::get($failKey, 0) + 1;
            Cache::put($failKey, $fails, $windowSec);

            if ($fails >= $maxFails) {
                Cache::put($blockKey, true, $blockSec);
                Log::warning('AI Security: Circuit breaker tripped', ['ip' => $ip, 'failures' => $fails]);
                return true;
            }
        } else {
            Cache::forget($failKey);
        }

        return false;
    }

    public function isTooComplex(string $input): bool
    {
        $wordCount = str_word_count($input);
        if ($wordCount > 120) {
            Log::warning('AI Security: Input too complex (word count)', ['words' => $wordCount]);
            return true;
        }

        $chars  = count_chars($input, 1);
        $len    = strlen($input);
        $unique = count($chars);
        if ($len > 100 && $unique > 70) {
            Log::warning('AI Security: High entropy input', ['unique_chars' => $unique, 'length' => $len]);
            return true;
        }

        return false;
    }
}
