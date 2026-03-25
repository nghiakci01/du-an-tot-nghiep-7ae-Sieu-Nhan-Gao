# Admin 04. Quản Lý Đơn Hàng

## Mô tả
Module xử lý toàn bộ vòng đời đơn hàng từ khi nhận đến khi hoàn thành hoặc hủy.

---

## Chức Năng

### A4.1 Danh Sách Đơn Hàng
- **Route:** `GET /admin/orders`
- **Controller:** `Admin\OrderController@index`
- **Bộ lọc:**
  - Theo trạng thái: pending / confirmed / shipped / completed / cancelled
  - Theo phương thức thanh toán
  - Theo khoảng ngày
  - Tìm theo tên khách / email / mã đơn
- **Phân trang:** 15 đơn/trang, sắp xếp mới nhất

---

### A4.2 Chi Tiết Đơn Hàng
- **Route:** `GET /admin/orders/{id}`
- **Controller:** `Admin\OrderController@show`
- **Hiển thị:**
  - Thông tin khách hàng và địa chỉ giao hàng
  - Danh sách sản phẩm (ảnh, tên, biến thể, số lượng, đơn giá, thành tiền)
  - Tổng tiền hàng, giảm giá coupon, phí ship, tổng thanh toán
  - Phương thức và trạng thái thanh toán
  - Nhật ký lịch sử trạng thái (OrderHistory)

---

### A4.3 Cập Nhật Trạng Thái Đơn Hàng
- **Route:** `PUT /admin/orders/{id}`
- **Controller:** `Admin\OrderController@update`
- **Nghiệp vụ (State Machine):**
  ```
  pending   → confirmed | cancelled
  confirmed → shipped   | cancelled
  shipped   → completed | returned | failed
  completed → returned
  ```
  - Mỗi thay đổi được ghi vào `order_histories` kèm ghi chú.
  - Gửi thông báo cho khách hàng khi trạng thái thay đổi (nếu cấu hình mail).

---

### A4.4 Xác Nhận Thanh Toán (Chuyển Khoản)
- **Route:** `POST /admin/orders/{id}/confirm-payment`
- **Controller:** `Admin\OrderController@confirmPayment`
- **Mô tả:** Admin xác nhận đã nhận tiền chuyển khoản → cập nhật `payment_status = 'paid'`.

---

### A4.5 In Hóa Đơn
- **Route:** `GET /admin/orders/{id}/print`
- **Controller:** `Admin\OrderController@print`
- **Mô tả:** Mở trang in với layout đơn giản phù hợp máy in. Sử dụng `window.print()` trên browser.

---

### A4.6 Tìm Kiếm Khách Hàng (AJAX)
- **Route:** `GET /admin/orders/customers/search`
- **Mô tả:** Gợi ý tìm kiếm khách hàng khi tạo đơn hàng thủ công.

---

### A4.7 Tự Động Hủy Đơn Quá Hạn
- **Route:** `POST /admin/orders-trigger-auto-cancel`
- **Mô tả:** Trigger thủ công để hủy các đơn `pending` quá N giờ không được xác nhận.
- **Nghiệp vụ:** Thường được gọi bởi Scheduler/Cron job.

### A4.8 Truy Vấn & Hoàn Tiền VNPAY *(MỚI)*
- **Route (query):** `POST /admin/orders/{id}/query-payment`
- **Route (refund):** `POST /admin/orders/{id}/refund-payment`
- **Controller:** `Admin\\OrderController@queryPayment` / `@refundPayment`
- **Mô tả:**
  - **Query:** Tra cứu trạng thái thanh toán thực tế từ cổng VNPAY (phòng trường hợp callback thất bại)
  - **Refund:** Khởi tạo yêu cầu hoàn tiền tự động về thẻ/ví khách hàng qua VNPAY API
- **Phân quyền:** Chỉ Admin

---

### A4.9 Quản Lý Hoàn Trả (Return Requests) *(MỚI)*
- **Route:** `GET /admin/returns`
- **Controller:** `Admin\OrderReturnController`
- **Quy trình xử lý:**
  1. **Danh sách:** Xem toàn bộ yêu cầu hoàn trả từ khách hàng.
  2. **Duyệt (Approve):** Chấp nhận yêu cầu, yêu cầu khách gửi hàng về kho. Trạng thái: `pending` → `approved`.
  3. **Đang gửi (Shipping):** Cập nhật khi khách nộp bằng chứng gửi hàng. Trạng thái: `approved` → `shipping`.
  4. **Đã nhận (Received):** Kho xác nhận đã nhận được hàng. Trạng thái: `shipping` → `received`.
  5. **Hoàn tất (Complete):** Xác nhận hoàn tiền thành công cho khách. Trạng thái: `received` → `completed`. Đơn hàng gốc chuyển sang `returned`, trạng thái thanh toán là `refunded`.
  6. **Từ chối (Reject):** Từ chối yêu cầu hoàn trả nếu không hợp lệ.
- **Thông báo:** Hệ thống tự động gửi thông báo cho khách hàng qua mỗi bước chuyển trạng thái.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem danh sách & chi tiết | ✅ | ✅ |
| Cập nhật trạng thái | ✅ | ✅ |
| Xác nhận thanh toán | ✅ | ✅ |
| In hóa đơn | ✅ | ✅ |
| VNPAY Query / Refund | ❌ | ✅ |

## Models Liên Quan
- `Order`, `OrderItem`, `OrderHistory`
- `OrderReturnRequest` — Quản lý thông tin và hình ảnh yêu cầu hoàn trả
- `Order.shipping_provider` — Nhà vận chuyển được gắn với đơn hàng

