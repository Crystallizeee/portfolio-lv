## 2025-02-18 - Cache AI System Prompt Generation
**Learning:** Generating the system prompt dynamically inside the `ChatbotController` for every single request causes a significant bottleneck. Gathering portfolio data dynamically effectively executes 6+ queries against the database to fetch `User`, `Experience`, `Skill`, `Project`, `Certificate`, `Language`, and `Education` models on every single `/api/chatbot` interaction.
**Action:** When building extensive contextual prompts from static database records, encapsulate the aggregation logic inside `Cache::remember()` to ensure that the heavy DB queries are bypassed for the duration of the cache, ensuring prompt generation responds immediately.
## $(date +%Y-%m-%d) - Defer Synchronous Database Writes in Middleware
**Learning:** In Laravel 11.x, performing synchronous database inserts (like tracking page visits in middleware) blocks the HTTP response, increasing TTFB and reducing perceived performance.
**Action:** Use Laravel's `defer()` helper to push non-critical, synchronous database operations to a background task that executes after the response is sent to the client, improving page load times.
## 2026-06-03 - Defer Synchronous Database Writes in Middleware
**Learning:** In Laravel 11.x+, performing synchronous database inserts (like tracking page visits in middleware) blocks the HTTP response, increasing TTFB and reducing perceived performance. Always capture context state (like `now()` or request IP) before deferring, as the request might be terminated when the closure executes.
**Action:** Use Laravel's `defer()` helper to push non-critical, synchronous database operations to a background task that executes after the response is sent to the client, capturing context state before deferring.

## 2025-02-12 - User-Agent Parsing Loop Bottleneck
**Learning:** Instantiating `Jenssegers\Agent\Agent` and parsing hundreds of User-Agent strings (e.g., inside an analytics dashboard loop) on every synchronous request is highly CPU intensive and severely degrades load times.
**Action:** Always wrap heavy synchronous data processing loops—especially those utilizing regex-heavy string parsing—inside a `Cache::remember` block, even for admin-facing dashboards where real-time accuracy can be traded for performance.
## 2026-06-18 - Cache Global SEO Metadata Query
**Learning:** Performing a database query inside the `boot()` method of a Service Provider (e.g., `AppServiceProvider`) forces that query to execute synchronously on every single HTTP request (including all API, Web, and Livewire endpoints).
**Action:** Always wrap heavy or global data queries placed in Service Providers or heavily-included layout components with `Cache::remember()` to avoid creating a severe N+1-like global database bottleneck.
