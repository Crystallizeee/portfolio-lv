## 2024-03-21 - Icon-Only Buttons Missing ARIA Labels
**Learning:** The application extensively uses Lucide icons (`<i data-lucide="..."></i>`) for key interactive elements like mobile menus, chat widgets, sidebar toggles, and scroll-to-top buttons. These buttons lack descriptive `aria-label` attributes, making them inaccessible to screen readers as there is no visible text.
**Action:** Always verify icon-only buttons (`<button>` tags wrapping solely `<i data-lucide="..."></i>` or SVG elements) include explicit `aria-label` attributes describing their function, particularly in core layout components like `app.blade.php` and `admin.blade.php`.

## 2024-03-21 - Async Buttons Missing Loading States
**Learning:** Some interactive buttons that trigger Livewire backend requests, like the "Like" button in `post-like-button.blade.php`, do not utilize Livewire's built-in `wire:loading` utilities, leading to a lack of visual feedback during the request and allowing duplicate clicks.
**Action:** Always leverage Livewire's built-in loading directives (`wire:loading.attr="disabled"`, `wire:loading.class="..."`) for buttons that trigger `wire:click` or form submissions to improve perceived performance and prevent race conditions.
