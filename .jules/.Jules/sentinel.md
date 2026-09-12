## 2024-05-24 - Missing Livewire Validation in Custom Methods
**Vulnerability:** Livewire component defined $rules but never called $this->validate() in a custom action.
**Learning:** Defining $rules in Livewire doesn't automatically validate requests for custom actions, leaving inputs completely unvalidated.
**Prevention:** Always explicitly call $this->validate() in custom action methods where $rules are expected to be enforced.
