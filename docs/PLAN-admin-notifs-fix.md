# Fix Duplicated Admin Notifications

## Context
The admin panel currently has a layout file (`resources/views/layouts/admin.blade.php`) which handles displaying `session('success')` globally. However, almost all individual admin view files also include `@if(session('success'))` locally. This causes multiple success messages to appear simultaneously when actions are completed.

## Objective
Remove all redundant local `session('success')` and `session('error')` blocks from files inside `resources/views/admin/` so that only the global notification handler from `admin.blade.php` is responsible for showing alerts.

## Affected Files
Based on preliminary search, the following files contain the redundant code:
- `admin/wallet/withdrawals.blade.php`
- `admin/wallet/index.blade.php`
- `admin/users/index.blade.php`
- `admin/suppliers/index.blade.php`
- `admin/sizes/index.blade.php`
- `admin/reviews/index.blade.php`
- `admin/products/index.blade.php`
- `admin/posts/index.blade.php`
- `admin/post-categories/index.blade.php`
- `admin/orders/index.blade.php`
- `admin/coupons/index.blade.php`
- `admin/contact-messages/index.blade.php`
- `admin/colors/index.blade.php`
- `admin/chatbot/questions/index.blade.php`
- `admin/chat/trash.blade.php`
- `admin/categories/index.blade.php`
- `admin/banners/index.blade.php`
- `admin/bank-settings/index.blade.php`

## Implementation Steps

### Phase 1: Preparation
- Backup current view states or assure version control (Git) is clean.

### Phase 2: Execution (Targeted Removal)
- For each file identified above, remove the HTML block checking for `@if(session('success'))` and its corresponding `<div class="alert...">...</div>`.
- This can be done effectively via a script (regex replace) or manually by iterating through the files using `multi_replace_file_content`.

### Phase 3: Verification
- Trigger a generic action inside the admin panel (e.g. update a category).
- Verify that exactly **one** "Thành công" alert is displayed at the top right/center of the screen.

## User Review Required
Before proceeding, please review the brainstorming options provided in the chat to confirm the preferred approach.
