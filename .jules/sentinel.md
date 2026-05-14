## 2024-05-14 - [Livewire Method Rate Limiting Bypass]
**Vulnerability:** Admin login endpoint lacked rate limiting for authentication attempts, making it susceptible to brute-force attacks.
**Learning:** Livewire component methods invoked from the frontend (like `wire:submit="login"`) bypass route-level middleware defined in `routes/web.php`. Even though a `throttle` middleware was applied to the `POST /login` route, it was ineffective against requests handled directly by the Livewire component.
**Prevention:** Always implement rate limiting explicitly within the Livewire component methods that handle sensitive actions, such as authentication, using Laravel's `RateLimiter` facade. Do not rely solely on route-level middleware for Livewire components.
