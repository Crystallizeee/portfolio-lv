## 2025-02-12 - Dynamic ARIA labels for Livewire components
**Learning:** When adding `aria-label` or `title` attributes to dynamically generated icon-only buttons in Laravel Blade templates (e.g., inside `@foreach` loops), using dynamic context variables like `{{ $index + 1 }}` is essential to distinguish them for screen readers (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`).
**Action:** Always include loop iteration index or item-specific identifiers when labeling repeated icon-only buttons in `.blade.php` files to prevent duplicate non-descriptive labels.
