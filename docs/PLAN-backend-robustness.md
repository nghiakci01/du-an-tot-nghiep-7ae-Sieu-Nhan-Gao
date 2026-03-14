# PLAN: Backend Robustness (Option B)

## Overview
Cải thiện độ ổn định của hệ thống bằng cách chuyển đổi các tác vụ nặng (VTON) sang xử lý nền (Background Jobs) và tăng cường khả năng giám sát thông qua Logging chuyên sâu.

## Success Criteria
1. Tính năng thử đồ AI không gây treo trang web (xử lý bất đồng bộ).
2. Toàn bộ các lỗi tiềm ẩn trong AppServiceProvider được ghi lại rõ ràng.
3. Có hệ thống Log riêng biệt cho AI VTON.

## Tech Stack
- Laravel Queues (Database Driver)
- Laravel Logging (Custom Channels)
- AJAX Polling (Status checking)

## Task Breakdown

### Task 1: Hạ tầng Queue & Logging
- [ ] Chạy `php artisan queue:table` và migrate.
- [ ] Cấu hình custom log channel `vton` trong `config/logging.php`.

### Task 2: Refactor VtonController & Jobs
- [ ] Tạo `ProcessVtonJob`.
- [ ] Refactor `VtonController@tryOn` để dispatch Job.
- [ ] Thêm endpoint `vton/status/{id}` để check trạng thái.

### Task 3: Error Visibility
- [ ] Thêm Logging cho AppServiceProvider.
- [ ] Review toàn bộ codebase cho các khối `catch` rỗng.

### Task 4: Frontend Integration
- [ ] Cập nhật JS trong `product/show.blade.php` để thực hiện polling check status.

## Phase X: Verification
- [ ] Chạy worker: `php artisan queue:work --once`.
- [ ] Test VTON flow từ đầu đến cuối.
- [ ] Check logs tại `storage/logs/vton.log`.
