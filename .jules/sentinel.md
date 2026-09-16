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

## 2025-10-24 - Rate Limiting on All Data-Mutating Methods
**Vulnerability:** The `updateProfile` method in `ProfileSettings.php` lacked rate limiting, while other methods in the same component were protected.
**Learning:** Assuming component-level protection when only some methods are protected leaves unprotected methods vulnerable to resource exhaustion or abuse.
**Prevention:** Consistently implement rate limiting on all data-mutating methods (e.g., `updateProfile`, `saveEducation`) before any validation logic.

## 2024-05-18 - Rate Limiting on All Data-Mutating Methods
**Vulnerability:** Several administrative Livewire components (`ManageCertificates`, `ManageExperiences`, `ManageLanguages`) lacked rate limiting on their `save()` methods, making them vulnerable to resource exhaustion or abuse.
**Learning:** Assuming component-level protection when only some methods are protected leaves unprotected methods vulnerable to resource exhaustion or abuse.
**Prevention:** Consistently implement rate limiting on all data-mutating methods before any validation logic.
## 2025-03-08 - Rate Limiting on File Uploads
**Vulnerability:** File upload methods in Livewire administrative components were missing rate limits before validation logic.
**Learning:** Placing rate limiting before validation in components that handle file uploads prevents validation-based DoS attacks, although it may accidentally penalize users for minor validation errors.
**Prevention:** Consider UX when placing rate limits, but prioritize preventing resource exhaustion for endpoints parsing large payloads.

## 2025-03-08 - Rate Limiting on All Data-Mutating Methods (ManageSkills)
**Vulnerability:** The `save` method in `ManageSkills` lacked rate limiting, making it vulnerable to resource exhaustion or abuse.
**Learning:** Assuming component-level protection when only some methods are protected leaves unprotected methods vulnerable to resource exhaustion or abuse.
**Prevention:** Consistently implement rate limiting on all data-mutating methods before any validation logic.

## 2024-05-18 - Removed insecure TLS verification bypass
**Vulnerability:** The application was skipping TLS certificate verification via `Http::withoutVerifying()` for Proxmox API calls, exposing it to Man-in-the-Middle (MitM) attacks.
**Learning:** Disabling SSL verification on external HTTP requests is insecure. It bypasses the integrity and confidentiality guarantees of TLS.
**Prevention:** Avoid using `withoutVerifying()` in production code. Ensure valid certificates are used or properly configure CA bundles if necessary.

## 2024-06-03 - Missing Rate Limiting on 2FA Management Actions
**Vulnerability:** The 2FA management methods (`enableTwoFactor`, `disableTwoFactor`, and `regenerateRecoveryCodes`) in the `ProfileSettings` Livewire component lacked rate limiting.
**Learning:** These sensitive endpoints should always be rate-limited to prevent abuse and resource exhaustion, especially when generating cryptographic values.
**Prevention:** Always apply rate limiting to endpoints that handle sensitive state transitions or cryptographic operations to prevent abuse.

## 2025-05-15 - Missing Rate Limiting on SEO Manager
**Vulnerability:** The `SeoManager` component lacked rate limiting on the `save` method.
**Learning:** Inconsistent application of rate limiting across admin components can leave isolated endpoints vulnerable to DoS attacks via resource exhaustion.
**Prevention:** Ensure all data-mutating endpoints, especially in Livewire components (which are easy to script), have appropriate rate limits applied based on the authenticated user ID.

## 2025-05-15 - Dependency version issues in composer.lock across different PHP versions
**Vulnerability:** Although not a direct vulnerability in the code, the `composer.lock` file was resolving versions of certain packages (like `symfony/clock` or `symfony/css-selector`) that required a higher PHP version (e.g., PHP 8.4) than the CI testing matrix supported (e.g., PHP 8.2 and 8.3). This causes automated pipelines and build environments to fail, creating operational disruption.
**Learning:** `composer update` operations can sometimes silently upgrade deeply-nested packages to versions requiring higher platform requirements if `--ignore-platform-reqs` is not used carefully or if the `composer.json` platform config is not strictly defined to the lowest supported version.
**Prevention:** In libraries or projects targeting multiple PHP versions (like `^8.2`), ensure `composer update` is run with `--ignore-platform-reqs` or consider setting `config.platform.php` in `composer.json` to the minimum supported version (e.g., `8.2.0`) so the lockfile always resolves compatible packages.
