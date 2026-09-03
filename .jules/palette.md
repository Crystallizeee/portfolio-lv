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
## 2026-07-06 - Add Skip to Content Links
**Learning:** Implementing skip to content links for keyboard users requires the target <main> to have tabindex="-1" and focus:outline-none to properly receive focus without showing an ugly ring, while remaining hidden for mouse users.
**Action:** Always ensure target element for skip links has appropriate focus management.
## 2026-07-08 - Added aria-hidden attributes to icon-only buttons
**Learning:** Found that screen readers can sometimes awkwardly read out SVG content or meaningless strings for icon-only buttons relying on Lucide icons, even when the parent button has an `aria-label`. Additionally, expandable toggle widgets (like the chat window or mobile menus) require `aria-controls` and `aria-expanded` bindings on the trigger button to explicitly announce state changes to screen reader users.
**Action:** Added `aria-hidden="true"` to the internal `<i data-lucide="...">` elements inside icon-only buttons across layouts. Also explicitly bound `:aria-expanded` and `aria-controls` to expanding toggle triggers like the chatbot and sidebar menus.

## 2024-07-28 - Icon Button Focus States & Inconsistent Translations
**Learning:** Found instances where custom icon buttons (like "Copy Link" or "Like") lacked `focus-visible` styling, making keyboard navigation difficult to track. Additionally, some localized strings (e.g., "Salin Tautan") were hardcoded in otherwise English views, confusing screen readers.
**Action:** Always verify `focus-visible` states for interactive elements, ensure `aria-hidden` is applied to decorative icons, and maintain consistent language strings within views.
## 2026-07-29 - Focus States and ARIA attributes in Manage Languages
**Learning:** Found that the icon buttons in the Manage Languages component lacked focus-visible states and their internal Lucide icons lacked `aria-hidden="true"`, causing poor keyboard navigation and screen reader confusion.
**Action:** When updating or reviewing similar Livewire components, ensure all interactive buttons have `focus:outline-none focus-visible:ring-2` styling, and internal SVG/icon elements have `aria-hidden="true"` so they aren't incorrectly announced by screen readers.
## 2024-07-30 - Layout Accessibility Improvements
**Learning:** Found that core navigational elements in `resources/views/components/layouts/app.blade.php` lacked proper ARIA attributes and focus-visible states. Specifically, Lucide icons within icon-only buttons were not hidden from screen readers (`aria-hidden="true"`), and interactive elements like the mobile menu toggle and command palette button did not have `aria-controls` or `aria-expanded` attributes. Furthermore, the chat widget lacked `role="dialog"` and `aria-modal="true"`.
**Action:** Always ensure that icon-only buttons have `aria-hidden="true"` on the internal icon element, interactive buttons have `focus:outline-none focus-visible:ring-2` for keyboard accessibility, and custom widgets (like modals or menus) use appropriate ARIA roles (`dialog`, `aria-modal`) and state attributes (`aria-expanded`, `aria-controls`).
## 2024-08-03 - Added missing focus rings to main navigation links
**Learning:** Main navigation anchor links that rely purely on hover colors (`hover:text-cyan-400`) might completely lack visible focus indicators for keyboard navigation (`Tab`) if `focus-visible` classes aren't explicitly added. This is a subtle but critical accessibility flaw in many custom Tailwind navigation implementations.
**Action:** Always verify that interactive elements, especially custom-styled anchors and buttons, have explicit `focus-visible` utility classes (e.g., `focus:outline-none focus-visible:ring-2`) to ensure a proper visual indicator for keyboard users.
## 2026-08-01 - Focus States and External Links in Projects Grid
**Learning:** Found that links within the project grid (title link, "Read Case Study", "Live Demo") lacked visual focus states for keyboard navigation. Additionally, external links lacked `rel="noopener noreferrer"` and screen reader text indicating they open in a new tab.
**Action:** Always add explicit `focus-visible` utility classes to interactive elements like links and buttons for keyboard accessibility. Ensure external links use `rel="noopener noreferrer"` and include `<span class="sr-only">(opens in a new tab)</span>`.
## 2024-08-03 - Added missing focus rings to main navigation links
**Learning:** Main navigation anchor links that rely purely on hover colors (`hover:text-cyan-400`) might completely lack visible focus indicators for keyboard navigation (`Tab`) if `focus-visible` classes aren't explicitly added. This is a subtle but critical accessibility flaw in many custom Tailwind navigation implementations.
**Action:** Always verify that interactive elements, especially custom-styled anchors and buttons, have explicit `focus-visible` utility classes (e.g., `focus:outline-none focus-visible:ring-2`) to ensure a proper visual indicator for keyboard users.
