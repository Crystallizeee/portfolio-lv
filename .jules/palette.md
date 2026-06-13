## 2025-02-12 - Dynamic ARIA labels for Livewire components
**Learning:** When adding `aria-label` or `title` attributes to dynamically generated icon-only buttons in Laravel Blade templates (e.g., inside `@foreach` loops), using dynamic context variables like `{{ $index + 1 }}` is essential to distinguish them for screen readers (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`).
**Action:** Always include loop iteration index or item-specific identifiers when labeling repeated icon-only buttons in `.blade.php` files to prevent duplicate non-descriptive labels.
## 2024-05-18 - Added Target Binding to Livewire Buttons
**Learning:** By default `wire:loading` without a target triggers on all Livewire requests inside the component (e.g. typing in inputs with `wire:model.live`). Mixed localization texts (like "Menyimpan..." on an english form) can also easily sneak into Livewire files without proper review.
**Action:** When adding UX loading states to submit buttons using `wire:loading.attr="disabled"`, always attach `wire:target="methodName"` so the spinner specifically waits only for the expected submit action. Ensure consistent language usage based on context.
