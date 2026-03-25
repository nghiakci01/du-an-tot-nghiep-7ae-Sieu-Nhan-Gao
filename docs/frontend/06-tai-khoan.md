# 06. Tài Khoản Khách Hàng (My Account)

## Mô tả
Trang quản lý tài khoản cá nhân cho khách hàng đã đăng nhập: xem thông tin, cập nhật hồ sơ và quản lý lịch sử đơn hàng.

---

## Chức Năng

### 6.1 Trang Tài Khoản
- **Route:** `GET /my-account`
- **Controller:** `Frontend\AccountController@index`
- **Mô tả:** Dashboard tài khoản với các tab: Thông tin cá nhân, Đơn hàng.
- **Yêu cầu:** Đã đăng nhập.

---

### 6.2 Cập Nhật Thông Tin Cá Nhân
- **Route:** `POST /my-account/update`
- **Controller:** `Frontend\AccountController@update`
- **Đầu vào:**
  - `name` — Họ tên
  - `phone` — Số điện thoại
  - `address` — Địa chỉ
  - `avatar` — Ảnh đại diện (file hình ảnh, tùy chọn)
  - `current_password`, `new_password`, `new_password_confirmation` — Đổi mật khẩu (tùy chọn)
- **Nghiệp vụ:**
  - Nếu có `new_password` → xác minh `current_password` trước khi thay đổi.
  - Ảnh đại diện được lưu vào `storage/app/public/avatars/`.

---

### 6.3 Danh Sách Đơn Hàng
- **Route:** `GET /my-account/orders`
- **Mô tả:** Xem lịch sử tất cả đơn hàng của tài khoản, sắp xếp theo thời gian mới nhất.

---

### 6.4 Chi Tiết Đơn Hàng
- **Route:** `GET /my-account/orders/{id}`
- **Controller:** `Frontend\AccountController@showOrder`
- **Mô tả:** Xem chi tiết một đơn hàng cụ thể gồm danh sách sản phẩm, trạng thái, địa chỉ giao hàng và lịch sử trạng thái.

---

### 6.5 Hủy Đơn Hàng
- **Route:** `POST /my-account/orders/{id}/cancel`
- **Controller:** `Frontend\AccountController@cancelOrder`
- **Nghiệp vụ:**
  - Chỉ được hủy khi trạng thái đơn là `pending`.
  - Hoàn lại tồn kho.
  - Ghi nhật ký lịch sử đơn hàng.

---

### 6.6 Yêu Cầu Hoàn Trả (Return Order) *(MỚI)*
- **Route:** `GET /my-account/orders/{id}/return`
- **Controller:** `Frontend\AccountController@returnOrderForm`
- **Nghiệp vụ:**
  - Áp dụng cho đơn hàng `completed` hoặc `shipped`.
  - Khách hàng nhập lý do, ghi chú và tải lên tối đa 5 ảnh minh họa.
  - Sau khi Admin duyệt, khách hàng nộp thông tin vận chuyển (mã vận đơn, chứng từ).

---

### 6.7 Quản Lý Tài Khoản Ngân Hàng *(MỚI)*
- **Mô tả:** Khách hàng lưu thông tin tài khoản ngân hàng để nhận tiền hoàn trả.
- **Model:** `UserBankAccount`
- **Thông tin:** Tên ngân hàng, Số tài khoản, Tên chủ tài khoản, Chi nhánh.

---

## Quyền Hạn
| Hành động | Guest | User |
|-----------|-------|------|
| Xem trang tài khoản | ❌ | ✅ |
| Cập nhật thông tin | ❌ | ✅ |
| Xem đơn hàng | ❌ | ✅ |
| Hủy đơn hàng | ❌ | ✅ |
