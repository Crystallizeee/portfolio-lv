## 2025-05-19 - [Resolve N+1 query on blog posts]
**Learning:** Found N+1 queries in the blog rendering where `user` is lazy-loaded in a loop inside views. Eager load should be used instead.
**Action:** Use `with('user')` when fetching posts in the controller to avoid triggering multiple queries during iteration.

## 2026-05-21 - [Livewire Landing Page Query Caching]
**Learning:** Components loaded on the landing page execute identical database queries across requests without caching. This can degrade performance under load as each Livewire component performs synchronous queries to the database.
**Action:** Use `Cache::remember` inside heavily accessed Livewire components to store the retrieved data. This avoids repetitive synchronous DB hits and improves load speeds.
## 2024-05-22 - [Livewire Component Data Caching]
**Learning:** Livewire components executing database queries in `render()` directly impact heavily-trafficked routes by repeating queries on every request or re-render cycle, especially for static collection data like skills or experiences.
**Action:** When a Livewire component renders static or infrequently updated data on a highly accessed page (e.g., landing page), always wrap the Eloquent query and any subsequent collection operations in Laravel's `Cache::remember()` to eliminate redundant DB queries and computational overhead.
