## 2025-02-18 - Ensure aria-labels for Icon-Only Buttons
**Learning:** Depending solely on `title` attributes for icon-only buttons isn't fully accessible. Some screen readers and devices may ignore it, leaving users without context for interactive elements.
**Action:** Always add an explicit `aria-label` to buttons that only contain an icon (e.g., using Lucide icons) to provide better context and a more inclusive experience.
