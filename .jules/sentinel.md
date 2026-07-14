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
## 2025-03-01 - Missing Ownership Check on Draft Posts
**Vulnerability:** In `BlogController::show`, authenticated users could bypass authorization to view any other user's draft posts due to an missing `user_id` constraint when checking for `auth()->check()`.
**Learning:** Checking if a user is authenticated (`auth()->check()`) is not the same as checking if the authenticated user is authorized to view a specific resource.
**Prevention:** Always scope visibility constraints for user-specific data using `where('user_id', auth()->id())` to prevent IDOR.
## 2025-02-12 - Apply rate limiting to heavy API endpoints
**Vulnerability:** Resource exhaustion via unthrottled API endpoint
**Learning:** Even if an API endpoint requires an authorization token, it can still be vulnerable to resource exhaustion (DoS) if it performs heavy operations (like PDF generation in `api/cv/download`).
**Prevention:** Always apply rate limiting middleware (e.g., `throttle:10,1`) to resource-intensive routes, even when authenticated.
## 2025-05-29 - Missing Rate Limiting on External API Calls
**Vulnerability:** The `generate` method in the `AiCoverLetter` Livewire component lacked rate limiting while communicating with an external AI API (Ollama/OpenAI), exposing the application to Denial of Service (DoS), API quota exhaustion, and Denial of Wallet (DoW) attacks.
**Learning:** Livewire methods that perform heavy computational tasks or invoke third-party APIs can be rapidly triggered by users. Without rate limits, an attacker can continuously send requests, exhausting resources and racking up costs.
**Prevention:** Always implement explicit rate limiting using Laravel's `RateLimiter` facade on Livewire methods that trigger resource-intensive tasks or external API calls. Ensure an appropriate throttle key (e.g., using `auth()->id()`) and reasonable limits (e.g., 5 attempts) are configured.

## 2025-05-29 - Rate Limit Bypass via Honeypot Trigger
**Vulnerability:** In `app/Livewire/PostComments.php`, the honeypot check (designed to catch bots filling hidden fields) executed before the rate limiter. If a bot triggered the honeypot, the system immediately inserted a comment into the database and returned early, bypassing the rate limiter entirely. This could allow bots to flood the database with spam comments, causing Denial of Service (DoS) and database exhaustion.
**Learning:** Security controls like rate limiting must always evaluate the request before specific business logic or early returns (like honeypots) handle it, otherwise the early returns provide an explicit bypass to those security controls.
**Prevention:** Always place rate limiting and validation logic at the very beginning of a function that accepts external input, ensuring all requests (even those flagged as malicious by honeypots) are limited.
## 2025-05-29 - Missing Rate Limiting on External API Calls in Admin Components
**Vulnerability:** The `generateSeoAndTags` method in the `ManagePosts` Livewire component lacked rate limiting while communicating with an external AI API (Ollama/OpenAI), exposing the application to Denial of Service (DoS), API quota exhaustion, and Denial of Wallet (DoW) attacks.
**Learning:** Livewire methods that perform heavy computational tasks or invoke third-party APIs can be rapidly triggered by authenticated users. Without rate limits, an attacker can continuously send requests, exhausting resources and racking up costs.
**Prevention:** Always implement explicit rate limiting using Laravel's `RateLimiter` facade on Livewire methods that trigger resource-intensive tasks or external API calls. Ensure an appropriate throttle key (e.g., using `auth()->id()`) and reasonable limits (e.g., 5 attempts) are configured.
## 2025-05-29 - Missing Rate Limiting on Internal Heavy Processing Tasks (PDF Generation)
**Vulnerability:** The `generatePdf` method in the `CvGenerator` Livewire component lacked rate limiting while performing computationally expensive PDF generation via `Pdf::loadHtml()`. This exposed the application to Denial of Service (DoS) and server resource exhaustion.
**Learning:** Livewire methods that perform heavy computational tasks on the server (like PDF generation or complex image processing) can be rapidly triggered by authenticated users. Without rate limits, an attacker can continuously send requests, exhausting CPU and memory resources.
**Prevention:** Always implement explicit rate limiting using Laravel's `RateLimiter` facade on Livewire methods that trigger resource-intensive processing. Ensure an appropriate throttle key (e.g., using `auth()->id()`) and reasonable limits are configured, and return a user-friendly error message via `$this->addError()`.
## 2025-05-29 - Stored XSS via Unrestricted Image Uploads (SVG)
**Vulnerability:** Several file upload endpoints (`ManageCertificates`, `ManagePosts`, `ManageProjects`, `PostAttachmentController`) used Laravel's generic `image` validation rule without explicit MIME type restrictions. This allowed the upload of SVG files, which can contain executable JavaScript and lead to Stored Cross-Site Scripting (XSS) if rendered back to users directly in the browser.
**Learning:** Laravel's `image` validation rule allows `jpeg, png, gif, bmp, svg, webp`. SVGs are XML-based vector graphics that can embed `<script>` tags. If an attacker uploads a malicious SVG and the application renders it without sanitization or restrictive Content-Security-Policy (CSP), the script will execute in the context of the user's browser viewing the image.
**Prevention:** When validating image uploads where the application does not strictly require or sanitize SVGs, prefer explicit `mimes:jpeg,png,jpg,webp` restrictions instead of the generic `image` rule to prevent Stored XSS vulnerabilities associated with allowed SVG files.

## 2025-05-29 - Command Injection in Process::run
**Vulnerability:** Shell Command Injection vulnerability found in `app/Console/Commands/SyncCybersecProfiles.php` where user-controlled input (`$profile->username`) was concatenated directly into a command string passed to `Process::run("python {$scriptPath} --username {$profile->username}")`. An attacker could inject arbitrary shell commands via the username field.
**Learning:** Concatenating user inputs directly into shell command strings is inherently dangerous because shell metacharacters (e.g., `;`, `|`, `&&`) can be used to break out of the intended command and execute arbitrary code.
**Prevention:** Always use the array syntax for executing system commands (e.g., `Process::run(['python', $scriptPath, '--username', $profile->username])`). This ensures parameters are passed directly as arguments to the executable without being interpreted by the shell, automatically escaping them.
## 2025-05-29 - Missing Rate Limiting on JSON Backup Import Endpoint
**Vulnerability:** The `import` method in `BackupController` processed large uploaded JSON backup files and executed bulk database transactions to restore multiple models without any rate limiting. An attacker or a compromised authenticated account could repeatedly upload large files, leading to severe resource exhaustion, database locking, and Denial of Service (DoS).
**Learning:** Endpoints handling file uploads and heavy, transactional database operations (like full profile restorations) are prime targets for resource exhaustion attacks. The fact that the endpoint requires authentication is insufficient defense against account compromise or automated scripts triggered by a malicious insider.
**Prevention:** Always implement explicit rate limiting (using Laravel's `RateLimiter` facade) on endpoints that handle bulk data imports, file processing, or heavy transactions. Ensure a strict limit (e.g., 5 attempts per user per minute) is enforced before processing the uploaded file.
## 2026-07-07 - [Missing Rate Limiting on 2FA Confirmation]
**Vulnerability:** The `confirmTwoFactor` method in `ProfileSettings.php` did not have rate limiting.
**Learning:** This could allow an attacker to brute-force the 6-digit OTP code when a user is setting up 2FA.
**Prevention:** Always use Laravel's `RateLimiter` facade to limit attempts on endpoints that verify codes or passwords.

## 2026-07-12 - [Rate Limiter Bypass due to Early Validation]
**Vulnerability:** In multiple Livewire components (`AdminLogin.php`, `PostComments.php`), the `$this->validate()` call was placed *before* the rate limiting block (`RateLimiter::tooManyAttempts`).
**Learning:** This allowed an attacker to bypass rate limiting completely by sending invalid payloads. Since `validate()` throws an exception on failure, the code execution stops before it hits the rate limiter check, meaning the rate limit token is never consumed. An attacker could flood the server with invalid requests without ever being rate limited.
**Prevention:** Always place rate limiting logic at the absolute beginning of the method, *before* any validation, business logic, or early returns, to ensure all requests (valid or invalid) are throttled appropriately.
## 2026-07-12 - [Rate Limiter Bypass due to Early Validation]
**Vulnerability:** In multiple Livewire components (`AdminLogin.php`, `TwoFactorChallenge.php`, `ProfileSettings.php`, `CvGenerator.php`), the `$this->validate()` call (or other early returns) was placed *before* the rate limiting block (`RateLimiter::hit`).
**Learning:** This allowed an attacker to bypass rate limiting completely by sending invalid payloads. Since `validate()` throws an exception on failure (or an early return triggers), the code execution stops before it hits the rate limiter check, meaning the rate limit token is never consumed. An attacker could flood the server with invalid requests without ever being rate limited.
**Prevention:** Always place rate limiting logic (`RateLimiter::hit()`) at the absolute beginning of the method, *before* any validation, business logic, or early returns, to ensure all requests (valid or invalid) are throttled appropriately.
