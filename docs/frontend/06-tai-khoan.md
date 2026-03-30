# 06. Tài Khoản Khách Hàng (My Account)

## Mô tả
Trang quản lý tài khoản cá nhân cho khách hàng đã đăng nhập: xem thông tin, cập nhật hồ sơ, quản lý đơn hàng, ví điện tử và tài khoản ngân hàng.

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

### 6.6 Yêu Cầu Hoàn Trả (Return Order)
- **Route:** `GET /my-account/orders/{id}/return`
- **Controller:** `Frontend\AccountController@returnOrderForm`
- **Nghiệp vụ:**
  - Áp dụng cho đơn hàng `completed` hoặc `shipped`.
  - Khách hàng nhập lý do, ghi chú và tải lên tối đa 5 ảnh minh họa.
  - Sau khi Admin duyệt, khách hàng nộp thông tin vận chuyển (mã vận đơn, chứng từ).

---

### 6.7 Quản Lý Tài Khoản Ngân Hàng
- **Mô tả:** Khách hàng lưu thông tin tài khoản ngân hàng để nhận tiền hoàn trả hoặc rút tiền ví.
- **Model:** `UserBankAccount`
- **Thông tin:** Tên ngân hàng, Số tài khoản, Tên chủ tài khoản, Chi nhánh.

---

### 6.8 Ví Điện Tử (Wallet)
- **Controller:** `Frontend\WalletController`
- **Mô tả:** Khách hàng sử dụng ví điện tử để thanh toán đơn hàng thay cho VNPAY/COD.

#### 6.8.1 Xem Số Dư & Lịch Sử Giao Dịch
- **Route:** `GET /my-account/wallet`
- **Hiển thị:** Số dư hiện tại, danh sách giao dịch (nạp/trừ/rút) phân trang.

#### 6.8.2 Yêu Cầu Nạp Tiền
- **Route:** `POST /my-account/wallet/topup`
- **Nghiệp vụ:**
  - Khách hàng nhập số tiền muốn nạp và upload bằng chứng chuyển khoản.
  - Tạo `WalletTopupRequest` với trạng thái `pending`.
  - Admin duyệt → tiền tự động cộng vào ví.
- **Model:** `WalletTopupRequest`

#### 6.8.3 Yêu Cầu Rút Tiền
- **Route:** `POST /my-account/wallet/withdraw`
- **Nghiệp vụ:**
  - Khách hàng nhập số tiền và chọn tài khoản ngân hàng nhận tiền.
  - Tạo `WalletWithdrawRequest` với trạng thái `pending`, tiền bị giữ ngay.
  - Admin duyệt → chuyển khoản thực tế cho khách.
  - Admin từ chối → tiền hoàn lại vào ví.
- **Model:** `WalletWithdrawRequest`

#### 6.8.4 Thanh Toán Bằng Ví
- Khả dụng tại trang Checkout khi số dư ví đủ.
- Trừ tiền ngay khi đặt hàng thành công.

---

## Quyền Hạn
| Hành động | Guest | User |
|-----------|-------|------|
| Xem trang tài khoản | ❌ | ✅ |
| Cập nhật thông tin | ❌ | ✅ |
| Xem đơn hàng | ❌ | ✅ |
| Hủy đơn hàng | ❌ | ✅ |
| Yêu cầu hoàn trả | ❌ | ✅ |
| Sử dụng ví điện tử | ❌ | ✅ |

## Models Liên Quan
- `User` — Trường `wallet_balance`
- `Order`, `OrderItem`, `OrderReturnRequest`
- `UserBankAccount`
- `WalletTopupRequest`, `WalletTransaction`, `WalletWithdrawRequest`
