## 2025-02-12 - Dynamic ARIA labels for Livewire components
**Learning:** When adding `aria-label` or `title` attributes to dynamically generated icon-only buttons in Laravel Blade templates (e.g., inside `@foreach` loops), using dynamic context variables like `{{ $index + 1 }}` is essential to distinguish them for screen readers (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`).
**Action:** Always include loop iteration index or item-specific identifiers when labeling repeated icon-only buttons in `.blade.php` files to prevent duplicate non-descriptive labels.

## 2025-02-13 - Icon-only buttons with tooltips lack ARIA labels
**Learning:** Many icon-only buttons in the application use a `title` attribute for tooltips but completely omit the `aria-label` attribute. While `title` provides visual hover text, relying solely on it for accessibility is insufficient as it is not consistently announced by all screen readers across all browsers.
**Action:** Whenever implementing or encountering an icon-only button (e.g., `<i data-lucide="..."></i>` inside a `<button>`) that has a `title` attribute, ensure that an identical or more descriptive `aria-label` attribute is also applied directly to the `<button>` element.
