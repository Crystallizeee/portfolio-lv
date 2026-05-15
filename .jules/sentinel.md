## 2024-05-15 - Missing Rate Limiting on Livewire Component Authentication Endpoints
**Vulnerability:** The AdminLogin component was missing rate limiting, leaving the admin login vulnerable to brute-force attacks.
**Learning:** Livewire actions bypass standard HTTP route middleware (e.g. `throttle:5,1`). If authentication logic is moved to Livewire components, standard middleware like RateLimiting must be manually implemented inside the component methods (e.g., using Laravel's RateLimiter facade).
**Prevention:** Implement rate limiting manually inside Livewire components that handle authentication or sensitive actions by using Laravel's RateLimiter facade.
