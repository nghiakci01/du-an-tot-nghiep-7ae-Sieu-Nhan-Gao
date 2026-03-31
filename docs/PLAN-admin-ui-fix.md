# [PLAN] Admin Product UI Fix - Sticky Action Column

This plan addresses the issue where the admin product list action buttons (Edit/Delete) are being cut off or making the sidebar/layout feel unsuitable due to horizontal scrolling.

## Context
The admin product table has many columns, causing horizontal overflow. The action buttons are at the end, making them hard to reach or obscured by the sidebar on smaller screens.

## Proposed Solution: Option A (Sticky Action Column)
We will implement a CSS-based "Sticky Column" for the "Hành động" (Action) column. This ensures that the Edit and Delete buttons are always visible on the right side of the screen, even when the user scrolls the table horizontally.

## Task Breakdown

### Phase 1: Style Definition
- [ ] Add sticky column CSS to the global admin layout or a dedicated style section in the product index.
- [ ] Support both Light and Dark modes.
- [ ] Add a subtle shadow to separate the sticky column from the scrollable content.

### Phase 2: View Modification
- [ ] Modify `resources/views/admin/products/index.blade.php`.
- [ ] Apply `.sticky-column` class to the last `th` and all last `td` elements.
- [ ] Ensure the container wrapping the table has the correct overflow settings.

### Phase 3: Verification
- [ ] Test on various screen sizes.
- [ ] Verify functionality of Edit/Delete buttons in the sticky column.
- [ ] Check visual consistency in both Light and Dark themes.

## Verification Checklist
- [ ] Action buttons are always visible on the right.
- [ ] Background color of the sticky column matches the row background (stripes handled).
- [ ] No layout breakage on mobile/tablet widths.
