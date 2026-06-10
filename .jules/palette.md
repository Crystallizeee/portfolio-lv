## 2024-05-18 - Alpine.js Accordion/Disclosure Accessibility
**Learning:** When using Alpine.js (`x-data="{ expanded: false }"`) for "Read more / Show less" style disclosure widgets or accordions, ensuring the button uses dynamic `:aria-expanded="expanded"` and `aria-controls` linked to a specific container `id` is a critical and simple accessibility win. Screen readers can then accurately convey whether the section is collapsed or expanded.
**Action:** When implementing or modifying any custom expanding sections, verify the presence of `aria-expanded` and matching `aria-controls`/`id` pairs on the toggle button and content container.

## 2024-05-18 - Livewire Form Submit Loading States
**Learning:** Adding loading states to standard asynchronous `<form wire:submit="method">` buttons significantly improves perceived performance and prevents duplicate submissions. Using Livewire's `wire:loading.attr="disabled"` combined with target-specific UI (e.g., `<span wire:loading wire:target="method">`) ensures that only the relevant button shows the loading state, providing clearer feedback.
**Action:** When implementing or modifying forms in Livewire, ensure submit buttons utilize `wire:loading`, `wire:target`, and appropriate Tailwind disabled classes (`disabled:opacity-50 disabled:cursor-not-allowed`) for a polished UX.
