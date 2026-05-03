<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * AI Security Service
 *
 * Provides layered defenses against:
 *  1. Indirect Prompt Injection
 *  2. Insecure Output Handling (XSS / RCE via AI output)
 *  3. Training Data Extraction / System Prompt Leakage
 *  4. DoS via Adversarial Resource Consumption
 *  5. Data Poisoning (sanitize DB data before prompt injection)
 */
class AiSecurityService
{
    // -------------------------------------------------------------------------
    // 1. PROMPT INJECTION DETECTION
    // -------------------------------------------------------------------------

    /**
     * Patterns that are hallmarks of prompt injection / jailbreak attempts.
     * Each pattern targets a known attack surface.
     */
    private const INJECTION_PATTERNS = [
        // Classic override attempts
        '/ignore\s+(all\s+)?(previous|prior|above|earlier|initial)\s+(instructions?|rules?|prompts?|context|constraints?)/i',
        '/disregard\s+(all\s+)?(previous|prior|above|earlier)\s+(instructions?|rules?)/i',
        '/forget\s+(everything|all|your|the)\s+(above|rules?|instructions?|context|training)/i',

        // Role / persona hijacking
        '/you\s+are\s+now\s+(a|an|the)\s+/i',
        '/pretend\s+(you\s+are|to\s+be)\s+/i',
        '/act\s+as\s+(if|a|an|though)\s+/i',
        '/roleplay\s+as\s+/i',
        '/from\s+now\s+on[\s,]+you\s+(are|will|must)/i',

        // New instruction injection
        '/new\s+(instructions?|system\s+prompt|rules?|directive|objective)\s*:/i',
        '/updated\s+instructions?\s*:/i',
        '/\bsystem\s*:\s*\n/i',            // SYSTEM: on its own line
        '/\[system\]/i',                    // [SYSTEM] tag
        '/\[\s*INST\s*\]/i',               // LLaMA instruction tokens
        '/<<\s*SYS\s*>>/i',               // LLaMA system tokens
        '/<\|im_start\|>/i',              // ChatML format
        '/<\|system\|>/i',               // Mistral format

        // System prompt extraction
        '/reveal\s+(your|the)\s+(system\s+prompt|instructions?|rules?|training)/i',
        '/print\s+(your|the)\s+(system\s+prompt|instructions?|rules?)/i',
        '/repeat\s+(the\s+)?(above|previous|your)\s+(instructions?|prompt|text)/i',
        '/what\s+(are|is|were)\s+(your|the)\s+(exact\s+)?(system\s+prompt|instructions?|rules?)/i',
        '/show\s+me\s+(your|the)\s+(system\s+prompt|instructions?|original\s+prompt)/i',
        '/translate\s+(the\s+)?(above|system\s+prompt|instructions?)\s+to\s+/i',
        '/summarize\s+(the\s+)?(above|system\s+prompt|initial\s+instructions?)/i',
        '/output\s+(your\s+)?(system\s+prompt|initialization|configuration)/i',

        // Jailbreak keywords
        '/\b(DAN|DUDE|AIM|STAN|KEVIN|JAILBREAK)\b/i',
        '/developer\s+mode/i',
        '/unrestricted\s+mode/i',
        '/bypass\s+(your\s+)?(safety|filters?|restrictions?|rules?)/i',
        '/without\s+(any\s+)?(restrictions?|limitations?|filters?|rules?|safety)/i',

        // Encoding / obfuscation tricks
        '/base64\s*(encode|decode|:)/i',
        '/rot13\s*(encode|decode|:)/i',
        '/hex\s*(encode|decode|:)/i',

        // Context separator injection (trying to end system block and start new one)
        '/={3,}|---\s*(SYSTEM|INSTRUCTION|RULE|OVERRIDE)/i',
    ];

    /**
     * Returns true if the input looks like a prompt injection attempt.
     */
    public function isPromptInjection(string $input): bool
    {
        foreach (self::INJECTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $input)) {
                Log::warning('AI Security: Prompt injection attempt detected', [
                    'pattern' => $pattern,
                    'input'   => mb_substr($input, 0, 200),
                ]);
                return true;
            }
        }

        // Repetition DoS: single char repeated 40+ times
        if (preg_match('/(.)\1{39,}/', $input)) {
            Log::warning('AI Security: Repetitive character DoS attempt', [
                'input' => mb_substr($input, 0, 100),
            ]);
            return true;
        }

        // Long Base64 block (possible hidden instructions)
        if (preg_match('/[A-Za-z0-9+\/]{80,}={0,2}/', $input)) {
            Log::warning('AI Security: Possible Base64 encoded injection', [
                'input' => mb_substr($input, 0, 100),
            ]);
            return true;
        }

        return false;
    }

    // -------------------------------------------------------------------------
    // 2. INPUT SANITIZATION (Defense-in-depth before sending to LLM)
    // -------------------------------------------------------------------------

    /**
     * Sanitize raw user input before building the prompt.
     * Strips control chars, normalizes whitespace, removes null bytes.
     */
    public function sanitizeInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);

        // Remove non-printable control characters except newlines/tabs
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Normalize excessive whitespace (more than 3 consecutive newlines → 2)
        $input = preg_replace('/\n{3,}/', "\n\n", $input);

        // Strip HTML/script tags (prevent HTML injection into prompt)
        $input = strip_tags($input);

        // Truncate to hard limit (extra safety beyond Laravel validation)
        $input = mb_substr(trim($input), 0, 500);

        return $input;
    }

    /**
     * Sanitize data sourced from the database before injecting into the prompt.
     * Prevents a compromised portfolio DB field from containing hidden instructions.
     */
    public function sanitizePromptData(string $data): string
    {
        // Strip tags
        $data = strip_tags($data);

        // Remove null bytes and control chars
        $data = str_replace("\0", '', $data);
        $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);

        // Collapse consecutive whitespace
        $data = preg_replace('/[ \t]{2,}/', ' ', $data);
        $data = preg_replace('/\n{3,}/', "\n\n", $data);

        // Hard cap DB data per-field to 300 chars (prevent context stuffing via DB)
        $data = mb_substr(trim($data), 0, 300);

        return $data;
    }

    // -------------------------------------------------------------------------
    // 3. OUTPUT SANITIZATION (Insecure Output Handling)
    // -------------------------------------------------------------------------

    /**
     * Sanitize AI-generated text before returning it to the client.
     * Prevents XSS, RCE-via-markdown, or accidental HTML execution.
     */
    public function sanitizeOutput(string $output): string
    {
        // Strip any HTML tags the model might have hallucinated
        $output = strip_tags($output);

        // Remove null bytes
        $output = str_replace("\0", '', $output);

        // Remove potentially dangerous markdown patterns (e.g., [text](javascript:...))
        $output = preg_replace('/\[([^\]]*)\]\s*\(\s*javascript:/i', '[$1](', $output);

        // Remove HTML entities that could smuggle tags if later decoded
        $output = html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $output = strip_tags($output); // Second pass after decoding

        // Hard cap output length at 1500 chars
        $output = mb_substr(trim($output), 0, 1500);

        return $output;
    }

    // -------------------------------------------------------------------------
    // 4. TRAINING DATA / SYSTEM PROMPT EXTRACTION DETECTION
    // -------------------------------------------------------------------------

    /**
     * Patterns suggesting the user wants to extract internal system data.
     * These are softer signals — checked separately so we can give a
     * tailored response rather than a generic block.
     */
    private const EXTRACTION_PATTERNS = [
        '/what\s+(information|data|context)\s+(do\s+you\s+have|were\s+you\s+given|was\s+provided)/i',
        '/what\s+do\s+you\s+know\s+about\s+(yourself|your\s+training|your\s+data)/i',
        '/(list|tell me|show)\s+(all|every)\s+(information|data)\s+(you|about|on)\s+/i',
        '/what\s+(is|are)\s+your\s+(exact\s+)?(rules?|constraints?|limitations?)\??/i',
        '/are\s+you\s+(allowed|able|permitted)\s+to\s+/i',
    ];

    /**
     * Detects attempts to probe the model's internal knowledge or constraints.
     * Returns true if extraction attempt detected.
     */
    public function isExtractionAttempt(string $input): bool
    {
        foreach (self::EXTRACTION_PATTERNS as $pattern) {
            if (preg_match($pattern, $input)) {
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

    /**
     * Per-IP circuit breaker: after N consecutive API failures in a time window,
     * temporarily block that IP from reaching the LLM backend.
     *
     * @param  string  $ip
     * @param  bool    $failed  true = record a failure; false = record a success
     * @return bool             true = circuit is OPEN (block request)
     */
    public function checkCircuitBreaker(string $ip, bool $failed = false): bool
    {
        $key       = "ai_cb:{$ip}";
        $failKey   = "ai_cb_fail:{$ip}";
        $blockKey  = "ai_cb_block:{$ip}";
        $maxFails  = (int) env('AI_CIRCUIT_BREAKER_MAX_FAILS', 5);
        $windowSec = 120; // failure window (2 min)
        $blockSec  = (int) env('AI_CIRCUIT_BREAKER_BLOCK_SECONDS', 300);

        // Already blocked?
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
            // Success: reset failure counter
            Cache::forget($failKey);
        }

        return false;
    }

    /**
     * Complexity scoring: estimates prompt "weight" to pre-reject unusually
     * expensive inputs (adversarial resource consumption).
     * Returns true if the request is too complex/large.
     */
    public function isTooComplex(string $input): bool
    {
        // Word count check (very long single-sentence prompts often signal abuse)
        $wordCount = str_word_count($input);
        if ($wordCount > 120) {
            Log::warning('AI Security: Input too complex (word count)', ['words' => $wordCount]);
            return true;
        }

        // Unique character entropy — very high entropy = obfuscated/encoded payload
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
