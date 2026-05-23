## 2024-05-23 - Timing Attack Vulnerability in Token Verification
**Vulnerability:** Found a timing attack vulnerability in `CvDownloadController.php` where a sensitive authentication token (`CV_API_TOKEN`) was verified using a non-constant time comparison operator (`!==`).
**Learning:** Using standard comparison operators (`==`, `===`, `!=`, `!==`) for secrets allows attackers to measure the time it takes for the comparison to fail. Because these operators terminate upon the first mismatched character, attackers can guess a token character-by-character based on slightly longer response times.
**Prevention:** Always use PHP's `hash_equals()` for comparing sensitive strings, hashes, or tokens, as it guarantees a constant-time comparison. Additionally, ensure variables passed to `hash_equals()` are strictly strings, as PHP 8+ throws a TypeError if a null value is passed.
## 2024-05-28 - Path Traversal Vulnerability in File Read
**Vulnerability:** Found a Path Traversal vulnerability in `OgImageController.php` where route parameters (`$type` and `$slug`) were directly concatenated into a file path for `storage_path()`.
**Learning:** Directly using user inputs to construct file paths without strict allowlisting allows attackers to use sequences like `../` to access unauthorized files outside the intended directory.
**Prevention:** Always sanitize inputs used in file paths using a strict allowlist. For typical route parameters, `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)` provides robust defense.
