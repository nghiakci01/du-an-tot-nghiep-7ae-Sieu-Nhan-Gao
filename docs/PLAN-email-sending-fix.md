# PLAN: Khắc phục lỗi không gửi Email khi đăng ký

Hệ thống hiện tại gặp vấn đề khiến email kẹt trong hàng đợi (Queue) hoặc bị chặn bởi giao thức SSL cũ trên môi trường Laragon. Kế hoạch này tập trung vào việc đưa cấu hình về trạng thái "Gửi ngay" (Synchronous) và sử dụng cổng kết nối an toàn nhưng ổn định hơn.

## User Review Required

> [!IMPORTANT]
> **Thay đổi quan trọng trong .env**:
> - `QUEUE_CONNECTION` sẽ chuyển từ `database` sang `sync`. Điều này giúp email gửi ngay mà không cần chạy lệnh `php artisan queue:work`.
> - `MAIL_PORT` chuyển sang `587` và `MAIL_ENCRYPTION` sang `tls`.

## Proposed Changes

### [Component] Environment & Configuration

#### [MODIFY] [.env](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/.env)
- Đổi `QUEUE_CONNECTION=sync`.
- Đổi `MAIL_PORT=587`.
- Đổi `MAIL_ENCRYPTION=tls`.

---

### [Component] Verification & Testing

#### [NEW] [TestMailController.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Http/Controllers/TestMailController.php) [TEMP]
- Tạo một route tạm `/test-mail-direct` để test gửi 1 email rác ngay lập tức, giúp phân lập lỗi do SMTP hay do Logic của RegisterController.

---

## Open Questions

- **Bạn có đang chạy lệnh `php artisan queue:work` không?** (Nếu không chạy, cấu hình `database` chắc chắn là nguyên nhân khiến mail không bay đi).
- **Bạn có muốn tôi thử gửi 1 mail test trực tiếp bằng lệnh terminal trước không?**

## Verification Plan

### Automated/Terminal Tests
1. Chạy `php artisan config:clear`.
2. Chạy lệnh Tinker gửi mail trực tiếp:
   ```php
   Mail::raw('Test', function($m) { $m->to('your-email@gmail.com')->subject('Test'); });
   ```

### Manual Verification
1. Đăng ký tài khoản mới trên giao diện `http://elite.test/register`.
2. Kiểm tra Inbox (bao gồm cả thư rác - Spam).
3. Kiểm tra file `storage/logs/laravel.log` nếu mail không tới.
