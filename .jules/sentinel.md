## 2024-05-23 - Timing Attack Vulnerability in Token Verification
**Vulnerability:** Found a timing attack vulnerability in `CvDownloadController.php` where a sensitive authentication token (`CV_API_TOKEN`) was verified using a non-constant time comparison operator (`!==`).
**Learning:** Using standard comparison operators (`==`, `===`, `!=`, `!==`) for secrets allows attackers to measure the time it takes for the comparison to fail. Because these operators terminate upon the first mismatched character, attackers can guess a token character-by-character based on slightly longer response times.
**Prevention:** Always use PHP's `hash_equals()` for comparing sensitive strings, hashes, or tokens, as it guarantees a constant-time comparison. Additionally, ensure variables passed to `hash_equals()` are strictly strings, as PHP 8+ throws a TypeError if a null value is passed.

## 2024-05-23 - Path Traversal Vulnerability in OgImageController
**Vulnerability:** Found a Path Traversal vulnerability in `OgImageController.php` where user inputs (`$type` and `$slug`) from route parameters were used to directly construct a file path (`$cachePath = "og-images/{$type}_{$slug}.png"`) without validation or sanitization.
**Learning:** Using unsanitized inputs to interact with the file system allows attackers to read or write to unintended files by injecting path traversal sequences (e.g., `../`). This poses a critical security risk.
**Prevention:** Always validate and sanitize user inputs that are used in file paths or commands using strict allowlists (e.g., `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)`).
