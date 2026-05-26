## 2024-03-21 - Icon-Only Buttons Missing ARIA Labels
**Learning:** The application extensively uses Lucide icons (`<i data-lucide="..."></i>`) for key interactive elements like mobile menus, chat widgets, sidebar toggles, and scroll-to-top buttons. These buttons lack descriptive `aria-label` attributes, making them inaccessible to screen readers as there is no visible text.
**Action:** Always verify icon-only buttons (`<button>` tags wrapping solely `<i data-lucide="..."></i>` or SVG elements) include explicit `aria-label` attributes describing their function, particularly in core layout components like `app.blade.php` and `admin.blade.php`.
## 2024-05-18 - Async Loading Patterns
**Learning:** Implementing explicit loading states for Livewire form submissions using  and  significantly improves user feedback and prevents duplicate submissions.
**Action:** Always verify that form submissions in Livewire components include visual feedback (like a spinner or text change) and that the submit button is disabled () during the asynchronous request.
## 2026-05-26 - Async Loading Patterns
**Learning:** Implementing explicit loading states for Livewire form submissions using wire:loading and wire:target significantly improves user feedback and prevents duplicate submissions.
**Action:** Always verify that form submissions in Livewire components include visual feedback (like a spinner or text change) and that the submit button is disabled (wire:loading.attr="disabled") during the asynchronous request.
