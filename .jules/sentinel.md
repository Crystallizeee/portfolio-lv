## 2024-05-23 - Timing Attack Vulnerability in Token Verification
**Vulnerability:** Found a timing attack vulnerability in `CvDownloadController.php` where a sensitive authentication token (`CV_API_TOKEN`) was verified using a non-constant time comparison operator (`!==`).
**Learning:** Using standard comparison operators (`==`, `===`, `!=`, `!==`) for secrets allows attackers to measure the time it takes for the comparison to fail. Because these operators terminate upon the first mismatched character, attackers can guess a token character-by-character based on slightly longer response times.
**Prevention:** Always use PHP's `hash_equals()` for comparing sensitive strings, hashes, or tokens, as it guarantees a constant-time comparison. Additionally, ensure variables passed to `hash_equals()` are strictly strings, as PHP 8+ throws a TypeError if a null value is passed.

## 2025-02-12 - Path Traversal Vulnerability in OgImageController
**Vulnerability:** The `$type` and `$slug` route parameters in `OgImageController` were used directly in `storage_path()` to construct file paths for caching and returning generated images. This could allow an attacker to read or write arbitrary files on the system using `../` directory traversal payloads.
**Learning:** Route parameters and user inputs should never be trusted when constructing file paths, even if they are expected to match certain patterns elsewhere. If not sanitized before file system operations, they bypass typical route constraints and directly interface with the OS file system.
**Prevention:** Always sanitize user input intended for file paths using a strict allowlist approach. For expected alphanumeric slugs or types, a regex like `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)` ensures only safe characters are used to construct the final path.
## 2025-02-28 - XSS in AlpineJS x-html directive
**Vulnerability:** A Cross-Site Scripting (XSS) vulnerability was found where `msg.text` was being passed directly to `x-html` without any sanitization in `resources/views/layouts/app.blade.php` and `resources/views/components/layouts/app.blade.php`.
**Learning:** `x-html` allows raw HTML to be executed. If user input like `msg.text` (which comes directly from the user chat message payload) is unsanitized, it could be used for XSS.
**Prevention:** Always use `DOMPurify.sanitize()` or another sanitization mechanism when using `x-html` with dynamic input, even if it seems safe or is just displaying user messages.
