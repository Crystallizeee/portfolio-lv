## 2025-05-19 - [Resolve N+1 query on blog posts]
**Learning:** Found N+1 queries in the blog rendering where `user` is lazy-loaded in a loop inside views. Eager load should be used instead.
**Action:** Use `with('user')` when fetching posts in the controller to avoid triggering multiple queries during iteration.

## 2026-05-21 - [Livewire Landing Page Query Caching]
**Learning:** Components loaded on the landing page execute identical database queries across requests without caching. This can degrade performance under load as each Livewire component performs synchronous queries to the database.
**Action:** Use `Cache::remember` inside heavily accessed Livewire components to store the retrieved data. This avoids repetitive synchronous DB hits and improves load speeds.

## 2026-05-27 - [Global Static Data Query Caching in Models]
**Learning:** Functions in Models that retrieve global, static data (like the portfolio owner) and are called from multiple components and controllers can cause redundant synchronous DB hits if they do not utilize caching internally, even if one controller caches the result.
**Action:** Move the `Cache::remember` logic inside the Model's retrieval method (e.g., `User::getPortfolioOwner()`) instead of the controller to ensure all downstream callers benefit from the cache automatically and avoid repetitive DB queries.

## 2026-05-28 - [Admin Dashboard N+1 Query in Date Iterations]
**Learning:** Found N+1 queries in `AdminDashboard`'s `prepareChartData` where queries were executed inside a `foreach` loop that iterated through an array of dates to retrieve analytics and visit stats per day.
**Action:** Instead of querying inside the loop, use `whereBetween` on the date range along with `selectRaw('date(created_at) as date, count(*) as count')`, `groupBy('date')`, and `pluck('count', 'date')`. Then inside the loop, retrieve the data using `$collection->get($date, 0)`.
