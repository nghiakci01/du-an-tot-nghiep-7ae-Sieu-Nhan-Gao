# 05. Thanh Toán (Checkout & Payment)

## Mô tả
Module xử lý toàn bộ quy trình đặt hàng và thanh toán bao gồm các phương thức: COD (tiền mặt khi nhận hàng), chuyển khoản ngân hàng, VNPAY và ví điện tử.

---

## Chức Năng

### 5.1 Trang Checkout
- **Route:** `GET /checkout`
- **Controller:** `Frontend\CheckoutController@index`
- **Mô tả:** Form điền thông tin giao hàng và chọn phương thức thanh toán.
- **Thông tin cần nhập:**
  - Họ tên, email, số điện thoại
  - Tỉnh/thành, địa chỉ giao hàng đầy đủ
  - Ghi chú đơn hàng (tùy chọn)
  - Phương thức thanh toán
- **Nghiệp vụ:**
  - Nếu đã đăng nhập → tự động điền thông tin từ profile.
  - Tính phí vận chuyển dựa theo tỉnh/thành.
  - Hiển thị tóm tắt đơn hàng từ giỏ hàng.

---

### 5.2 Đặt Hàng
- **Route:** `POST /checkout`
- **Controller:** `Frontend\CheckoutController@store`
- **Đầu vào:** Thông tin giao hàng + phương thức thanh toán
- **Phương thức thanh toán:**
  | Mã | Tên | Nghiệp vụ |
  |----|-----|----------|
  | `cod` | Tiền mặt khi nhận hàng | Tạo đơn → trạng thái `pending` |
  | `bank_transfer` | Chuyển khoản | Tạo đơn → hiển thị QR code ngân hàng |
  | `vnpay` | VNPAY | Tạo đơn → chuyển hướng sang cổng VNPAY |
  | `wallet` | Ví điện tử | Trừ tiền từ `wallet_balance` ngay khi đặt hàng |
- **Điều kiện thanh toán ví:** Số dư ví ≥ tổng giá trị đơn hàng. Tùy chọn này chỉ hiển thị khi số dư đủ.
- **Nghiệp vụ sau đặt hàng:**
  1. Tạo bản ghi `Order` và các `OrderItem`.
  2. Giảm tồn kho biến thể sản phẩm tương ứng.
  3. Xóa dữ liệu giỏ hàng khỏi session.
  4. Tăng `used_count` của coupon (nếu có).
  5. Ghi điểm tích lũy cho khách hàng (nếu có).
  6. Gửi thông báo cho admin.

---

### 5.3 Trang Đặt Hàng Thành Công
- **Route:** `GET /checkout/success/{id}`
- **Controller:** `Frontend\CheckoutController@success`
- **Mô tả:** Hiển thị thông tin đơn hàng vừa đặt và hướng dẫn thanh toán (nếu chuyển khoản).

---

### 5.4 Xác Nhận Chuyển Khoản
- **Route:** `POST /checkout/order/{id}/confirm-transfer`
- **Controller:** `Frontend\CheckoutController@confirmTransfer`
- **Mô tả:** Khách hàng xác nhận đã thực hiện chuyển khoản.
- **Nghiệp vụ:** Cập nhật `payment_status = 'pending_verification'`, gửi thông báo cho admin xác minh.

---

### 5.5 Hủy Đơn Hàng (Khách hàng)
- **Route:** `POST /checkout/order/{id}/cancel`
- **Controller:** `Frontend\CheckoutController@cancelOrder`
- **Nghiệp vụ:**
  - Chỉ hủy được khi trạng thái là `pending`.
  - Hoàn lại tồn kho sản phẩm.

---

### 5.6 Thanh Toán VNPAY
- **Route tạo:** `GET /vnpay/payment/{order_id}`
- **Route callback:** `GET /vnpay/callback`
- **Controller:** `Frontend\PaymentController`
- **Mô tả:** Tích hợp cổng thanh toán VNPAY.
- **Luồng xử lý:**
  1. Tạo URL thanh toán VNPAY với chữ ký bảo mật (HMAC-SHA512).
  2. Chuyển hướng người dùng sang VNPAY.
  3. VNPAY callback → xác minh chữ ký → cập nhật `payment_status`.
  - `vnp_ResponseCode = '00'` → Thành công
  - Các mã khác → Thất bại/Hủy

---

## Trạng Thái Đơn Hàng

```
pending → confirmed → shipped → completed
                   ↘ cancelled
                              ↘ returned
                              ↘ failed
```

## Models Liên Quan
- `Order` — Bảng `orders`
- `OrderItem` — Bảng `order_items`
- `OrderHistory` — Bảng `order_histories` (nhật ký thay đổi trạng thái)
- `Coupon` — Bảng `coupons`
- `BankSetting` — Bảng `bank_settings` (thông tin tài khoản ngân hàng QR)
- `WalletTransaction` — Bảng `wallet_transactions` (giao dịch ví khi thanh toán)
