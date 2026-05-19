## 2025-05-19 - [Resolve N+1 query on blog posts]
**Learning:** Found N+1 queries in the blog rendering where `user` is lazy-loaded in a loop inside views. Eager load should be used instead.
**Action:** Use `with('user')` when fetching posts in the controller to avoid triggering multiple queries during iteration.
