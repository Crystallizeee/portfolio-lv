## 2024-05-18 - Alpine.js Accordion/Disclosure Accessibility
**Learning:** When using Alpine.js (`x-data="{ expanded: false }"`) for "Read more / Show less" style disclosure widgets or accordions, ensuring the button uses dynamic `:aria-expanded="expanded"` and `aria-controls` linked to a specific container `id` is a critical and simple accessibility win. Screen readers can then accurately convey whether the section is collapsed or expanded.
**Action:** When implementing or modifying any custom expanding sections, verify the presence of `aria-expanded` and matching `aria-controls`/`id` pairs on the toggle button and content container.

## 2025-02-12 - Dynamic ARIA Labels for Iterated Components
**Learning:** When using `@foreach` to render a list of identical UI elements with icon-only buttons (e.g., delete/remove actions), assigning a static `title` or `aria-label` like "Remove" creates ambiguity for screen readers, as there will be multiple indistinguishable "Remove" buttons on the page.
**Action:** Always inject loop index or unique context variables into accessibility attributes (e.g., `aria-label="Remove Education #{{ $index + 1 }}"`) to ensure each actionable element is uniquely identifiable.
