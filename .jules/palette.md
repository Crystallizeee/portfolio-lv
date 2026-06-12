## 2025-02-12 - Dynamic ARIA labels for Livewire components
**Learning:** When adding `aria-label` or `title` attributes to dynamically generated icon-only buttons in Laravel Blade templates (e.g., inside `@foreach` loops), using dynamic context variables like `{{ $index + 1 }}` is essential to distinguish them for screen readers (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`).
**Action:** Always include loop iteration index or item-specific identifiers when labeling repeated icon-only buttons in `.blade.php` files to prevent duplicate non-descriptive labels.

## 2024-06-13 - [Mobile Menu Accessibility]
**Learning:** The mobile menu toggle button lacks the `aria-expanded` and `aria-controls` attributes, which are essential for screen readers to understand the state of the menu and the element it controls.
**Action:** Always add `:aria-expanded="mobileMenuOpen.toString()"` and `aria-controls="mobile-menu"` to the toggle button, and `id="mobile-menu"` to the menu container when implementing AlpineJS toggles.
