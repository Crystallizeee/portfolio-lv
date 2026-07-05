## 2024-03-21 - Icon-Only Buttons Missing ARIA Labels
**Learning:** The application extensively uses Lucide icons (`<i data-lucide="..."></i>`) for key interactive elements like mobile menus, chat widgets, sidebar toggles, and scroll-to-top buttons. These buttons lack descriptive `aria-label` attributes, making them inaccessible to screen readers as there is no visible text.
**Action:** Always verify icon-only buttons (`<button>` tags wrapping solely `<i data-lucide="..."></i>` or SVG elements) include explicit `aria-label` attributes describing their function, particularly in core layout components like `app.blade.php` and `admin.blade.php`.
## 2025-02-23 - Add loading states for async operations
**Learning:** Found that some buttons triggering async operations (like toggling a "Like" button in `post-like-button.blade.php`) lacked visual feedback, leading to potential duplicate submissions or confusion.
**Action:** Added `wire:loading.attr="disabled"`, `wire:loading.class="opacity-50 cursor-not-allowed"`, and `wire:target="toggleLike"` to provide immediate feedback.
## 2024-05-24 - Standardized modal close buttons\n**Learning:** Crude text 'X' close buttons in modals are confusing for screen readers without labels, and look inconsistent visually.\n**Action:** Replaced text 'X' with lucide icons and added aria-label='Close modal' for a11y.
## 2025-02-12 - Icon-only Button Accessibility
**Learning:** Icon-only buttons using Lucide icons (`<i data-lucide="..."></i>`) often rely solely on `title` attributes for context, which are not reliably announced by screen readers, leading to poor accessibility. Also, the SVG icons themselves are sometimes announced as meaningless elements if not explicitly hidden.
**Action:** When implementing or updating icon-only buttons, explicitly extract the `title` into an `aria-label` on the parent `<button>` element. Simultaneously, add `aria-hidden="true"` to the inner `<i>` tag containing the Lucide icon to prevent redundant or confusing screen reader announcements.
## 2025-07-06 - Livewire Button Loading States Target
**Learning:** In Livewire, when implementing loading states (spinners or text changes) on generic submit buttons, explicitly defining `wire:target="methodName"` is critical. Without it, global interactions on the page might inadvertently trigger the loading state of unrelated buttons, causing visual confusion.
**Action:** Always pair `wire:loading` (and `wire:loading.attr="disabled"`) with a specific `wire:target="methodName"` when enhancing action buttons to isolate the loading feedback correctly.
