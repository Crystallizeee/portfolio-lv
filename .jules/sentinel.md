## 2024-05-23 - Timing Attack Vulnerability in Token Verification
**Vulnerability:** Found a timing attack vulnerability in `CvDownloadController.php` where a sensitive authentication token (`CV_API_TOKEN`) was verified using a non-constant time comparison operator (`!==`).
**Learning:** Using standard comparison operators (`==`, `===`, `!=`, `!==`) for secrets allows attackers to measure the time it takes for the comparison to fail. Because these operators terminate upon the first mismatched character, attackers can guess a token character-by-character based on slightly longer response times.
**Prevention:** Always use PHP's `hash_equals()` for comparing sensitive strings, hashes, or tokens, as it guarantees a constant-time comparison. Additionally, ensure variables passed to `hash_equals()` are strictly strings, as PHP 8+ throws a TypeError if a null value is passed.

## 2025-02-12 - Path Traversal Vulnerability in OgImageController
**Vulnerability:** The `$type` and `$slug` route parameters in `OgImageController` were used directly in `storage_path()` to construct file paths for caching and returning generated images. This could allow an attacker to read or write arbitrary files on the system using `../` directory traversal payloads.
**Learning:** Route parameters and user inputs should never be trusted when constructing file paths, even if they are expected to match certain patterns elsewhere. If not sanitized before file system operations, they bypass typical route constraints and directly interface with the OS file system.
**Prevention:** Always sanitize user input intended for file paths using a strict allowlist approach. For expected alphanumeric slugs or types, a regex like `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)` ensures only safe characters are used to construct the final path.

## 2024-05-27 - Livewire IDOR in Admin Components
**Vulnerability:** Insecure Direct Object References (IDOR) across `ManageLanguages`, `ManageCybersecProfiles`, and `CvGenerator` where `Model::find($id)` was used to fetch, update, and delete objects without verifying the owner.
**Learning:** Livewire components were blindly trusting user-supplied `$id` parameters for database lookups.
**Prevention:** Always scope Eloquent lookups with `where('user_id', Auth::id())->findOrFail($id)` to strictly enforce ownership boundaries, and prefer `findOrFail()` over `find()` to avoid 500 errors on null updates.

## 2024-05-24 - [IDOR in User Models]
**Vulnerability:** Insecure Direct Object Reference (IDOR) vulnerabilities where components performed `Model::findOrFail($id)` or `Model::findOrFail($commentId)` without verifying if the authenticated user owned the parent entity or the object itself. Found in `PostComments.php` and `ManageProfiles.php`.
**Learning:** For user-specific models or child models nested under user-specific models, calling `findOrFail()` with user input directly allows attackers to modify or delete objects belonging to other users. Laravel Eloquent queries must explicitly enforce authorization or scope.
**Prevention:** Always scope Eloquent queries to the authenticated user using `Model::where('user_id', Auth::id())->findOrFail($id)` or verify the ownership of parent entities using `whereHas('post', function($q) { $q->where('user_id', auth()->id()); })` for nested relationships.
## 2026-05-29 - [Fix XSS Vulnerability in Chatbot]
**Vulnerability:** User and bot chat inputs rendered using x-html without DOMPurify for user input and condition blocks.
**Learning:** Alpine.js x-html parses unescaped raw strings. Using a ternary conditional directly in x-html bindings requires wrapping the ENTIRE result in DOMPurify.sanitize(), not just parts of it.
**Prevention:** Ensure the entire returned expression of x-html directives handling dynamic text is enclosed in a sanitation function.
