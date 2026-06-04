## 2025-02-18 - Cache AI System Prompt Generation
**Learning:** Generating the system prompt dynamically inside the `ChatbotController` for every single request causes a significant bottleneck. Gathering portfolio data dynamically effectively executes 6+ queries against the database to fetch `User`, `Experience`, `Skill`, `Project`, `Certificate`, `Language`, and `Education` models on every single `/api/chatbot` interaction.
**Action:** When building extensive contextual prompts from static database records, encapsulate the aggregation logic inside `Cache::remember()` to ensure that the heavy DB queries are bypassed for the duration of the cache, ensuring prompt generation responds immediately.
## $(date +%Y-%m-%d) - Defer Synchronous Database Writes in Middleware
**Learning:** In Laravel 11.x, performing synchronous database inserts (like tracking page visits in middleware) blocks the HTTP response, increasing TTFB and reducing perceived performance.
**Action:** Use Laravel's `defer()` helper to push non-critical, synchronous database operations to a background task that executes after the response is sent to the client, improving page load times.
## 2026-06-03 - Defer Synchronous Database Writes in Middleware
**Learning:** In Laravel 11.x+, performing synchronous database inserts (like tracking page visits in middleware) blocks the HTTP response, increasing TTFB and reducing perceived performance. Always capture context state (like `now()` or request IP) before deferring, as the request might be terminated when the closure executes.
**Action:** Use Laravel's `defer()` helper to push non-critical, synchronous database operations to a background task that executes after the response is sent to the client, capturing context state before deferring.
