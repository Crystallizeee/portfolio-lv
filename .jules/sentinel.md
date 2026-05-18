## 2024-05-18 - [Livewire Component Action Route Bypass]
**Vulnerability:** Livewire forms bypassed route-level rate limiting
**Learning:** Livewire form submissions bypass standard Laravel route middlewares, leaving them vulnerable to brute-force attacks if rate limiting isn't explicitly applied.
**Prevention:** Apply rate limiting using `RateLimiter` facade inside Livewire component actions instead of `routes/web.php`.
