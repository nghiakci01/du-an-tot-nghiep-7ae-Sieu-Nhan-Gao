# [PLAN] Global Admin UI Fix - Unified Sticky Action Columns

This plan outlines the process of implementing a consistent, reusable Sticky Action Column across all administrative management tables in the application.

## Context
Multiple admin pages (Products, Coupons, Reviews, Users, etc.) suffer from "screen real estate" issues where the action buttons (Edit/Delete) are hidden during horizontal scrolling. A centralized solution is needed to fix all existing and future admin tables.

## Proposed Solution: Option A (Centralized CSS Utility)
We will move the Sticky Action Column CSS to the global admin layout and then apply a standard class to all resource index views.

## Task Breakdown

### Phase 1: Globalization of Styles
- [ ] Move CSS from `admin/products/index.blade.php` to a `<style>` block in `layouts/admin.blade.php`.
- [ ] Refactor CSS to be as generic as possible (handling stripes and dark mode globally).
- [ ] Verify that the `admin-products` page still works after the move.

### Phase 2: Systematic View Updates
Apply the `.sticky-action-column` class to the "Hành động" (Action) header and cells in the following views:
- [ ] Coupons Index
- [ ] Reviews Index
- [ ] Users Index
- [ ] Categories Index
- [ ] Orders Index
- [ ] Banners Index
- [ ] Suppliers Index
- [ ] Order Returns Index
- [ ] Attributes (Colors, Sizes) Index
- [ ] Bank Settings Index

### Phase 3: Verification & Polish
- [ ] Cross-browser/Cross-device check (Chrome, Firefox, Safari style).
- [ ] Theme check (Light/Dark mode transitions).
- [ ] Layout check (Ensure no overflow-y issues arise from sticky positioning).

## Verification Checklist
- [ ] `.sticky-action-column` is defined globally in `admin.blade.php`.
- [ ] At least 5 different management pages have the sticky column functional.
- [ ] Edit/Delete button functionality remains intact.
- [ ] Visual separation (shadow/border) looks consistent across all pages.
