## 2024-05-18 - [Validation-Based Resource Exhaustion / DoS]
**Vulnerability:** Circuit Breaker rate limit check was placed after validation logic.
**Learning:** An attacker could spam payloads larger than the validation limits (e.g., > 500 chars). Validation triggers an exception, aborting the request before the rate limiter triggers. This consumes server resources repeatedly.
**Prevention:** Always place security controls like circuit breakers and rate limits at the very beginning of the controller or method, before any validation logic.

## 2025-03-08 - Sanitize LLM Inputs and Outputs
**Vulnerability:** Untrusted user input could lead to prompt injection when building LLM prompts, and unsanitized LLM output could lead to XSS.
**Learning:** External AI models (LLMs) output should always be treated as untrusted data, and inputs to LLMs should be sanitized.
**Prevention:** Use `app(\App\Services\AiSecurityService::class)->sanitizeInput()` and `app(\App\Services\AiSecurityService::class)->sanitizeOutput()` for inputs and outputs respectively.

## 2025-03-08 - Rate Limiting Added to Avatar Upload
**Vulnerability:** The `updateAvatar` method in `ProfileSettings.php` lacked rate limiting, allowing an attacker to repeatedly upload 2MB image files. Since file uploads and image validation consume CPU and disk resources, this could lead to Denial of Service (DoS).
**Learning:** Unrestricted file upload endpoints, especially those involving image validation, are prime targets for resource exhaustion attacks.
**Prevention:** Always apply rate limiting to file upload endpoints, placing the rate limiter logic *before* the validation step to prevent attackers from using large, invalid payloads to bypass the rate limiter.
## 2025-02-15 - CSRF Vulnerability in Cache Clearance Endpoint
**Vulnerability:** The `/admin/clear-cache` endpoint was defined as a `GET` request (`Route::get`) and performed a state-modifying action (clearing the system cache via `Artisan::call('optimize:clear')`). This allowed for Cross-Site Request Forgery (CSRF).
**Learning:** Even internal admin tools that do not change sensitive data (like clearing caches) must be protected against CSRF, as they modify server state. A `GET` request is inherently vulnerable because it lacks CSRF token validation and can be triggered via simple embedded links or images on an attacker-controlled site.
**Prevention:** Always use `POST` (or `PUT`/`DELETE`) methods for routes that perform actions or change server state, and ensure corresponding UI elements use forms with `@csrf` tokens rather than anchor links.
