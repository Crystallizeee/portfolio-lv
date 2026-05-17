## 2025-02-12 - Livewire Component Actions Bypass Route Middlewares
**Vulnerability:** The `AdminLogin` form lacked rate limiting despite being an authentication endpoint.
**Learning:** In Livewire applications, component actions (like `wire:submit="login"`) hit a generic Livewire update route rather than the traditional form POST route. Thus, rate limiting middleware applied to the initial page render route does not protect the action method itself.
**Prevention:** Always implement rate limiting logic manually within Livewire component methods (e.g., using Laravel's `RateLimiter` facade) for sensitive actions like authentication, rather than relying solely on route middleware.
