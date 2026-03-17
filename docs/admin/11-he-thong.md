# Admin 11. Cài Đặt Hệ Thống

## Mô tả
Module cấu hình toàn bộ hệ thống, bao gồm thông tin shop, banner quảng cáo, cài đặt ngân hàng và nhật ký hệ thống.

---

## Chức Năng

### A11.1 Cài Đặt Chung (System Settings)
- **Route:** `GET/POST /admin/system-settings`
- **Controller:** `Admin\SettingController`
- **Cấu hình:**
  | Nhóm | Các tham số |
  |------|------------|
  | Thông tin shop | Tên, logo, địa chỉ, hotline, email |
  | Mạng xã hội | Facebook, Instagram, TikTok URLs |
  | Vận chuyển | Phí ship theo tỉnh/thành, miễn ship từ X VNĐ |
  | Điểm tích lũy | Tỷ lệ tích điểm, tỷ giá quy đổi |
- **Lưu trữ:** Bảng `settings` dạng key-value.

---

### A11.2 Banner
- **Route:** `GET /admin/banners` (resource)
- **Controller:** `Admin\BannerController`
- **Thông tin:**
  - Tiêu đề, hình ảnh, liên kết (URL đích khi click)
  - Vị trí hiển thị (slider chính, banner phụ...)
  - Thứ tự hiển thị, bật/tắt
- **Model:** `Banner` — Bảng `banners`

---

### A11.3 Cài Đặt Ngân Hàng (QR Thanh Toán)
- **Route:** `GET /admin/bank-settings` (resource)
- **Controller:** `Admin\BankSettingController`
- **Thông tin:**
  | Trường | Mô tả |
  |--------|-------|
  | `bank_name` | Tên ngân hàng (VD: Vietcombank, MB Bank) |
  | `bank_id` | Mã ngân hàng cho VietQR API |
  | `account_number` | Số tài khoản |
  | `account_name` | Tên chủ tài khoản |
  | `is_active` | Bật/tắt hiển thị |
  | `is_default` | Ngân hàng mặc định khi checkout *(chỉ 1 tại 1 thời điểm)* |
- **Model:** `BankSetting` — Bảng `bank_settings`
- **Nghiệp vụ:** Khi đặt `is_default = true`, hệ thống tự hủy default các ngân hàng khác.


---

### A11.4 Nhật Ký Kiểm Toán (Audit Logs)
- **Route:** `GET /admin/audit-logs`
- **Controller:** `Admin\AuditLogController@index`
- **Mô tả:** Ghi lại tất cả hành động thay đổi dữ liệu trong hệ thống.
- **Thông tin log:**
  - Ai thực hiện (user_id, email)
  - Hành động (created / updated / deleted)
  - Model và ID bị tác động
  - Dữ liệu trước và sau khi thay đổi (JSON diff)
  - Thời gian
- **Chỉ Admin** được xem.

---

### A11.5 Thông Báo Admin
- **Route:** `GET /admin/notifications`
- **Controller:** `Admin\NotificationController`
- **Chức năng:**
  - Xem tất cả thông báo (đơn mới, thanh toán, đánh giá mới,...)
  - Đánh dấu đã đọc / đánh dấu tất cả đã đọc
  - Badge đếm thông báo chưa đọc trên header

---

### A11.6 Hồ Sơ Admin
- **Route:** `GET/POST /admin/profile`
- **Controller:** `Admin\ProfileController`
- **Chức năng:**
  - Cập nhật họ tên, số điện thoại, địa chỉ
  - Thay ảnh đại diện
  - Đổi mật khẩu (yêu cầu nhập mật khẩu hiện tại)

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Cài đặt hệ thống | ❌ | ✅ |
| Banner | ❌ | ✅ |
| Cài đặt ngân hàng | ❌ | ✅ |
| Xem Audit Logs | ❌ | ✅ |
| Xem Thông báo | ✅ | ✅ |
| Hồ sơ Admin | ✅ | ✅ |

## Models Liên Quan
- `Setting` — Bảng `settings` (key, value)
- `Banner` — Bảng `banners`
- `BankSetting` — Bảng `bank_settings`
- `AuditLog` — Bảng `audit_logs`
