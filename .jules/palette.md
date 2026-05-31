## 2026-05-30 - Prevent Global Loading State on Generic Submits
**Learning:** In Livewire, using `wire:loading` on a button without specifying `wire:target` causes the button to show its loading state during *any* server roundtrip initiated by the component (e.g., clicking a separate 'Delete' button in a table).
**Action:** Always specify `wire:target="methodName"` alongside `wire:loading` and `wire:loading.attr="disabled"` on async submit buttons to ensure the loading state is scoped precisely to the action it triggers.
