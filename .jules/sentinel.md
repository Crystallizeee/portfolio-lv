## 2025-02-14 - [Livewire Method Rate Limiting]
**Vulnerability:** Missing rate limiting on 2FA challenge component logic.
**Learning:** Livewire methods bypass standard route-level middleware, requiring manual implementation of rate limiting using `RateLimiter`.
**Prevention:** Always ensure any authentication or sensitive actions performed via Livewire component methods are explicitly protected using the `RateLimiter` facade.
## 2026-05-20 - [Path Traversal in OgImageController]
**Vulnerability:** Path Traversal vulnerability found in `OgImageController`.
**Learning:** User inputs from route parameters (`$type` and `$slug`) were directly concatenated into file paths without sanitization.
**Prevention:** Always sanitize user inputs used for file path construction with a strict allowlist regex (e.g., `preg_replace('/[^a-zA-Z0-9_-]/', '', $input)`) to prevent directory traversal.
