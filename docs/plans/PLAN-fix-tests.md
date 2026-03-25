## 🧠 Brainstorm: Fix 14 lỗi PHPUnit Test còn lại

### Context
Sau khi sửa lỗi MySQL raw query trong migrations, số lượng test fail đã từ 95 giảm xuống chỉ còn 14 lỗi cơ bản trên tổng số 96 tests (82 tests passed). 
Phân tích sơ bộ cho thấy hầu hết các lỗi này là do **sai lệch Test với thực tế Code hiện tại** (ví dụ: route URL đã được đổi tên nhưng test file chưa cập nhật), hoặc các ràng buộc database nghiêm ngặt trên test environment (SQLite).

---

### Phân rã nhóm lỗi (14 lỗi)

1. **Nhóm Lỗi 404 (Sai Route / URL bị đổi)** - *Khoảng 7 lỗi*
   - Các file: `AccountTest`, `AdminBrandTest`, `AdminDashboardTest`, `AdminSettingTest`, `CartValidationTest`, `ProductTest`, `ReviewTest`.
   - Nguyên nhân: Các file Test đang gọi tới URL cũ e.g., `/account` (đúng: `/my-account`), `/products` (đúng: `/shop`), hoặc `/admin` chưa redirect chuẩn trong Test.

2. **Nhóm Lỗi Text Assertions (Dịch thuật / Đã đổi UI message)** - *2 lỗi*
   - `CheckoutAuditTest`: Kì vọng thông báo lỗi "Giỏ hàng của bạn đang trống." nhưng thực tế hệ thống đang trả về "Vui lòng chọn sản phẩm trong giỏ hàng..."
   - `ProductDetailAuditTest`: Kì vọng thấy text "0 VND" nhưng format giá hiển thị trên UI đã bị thay đổi (hiển thị khác, ví dụ Liên hệ hoặc format tiền tệ kiểu khác).

3. **Nhóm Lỗi Database Integrity & Logic (Lỗi CHECK Constraint / Xóa dữ liệu ròng)** - *5 lỗi*
   - User Factory / Seeding insert role `ADMIN` (viết hoa) trong khi constraint SQLite quy định chữ thường.
   - `Admin\CategoryTest`: Xóa category có danh mục con chưa chặn đúng logic dẫn đến thiếu flash session error.
   - Lỗi Checkout `Attempt to read property "id" on null` do đơn hàng chưa thực sự được tạo ra trong Unit Test (bị kẹt ở logic thanh toán hoặc validation lỗi).

---

### Option A: Clean Update (Cập nhật Test để khớp ứng dụng thực)
Cập nhật nội dung các file thư mục `tests/` để phản ánh đúng thực trạng tính năng hiện tại của ứng dụng.

✅ **Pros:**
- Gồm các sửa chữa đơn giản (đổi text, đổi đường dẫn URI, fix chữ hoa/thường).
- Đảm bảo Test bao phủ thực tế ứng dụng.
- Phản ánh đúng chức năng đang chạy tốt trên Product Catalog và Giỏ hàng.

❌ **Cons:**
- Mất thời gian rà soát lại Request Validation trong test do Checkout đang fail.

📊 **Effort:** Medium

---

### Option B: Core Adjustments (Đổi ngược code App để tuân thủ Test)
Sửa code của Controller, Form Request để tuân thủ 100% logic đã được quy định cứng từ trước trong file Test.

✅ **Pros:**
- Không cần đụng tới thư mục tests/.

❌ **Cons:**
- Gây rủi ro cho code ứng dụng đang hoạt động tốt.
- Thay đổi các URL thân thiện SEO (như `/shop`, `/my-account` trở lại thành `/products`, `/account`) là không cần thiết.

📊 **Effort:** High

---

## 💡 Recommendation

**Option A (Clean Update)** là hợp lý và chuẩn xác nhất vì dự án đã có nhiều nâng cấp về UI, Routing và Logic, nhưng các Test File lại quá cũ kỹ chưa được cập nhật tương ứng.

Kế hoạch thực thi Phase 3 (Fix 14 Tests) sẽ diễn ra như sau:
1. Fix Category Database Seeders / Factories (Đổi `ADMIN` thành `admin`).
2. Fix 7 lỗi 404 Routes: Sửa cứng các Uri path trong thư mục `tests/Feature/`.
3. Cập nhật Content Message: Sửa `assertSessionHas` để khớp thông điệp tiếng Việt mới.
4. Rà soát logic CheckoutTest: Bơm đúng Payload cần thiết giả lập Checkout để Order được tạo ra trong CSDL.

Bạn ưu tiên xử lý các lỗi nào trước, hay chốt phương án Option A để tôi tự động cày cuốc qua 14 lỗi này?
