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

## 2025-03-08 - [CSRF Vulnerability on State-Modifying Endpoints]
**Vulnerability:** The `/admin/clear-cache` route, which invokes an Artisan command (`optimize:clear`) modifying the server state, was configured as a `Route::get`.
**Learning:** Any state-modifying action accessible via a GET request is vulnerable to Cross-Site Request Forgery (CSRF) attacks, as it lacks CSRF token validation and can be triggered by external sites simply loading the URL (e.g., via an image tag or a link).
**Prevention:** Ensure all routes that modify server state, perform sensitive actions, or invoke commands use `Route::post` (or PUT/PATCH/DELETE) and are accompanied by UI elements that use forms with the `@csrf` directive instead of simple anchor (`<a>`) links.
