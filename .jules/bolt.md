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
## 2025-05-18 - Bulk API Fetching to prevent N+1 queries
**Learning:** Making separate HTTP requests to external APIs (e.g., Proxmox API) for individual entities (VMs, LXCs) inside loops or regular Livewire polling intervals (`#[Polling]`) introduces severe N+1 request bottlenecks, delaying component render times significantly.
**Action:** Always utilize bulk endpoints to fetch the state of all entities in a single request. Extract the needed data and cache the batched result using `Cache::remember()`, thereby reducing O(N) external requests to O(1).
## 2025-02-18 - Missing Livewire Pagination Trait Causes Full Reloads
**Learning:** Replacing `get()` with `paginate()` in a Livewire component correctly limits queries, but if you forget to `use Livewire\WithPagination;` inside the class, Livewire will render standard HTML `href` links for pagination controls instead of handling them via AJAX. This causes full page reloads, destroying the internal state of interactive components (like open modals or unsaved form input).
**Action:** When converting a query to use `paginate()` in a Livewire component for performance, always manually verify that the component class imports and uses the `Livewire\WithPagination` trait.
## 2025-05-18 - Scope Caches for Dynamic Data
**Learning:** Caching personalized or dynamic user records globally causes severe Stale Data and Data Leakage issues. For example, caching user CV data without appending the user ID will give everyone the same CV data and prevent data updates from immediately reflecting for an hour.
**Action:** When caching personalized user content (e.g., dynamic CV data or AI context), always scope the cache key to the specific user (e.g., `Cache::remember('data_' . auth()->id(), ...)`). Using a global cache key for user-specific data causes severe stale data regressions and cross-user data leakage. Avoid using cache for frequently changing components unless paired with corresponding `Cache::forget` mechanisms on model events.

## 2025-05-18 - Rate Limit Heavy Facades
**Learning:** Even if an API endpoint requires a strict authorization token, it can still be vulnerable to resource exhaustion DoS attacks if it handles an incredibly heavy operation, like generating a PDF using `Pdf::loadHtml()`.
**Action:** Always rate limit specific controller actions and endpoints that perform exceptionally heavy tasks (like generating PDFs, fetching enormous amounts of aggregated data, or firing bulk email chains), explicitly using Laravel's `RateLimiter` to protect system resources.
## $(date +%Y-%m-%d) - Prevent N+1 queries using collection count
**Learning:** Using `->count()` on an Eloquent relation after already fetching it via `->get()` triggers a completely redundant database query.
**Action:** When a full collection is being retrieved anyway for rendering in a view, always assign it to a variable and use the collection's internal `->count()` method to derive totals rather than issuing a separate database `COUNT()` aggregate query.
## 2025-02-12 - Prevent N+1 Database Write Bottlenecks in Livewire Polling Components
**Learning:** When Livewire components use polling (e.g., `#[Polling('30s')]`), any unconditional database writes inside the polling loop will create massive N+1 write bottlenecks across all connected clients. Throttling these writes using model timestamp properties (e.g., `$model->last_checked_at`) is risky because if the property is null or not properly cast to a Carbon instance in `$casts`, it causes fatal errors.
**Action:** Always use Laravel's atomic caching method `Cache::add('key', true, $seconds)` to safely throttle recurring DB writes across all concurrent clients without relying on potentially missing or uncast model properties.
