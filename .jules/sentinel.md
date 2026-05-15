## 2025-05-15 - Missing Rate Limiting on Livewire Actions
**Vulnerability:** The `AdminLogin` Livewire component did not implement rate limiting on the `login()` method, allowing brute-force password guessing attacks without being throttled.
**Learning:** In Livewire applications, component actions (like `wire:submit="login"`) execute via AJAX and completely bypass traditional Laravel route-level middleware (such as `throttle`).
**Prevention:** Always manually implement rate limiting using the `Illuminate\Support\Facades\RateLimiter` facade inside Livewire component methods that perform authentication, sensitive operations, or are susceptible to abuse.
