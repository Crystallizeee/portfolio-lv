## 2025-02-14 - [Livewire Method Rate Limiting]
**Vulnerability:** Missing rate limiting on 2FA challenge component logic.
**Learning:** Livewire methods bypass standard route-level middleware, requiring manual implementation of rate limiting using `RateLimiter`.
**Prevention:** Always ensure any authentication or sensitive actions performed via Livewire component methods are explicitly protected using the `RateLimiter` facade.
