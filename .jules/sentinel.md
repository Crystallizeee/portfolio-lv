## 2025-02-14 - [Livewire Method Rate Limiting]
**Vulnerability:** Missing rate limiting on 2FA challenge component logic.
**Learning:** Livewire methods bypass standard route-level middleware, requiring manual implementation of rate limiting using `RateLimiter`.
**Prevention:** Always ensure any authentication or sensitive actions performed via Livewire component methods are explicitly protected using the `RateLimiter` facade.
## 2025-02-14 - [Livewire Update Password Rate Limiting]
**Vulnerability:** Missing rate limiting on `updatePassword` component logic.
**Learning:** Livewire methods that verify passwords must have rate limiting manually implemented using `RateLimiter` to prevent brute force attacks on the password.
**Prevention:** Always explicitly use `RateLimiter` facade on Livewire methods that verify credentials or passwords.
