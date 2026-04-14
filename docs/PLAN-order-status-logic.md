# Cập nhật Logic Trạng thái Đơn hàng (Bỏ chặn Confirm cho đơn chưa thanh toán)

## Context
Hiện tại trong `App\Services\OrderService`, hệ thống đang có một đoạn code chặn gắt gao:
```php
if ($order->payment_method !== 'COD' && $order->payment_status !== 'paid') {
    if (!in_array($newStatus, [Order::STATUS_CANCELLED, Order::STATUS_FAILED])) {
        throw new Exception("Không thể chuyển trạng thái (sang {$newStatus}) do khách chưa hoàn tất thanh toán Online.");
    }
}
```
Mục đích ban đầu của đoạn code này là để chặn việc quản trị viên lỡ tay "Giao hàng" cho những đơn thanh toán qua VNPay bị lỗi (chưa trả tiền). 
**Tuy nhiên, nó gây ra tác dụng phụ:** 
1. Khi tạo đơn hàng tại cửa hàng, nếu chọn phương thức **CASH** (Tiền mặt) nhưng không được đánh dấu là paid, đơn sẽ bị kẹt cứng không thể đổi sang "Đã xác nhận".
2. Đối với đơn **BANK_TRANSFER** (Chuyển khoản thủ công), quản trị viên muốn bấm "Xác nhận đơn" để thông báo cho khách chuyển tiền thì bị chặn.

## 🧠 Brainstorm: Các hướng giải quyết

### Option A: Chỉ chặn việc "Giao hàng" (Shipping) và "Hoàn thành" (Completed)
Thay vì chặn mọi trạng thái, chúng ta sẽ chỉ cấm chuyển sang trạng thái `shipped` (Giao hàng) hoặc `completed` (Hoàn thành) nếu đơn online chưa thanh toán. Quản trị viên vẫn được phép đổi sang `confirmed` (Đã xác nhận) hoặc `processing` (Đang xử lý).

✅ **Pros:** Phù hợp thực tế e-commerce.
❌ **Cons:** Không có.
📊 **Effort:** Low

---

### Option B: Thêm `CASH` và `BANK_TRANSFER` vào danh sách miễn trừ (Giống như COD)
Sửa câu lệnh if thành kiểm tra: `if (!in_array($order->payment_method, ['COD', 'CASH', 'BANK_TRANSFER']) ...)`

✅ **Pros:** Dễ hiểu.
❌ **Cons:** Có thể khiến Admin lỡ tay giao hàng cho đơn BANK_TRANSFER dù chưa nhận tiền.
📊 **Effort:** Low

---

### Option C: Cho phép mọi trạng thái nếu đơn do Admin tạo
Nếu Admin có quyền, admin được bypass mọi check.

✅ **Pros:** Linh hoạt tuyệt đối cho Admin.
❌ **Cons:** Admin dễ thao tác nhầm, bỏ lọt đơn chưa thanh toán.
📊 **Effort:** Medium

---

## 💡 Recommendation

**Khuyên dùng: Option A kết hợp Option B.**
1. Nới lỏng logic: Cho phép đơn hàng chưa thanh toán được quyền chuyển sang Trạng thái `confirmed` (để in hóa đơn, chuẩn bị hàng...).
2. Bỏ chặn đối với phương thức `CASH`: Vì `CASH` là trả bằng tiền mặt, nó có tính chất tương tự như thu hộ (`COD`), người vận hành có quyền kiểm soát linh hoạt.

---

**Bạn muốn tôi cấu hình theo tùy chọn nào hoặc theo ý Recommendation không?** Mọi code implementation sẽ dừng lại đợi câu trả lời từ bạn.
