## 2024-05-18 - [Validation-Based Resource Exhaustion / DoS]
**Vulnerability:** Circuit Breaker rate limit check was placed after validation logic.
**Learning:** An attacker could spam payloads larger than the validation limits (e.g., > 500 chars). Validation triggers an exception, aborting the request before the rate limiter triggers. This consumes server resources repeatedly.
**Prevention:** Always place security controls like circuit breakers and rate limits at the very beginning of the controller or method, before any validation logic.

## 2025-03-08 - Sanitize LLM Inputs and Outputs
**Vulnerability:** Untrusted user input could lead to prompt injection when building LLM prompts, and unsanitized LLM output could lead to XSS.
**Learning:** External AI models (LLMs) output should always be treated as untrusted data, and inputs to LLMs should be sanitized.
**Prevention:** Use `app(\App\Services\AiSecurityService::class)->sanitizeInput()` and `app(\App\Services\AiSecurityService::class)->sanitizeOutput()` for inputs and outputs respectively.
