## 2026-05-17 - Test DB Setup
**Learning:** Tests were failing with "no such table" errors in CI because the environment wasn't setting up a test database correctly.
**Action:** Always call `$this->withoutVite()` in `TestCase::setUp()` to avoid "Vite manifest not found" errors when tests run before building assets. Ensure tests relying on missing tables have their dependencies seeded or factoried appropriately, and if using sqlite, to `touch database/database.sqlite` and pass the `DB_DATABASE` env appropriately.
