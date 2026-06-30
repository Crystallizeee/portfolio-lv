## 2024-03-21 - Icon-Only Buttons Missing ARIA Labels
**Learning:** The application extensively uses Lucide icons (`<i data-lucide="..."></i>`) for key interactive elements like mobile menus, chat widgets, sidebar toggles, and scroll-to-top buttons. These buttons lack descriptive `aria-label` attributes, making them inaccessible to screen readers as there is no visible text.
**Action:** Always verify icon-only buttons (`<button>` tags wrapping solely `<i data-lucide="..."></i>` or SVG elements) include explicit `aria-label` attributes describing their function, particularly in core layout components like `app.blade.php` and `admin.blade.php`.
## 2025-02-23 - Add loading states for async operations
**Learning:** Found that some buttons triggering async operations (like toggling a "Like" button in `post-like-button.blade.php`) lacked visual feedback, leading to potential duplicate submissions or confusion.
**Action:** Added `wire:loading.attr="disabled"`, `wire:loading.class="opacity-50 cursor-not-allowed"`, and `wire:target="toggleLike"` to provide immediate feedback.
## 2024-05-24 - Standardized modal close buttons\n**Learning:** Crude text 'X' close buttons in modals are confusing for screen readers without labels, and look inconsistent visually.\n**Action:** Replaced text 'X' with lucide icons and added aria-label='Close modal' for a11y.
