# Xóa Chức năng Tạo Đơn Hàng (Add Order)

## Context
Bạn đã quyết định xóa bỏ hoàn toàn tính năng "Thêm đơn hàng" (Tạo đơn hàng thủ công tại quầy/Admin). Việc này sẽ giúp tinh gọn hệ thống Dashboard, dồn toàn bộ luồng tạo đơn hàng sang phía giao diện người mua (Frontend - Website mua sắm). 

## 🧠 Brainstorm: Các phương án thực hiện

### Option A: Xóa Triệt Để (Hard Delete - Khuyên dùng)
Xóa tất cả các nút bấm, file giao diện (blade), route API và logic xử lý trong Controller liên quan đến việc tạo đơn hàng.

✅ **Pros:**
- Gọn nhẹ codebase, không để lại code chết (dead code).
- Tính bảo mật cao nhất (chặn tuyệt đối việc truy cập thông qua URL ẩn).

❌ **Cons:**
- Tốn công khôi phục nếu trong quá trình kinh doanh sau này lại muốn có chức năng "Tạo đơn tại quầy" cho nhân viên.

📊 **Effort:** Medium

---

### Option B: Chỉ "Ẩn" Giao Diện (Soft Hide)
Chỉ tiến hành xóa nút bấm "Thêm đơn hàng" ở giao diện trang chủ Đơn hàng (`index.blade.php`). Toàn bộ API và code xử lý đằng sau vẫn được giữ nguyên. 

✅ **Pros:**
- Triển khai cực nhanh (xóa 1 dòng code HTML).
- Rất dễ khôi phục khi cần thiết.

❌ **Cons:**
- Lỗ hổng bảo mật nhỏ: Nếu nhân viên nhớ URL `admin/orders/create`, họ vẫn có thể truy cập và tạo đơn trái phép.
- Để lại dead code phình to hệ thống.

📊 **Effort:** Low

---

### Option C: Vô Hiệu Hóa Từ Route (Disable via Route)
Ngoài việc xóa nút bấm ở Frontend, ta sẽ khai báo khóa lại route bằng middleware chặn hoặc dùng `except(['create', 'store'])`. Code file blade vẫn giữ lại để dự phòng.

✅ **Pros:**
- Khắc phục lỗi bảo mật của Option B, ngăn chặn truy cập 100%.
- Vẫn có thể khôi phục dễ dàng bằng cách mở route lại.

❌ **Cons:**
- Vẫn còn `create.blade.php` và các Controller thừa thãi không bao giờ dùng tới làm rác dự án.

📊 **Effort:** Low

---

## 💡 Recommendation

**Khuyên dùng: Option A (Xóa triệt để) hoặc Option C (Xóa nút và khóa Route).**
Tuy nhiên code sạch luôn là ưu tiên hàng đầu, do đó **Option A** là lựa chọn tuyệt vời nhất để đảm bảo dự án gọn gàng, giảm bớt lỗi phát sinh ở những cụm logic không dùng đến.

---

## Kế hoạch triển khai cấu hình (Nếu chọn Option A)

✅ **Bước 1: Chỉnh sửa `routes/web.php`**
- Bỏ các route phục vụ riêng cho tạo đơn: `admin.orders.customers.search` và `admin.api.variants.search`.
- Cập nhật route resource thành `Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store']);`

✅ **Bước 2: Dọn dẹp Controller**
- Xóa các hàm `create()`, `store()`, `customersSearch()` trong `OrderController.php`.
- Xóa hàm `variantsSearch()` trong `ProductController.php`.

✅ **Bước 3: Dọn dẹp Files**
- Xóa Nút bấm "Thêm đơn hàng" ở `resources/views/admin/orders/index.blade.php`.
- Xóa hẳn luôn file `resources/views/admin/orders/create.blade.php`.

---
**Vui lòng cho tôi biết bạn muốn chọn Option nào (A, B hay C)?** Sau khi bạn xác nhận, tôi sẽ bắt đầu dùng lệnh `/create` chuyên sâu để sửa code ngay! 
