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

## 2025-03-08 - CSRF Vulnerability in Admin State Modifying Routes
**Vulnerability:** A route modifying server state (`/admin/clear-cache` invoking `optimize:clear`) was defined as a `GET` route and triggered via an anchor tag.
**Learning:** Any endpoint that changes server state (like clearing caches, deleting items, or triggering jobs) must not be accessible via `GET` requests, as this exposes the application to Cross-Site Request Forgery (CSRF) attacks.
**Prevention:** Always use `Route::post` (or `PUT`/`DELETE`) for state-modifying actions, and update the corresponding frontend UI to use `<form method="POST">` with the `@csrf` directive instead of simple links.

## 2025-03-08 - Rate Limiting Added to updateProfile
**Vulnerability:** The `updateProfile` method in `ProfileSettings.php` lacked rate limiting, allowing an attacker to repeatedly spam profile update requests. Because database interactions consume CPU and backend resources, doing so rapidly could lead to Denial of Service (DoS) via resource exhaustion.
**Learning:** Unrestricted state-modifying endpoints, even seemingly innocuous ones like profile updates, are targets for resource exhaustion attacks when no rate limiting is in place.
**Prevention:** Always apply rate limiting to endpoints that handle user inputs and perform actions before executing validation to protect resources from being exhausted.
