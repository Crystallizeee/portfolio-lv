## 2024-03-21 - Icon-Only Buttons Missing ARIA Labels
**Learning:** The application extensively uses Lucide icons (`<i data-lucide="..."></i>`) for key interactive elements like mobile menus, chat widgets, sidebar toggles, and scroll-to-top buttons. These buttons lack descriptive `aria-label` attributes, making them inaccessible to screen readers as there is no visible text.
**Action:** Always verify icon-only buttons (`<button>` tags wrapping solely `<i data-lucide="..."></i>` or SVG elements) include explicit `aria-label` attributes describing their function, particularly in core layout components like `app.blade.php` and `admin.blade.php`.

## 2024-05-18 - Avoid aria-label on buttons with dynamic inner text
**Learning:** Adding `aria-label` directly to a button overrides all of its inner content for screen readers. In cases like a "Like" toggle button that also displays a dynamic count (e.g., "$likesCount"), an `aria-label` hides that count.
**Action:** For toggle buttons with dynamic inner text, use `aria-pressed` for state, `aria-hidden="true"` on decorative elements (like SVGs), and use visually hidden text (`<span class="sr-only">`) to provide context without overriding the dynamic content.
