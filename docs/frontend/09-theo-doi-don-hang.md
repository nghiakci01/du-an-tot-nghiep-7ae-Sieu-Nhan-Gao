# 09. Theo Dõi Đơn Hàng (Order Tracking)

## Mô tả
Cho phép khách hàng vãng lai (chưa đăng nhập) tra cứu trạng thái đơn hàng bằng mã đơn và thông tin liên hệ.

---

## Chức Năng

### 9.1 Trang Tra Cứu
- **Route:** `GET /order-tracking`
- **Controller:** `Frontend\OrderTrackingController@index`
- **Mô tả:** Hiển thị form tra cứu đơn hàng.

---

### 9.2 Tìm Kiếm Đơn Hàng
- **Route:** `POST /order-tracking/search`
- **Controller:** `Frontend\OrderTrackingController@search`
- **Đầu vào:**
  - `order_id` — Mã đơn hàng
  - `email` hoặc `phone` — Thông tin xác minh
- **Đầu ra:** Thông tin đơn hàng và lịch sử trạng thái.
- **Nghiệp vụ:** Phải khớp cả mã đơn lẫn email/số điện thoại mới trả về kết quả.

---

### 9.3 Xem Nhanh Đơn Hàng
- **Route:** `GET /view-order/{id}`
- **Controller:** `Frontend\GuestOrderController@show`
- **Mô tả:** Xem chi tiết đơn hàng theo ID (thường dùng qua link email xác nhận).
