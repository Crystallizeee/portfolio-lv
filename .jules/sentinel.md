## 2025-02-14 - [Livewire Method Rate Limiting]
**Vulnerability:** Missing rate limiting on 2FA challenge component logic.
**Learning:** Livewire methods bypass standard route-level middleware, requiring manual implementation of rate limiting using `RateLimiter`.
**Prevention:** Always ensure any authentication or sensitive actions performed via Livewire component methods are explicitly protected using the `RateLimiter` facade.
## 2025-02-14 - [Livewire Update Password Rate Limiting]
**Vulnerability:** Missing rate limiting on `updatePassword` component logic.
**Learning:** Livewire methods that verify passwords must have rate limiting manually implemented using `RateLimiter` to prevent brute force attacks on the password.
**Prevention:** Always explicitly use `RateLimiter` facade on Livewire methods that verify credentials or passwords.

## 2024-05-24 - Path Traversal in File Downloads/Reads
**Vulnerability:** User inputs (`$type` and `$slug`) from route parameters in `OgImageController` were concatenated directly into a file path via `storage_path()`.
**Learning:** Even though Laravel's routing provides some protection, raw inputs passed to file functions (`storage_path`, `file_exists`, `imagepng`) can still lead to path traversal if the inputs contain directory-traversal characters like `..`.
**Prevention:** Always sanitize route parameters and other user inputs using a strict allowlist regex (e.g., `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)`) before concatenating them into file paths.
