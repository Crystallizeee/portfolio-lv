## 2025-02-18 - Cache AI System Prompt Generation
**Learning:** Generating the system prompt dynamically inside the `ChatbotController` for every single request causes a significant bottleneck. Gathering portfolio data dynamically effectively executes 6+ queries against the database to fetch `User`, `Experience`, `Skill`, `Project`, `Certificate`, `Language`, and `Education` models on every single `/api/chatbot` interaction.
**Action:** When building extensive contextual prompts from static database records, encapsulate the aggregation logic inside `Cache::remember()` to ensure that the heavy DB queries are bypassed for the duration of the cache, ensuring prompt generation responds immediately.

## 2025-06-01 - Defer Synchronous Middleware Operations
**Learning:** Performing database writes synchronously in middleware (like analytics or visit tracking) blocks the main thread and delays the HTTP response to the client. In Laravel 11.x, the `defer()` helper allows running background tasks after the response has been sent to the user.
**Action:** When adding middleware that tracks, logs, or writes non-critical data to the database on every request, wrap the write operation in a `defer()` block to ensure it doesn't affect the user's perceived page load time.
