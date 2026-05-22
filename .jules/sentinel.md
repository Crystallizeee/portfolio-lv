## 2025-02-14 - [Livewire Method Rate Limiting]
**Vulnerability:** Missing rate limiting on 2FA challenge component logic.
**Learning:** Livewire methods bypass standard route-level middleware, requiring manual implementation of rate limiting using `RateLimiter`.
**Prevention:** Always ensure any authentication or sensitive actions performed via Livewire component methods are explicitly protected using the `RateLimiter` facade.
## 2025-02-14 - [Livewire Update Password Rate Limiting]
**Vulnerability:** Missing rate limiting on `updatePassword` component logic.
**Learning:** Livewire methods that verify passwords must have rate limiting manually implemented using `RateLimiter` to prevent brute force attacks on the password.
**Prevention:** Always explicitly use `RateLimiter` facade on Livewire methods that verify credentials or passwords.
## 2026-05-22 - [Path Traversal in OgImageController]
**Vulnerability:** Path traversal via unsanitized user input in Open Graph image caching endpoint.
**Learning:** When using user input to construct local file paths, input sanitization is critical to prevent reading or writing files outside intended directories.
**Prevention:** Always use regex allowlists (e.g., ) to sanitize input parameters used in path construction.

## 2025-02-14 - [Path Traversal in OgImageController]
**Vulnerability:** Path traversal via unsanitized user input in Open Graph image caching endpoint.
**Learning:** When using user input to construct local file paths, input sanitization is critical to prevent reading or writing files outside intended directories.
**Prevention:** Always use regex allowlists (e.g., `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)`) to sanitize input parameters used in path construction.
