## 2025-05-19 - [Resolve N+1 query on blog posts]
**Learning:** Found N+1 queries in the blog rendering where `user` is lazy-loaded in a loop inside views. Eager load should be used instead.
**Action:** Use `with('user')` when fetching posts in the controller to avoid triggering multiple queries during iteration.

## 2026-05-21 - [Livewire Landing Page Query Caching]
**Learning:** Components loaded on the landing page execute identical database queries across requests without caching. This can degrade performance under load as each Livewire component performs synchronous queries to the database.
**Action:** Use `Cache::remember` inside heavily accessed Livewire components to store the retrieved data. This avoids repetitive synchronous DB hits and improves load speeds.
