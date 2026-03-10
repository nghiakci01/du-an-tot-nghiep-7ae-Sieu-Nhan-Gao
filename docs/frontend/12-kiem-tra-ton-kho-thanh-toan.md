# 12. Kiểm tra tồn kho thời gian thực (Real-time Inventory Check)

## 1. Tổng quan
Tính năng này đảm bảo rằng số lượng sản phẩm trong giỏ hàng của khách hàng luôn được xác thực với hệ thống kho hàng tại mỗi bước chuyển đổi trong quy trình thanh toán. Điều này giúp tránh tình trạng khách hàng hoàn tất các bước nhập liệu nhưng không thể đặt hàng do sản phẩm đã hết trong lúc họ đang thực hiện thao tác.

## 2. Mục tiêu
- **Trải nghiệm người dùng (UX):** Thông báo ngay lập tức nếu có biến động kho hàng.
- **Tính chính xác:** Tránh việc "overselling" (bán quá số lượng tồn thực tế).
- **Tăng tỷ lệ chuyển đổi:** Giúp khách hàng điều chỉnh giỏ hàng sớm thay vì bị lỗi ở bước cuối cùng.

---

## 3. Các bước quy trình & Điểm kiểm tra (Checkpoints)

Hệ thống sẽ thực hiện kiểm tra kho qua API AJAX tại mỗi điểm "Tiếp tục" (Continue/Next):

### Bước 1: Thông tin giao hàng (Shipping Information)
- **Hành động:** Khách hàng nhập Họ tên, Số điện thoại, Địa chỉ.
- **Điểm kiểm tra:** Ngay khi nhấn nút **"Tiếp tục bước thanh toán"**.
- **Logic kiểm tra:**
    - Gửi danh sách `variant_id` và `quantity` hiện có trong session/cart lên server.
    - Server kiểm tra `stock_quantity` của từng `ProductVariant`.
- **Kết quả:**
    - **Hợp lệ:** Chuyển sang Bước 2 (Phương thức thanh toán).
    - **Không hợp lệ:** Hiển thị danh sách sản phẩm bị thiếu/hết hàng bằng Toast hoặc Modal và dừng quy trình chuyển bước.

### Bước 2: Phương thức thanh toán & Vận chuyển (Payment & Shipping)
- **Hành động:** Khách hàng chọn đơn vị vận chuyển và phương thức thanh toán (COD, Chuyển khoản, VNPAY).
- **Điểm kiểm tra:** Ngay khi nhấn nút **"Tiếp tục chuyển sang Xác nhận"**.
- **Logic kiểm tra:** Tương tự Bước 1, kiểm tra lại một lần nữa để phòng trường hợp có người khác vừa mua mất trong vài phút qua.
- **Kết quả:** Nếu có thay đổi, yêu cầu khách hàng cập nhật lại đơn hàng.

### Bước 3: Xác nhận & Đặt hàng (Confirm & Place Order)
- **Hành động:** Khách hàng xem lại tổng tiền và nhấn **"Đặt hàng"**.
- **Điểm kiểm tra:** Tại Controller xử lý `store()` (Backend).
- **Logic kiểm tra:** Sử dụng `DB::beginTransaction()` và `lockForUpdate()` để đảm bảo tính duy nhất và chính xác tuyệt đối tại thời điểm trừ kho.

---

## 4. Đặc tả kỹ thuật (Technical Specifications)

### API Endpoint: `/api/checkout/check-inventory`
- **Method:** `POST`
- **Request Body:**
  ```json
  {
    "items": [
      {"variant_id": 10, "quantity": 2},
      {"variant_id": 12, "quantity": 1}
    ]
  }
  ```
- **Response (Success):** `{"success": true, "message": "Kho hàng hợp lệ"}`
- **Response (Error):**
  ```json
  {
    "success": false,
    "errors": [
      {
        "variant_id": 10,
        "name": "Áo Thun Nam - Size L - Đen",
        "available": 0,
        "message": "Sản phẩm này đã hết hàng."
      }
    ]
  }
  ```

---

## 5. Giao diện & Trải nghiệm (UI/UX)
- **Nút bấm:** Trong lúc kiểm tra (Ajax call), các nút "Tiếp tục" phải hiển thị trạng thái `Loading` (Spinner) và bị `disabled`.
- **Thông báo lỗi:**
    - Sử dụng **SweetAlert2** để hiển thị thông báo lỗi nổi bật.
    - Liệt kê rõ sản phẩm nào bị thiếu và số lượng còn lại tối đa là bao nhiêu.
- **Xử lý đặc biệt:** Nếu sản phẩm đã hoàn toàn hết hàng (`available = 0`), cung cấp nút "Tự động xóa và tiếp tục" để tiện lợi cho khách.

---

## 6. Các trường hợp ngoại lệ (Edge Cases)
- **Sản phẩm bị ngưng kinh doanh:** Nếu sản phẩm bị Admin ẩn (`is_active = 0`) trong lúc khách đang checkout -> Báo lỗi như trường hợp hết hàng.
- **Mã giảm giá hết hiệu lực:** Nếu việc thay đổi số lượng do hết hàng làm tổng tiền không đủ điều kiện dùng mã giảm giá -> Yêu cầu khách nhập lại hoặc tự động gỡ mã.
- **Lỗi kết nối:** Khi API check kho thất bại do mạng -> Hiển thị thông báo "Không thể kết nối hệ thống, vui lòng thử lại".
