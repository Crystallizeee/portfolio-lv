## 2025-02-12 - Dynamic ARIA labels for Livewire components
**Learning:** When adding `aria-label` or `title` attributes to dynamically generated icon-only buttons in Laravel Blade templates (e.g., inside `@foreach` loops), using dynamic context variables like `{{ $index + 1 }}` is essential to distinguish them for screen readers (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`).
**Action:** Always include loop iteration index or item-specific identifiers when labeling repeated icon-only buttons in `.blade.php` files to prevent duplicate non-descriptive labels.
## 2025-02-12 - Explicit State for Toggle Buttons
**Learning:** When creating accessible toggle buttons (like mobile menus or "Read more" links) with AlpineJS, it is not enough to just use `x-show`. You must explicitly connect the state to ARIA attributes using `:aria-expanded="stateVariable.toString()"` on the button and `aria-controls="target-id"` pointing to the corresponding container `id`.
**Action:** Always include `aria-expanded` and `aria-controls` for interactive disclosures to ensure screen readers announce the state correctly.
