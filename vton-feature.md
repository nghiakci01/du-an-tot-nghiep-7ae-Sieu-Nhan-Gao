# Kế Hoạch Triển Khai: Tính Năng Thử Đồ AI (Virtual Try-On)

## Overview
Tính năng cho phép người dùng tải ảnh cá nhân lên và dùng AI để "mặc thử" sản phẩm quần áo ngay trên website.
Lựa chọn thực tế: Sử dụng **Replicate API (Mô hình Kolors VTON)** để xử lý, kết hợp luồng Post-try-on cho phép tải ảnh về hoặc Thêm thẳng vào giỏ hàng, và Validate cơ bản đối với ảnh người dùng upload.

## Project Type
WEB

## Success Criteria
1. Button "Thử đồ AI" và Modal hoạt động trơn tru trên trang chi tiết sản phẩm.
2. Upload ảnh có Validate (chỉ JPG/PNG/WEBP, max 10MB).
3. Controller gọi API Replicate thành công (Có Mock mode để test UI khi chưa set API Key).
4. Hiển thị ảnh Preview kết quả rõ ràng.
5. Người dùng có thể Download ảnh hoặc bấm nút "Thêm vào giỏ hàng" từ modal VTON.

## Tech Stack
- Frontend: Blade, Bootstrap Modal, AJAX form submit, SweetAlert2.
- Backend: Laravel `VtonController`, HTTP Client (tương tác Replicate).
- AI Provider: Replicate (`cuiapp/kolors-virtual-try-on`).

## File Structure
- `app/Http/Controllers/Frontend/VtonController.php` (Đã có base, xác minh JS fetch)
- `resources/views/frontend/products/show.blade.php` (Chứa form upload, AJAX loader, & luồng Post-try-on)
- `routes/web.php` (Đường dẫn POST `/vton/try-on`)

## Task Breakdown

### Task 1: Hoàn thiện UI Upload & Validate (Agent: `frontend-specialist`)
- **INPUT:** `show.blade.php` Modal `aiTryOnModal`.
- **OUTPUT:** Thêm script JS xử lý sự kiện submit `#vtonForm` bằng AJAX. Validate file size (10MB) & hợp lệ loại file. Chuyển đổi trạng thái Loading UI.
- **VERIFY:** JS intercept form, gọi AJAX mượt mà không load lại trang, hiển thị loading đúng cách.

### Task 2: API Integration & Response Handling (Agent: `backend-specialist`)
- **INPUT:** `VtonController.php`
- **OUTPUT:** Đảm bảo Controller nhận và xử lý token Replicate hoặc chạy MockMode. Trả về JSON structure: `{ success, image_url, message }`.
- **VERIFY:** Gọi endpoint VTON trả về base64/URL hợp lệ.

### Task 3: Post-Try-On Flow (Add to Cart / Download) (Agent: `frontend-specialist`)
- **INPUT:** Element khu vực kết quả (`#vton-result`) trong `show.blade.php`.
- **OUTPUT:**
  1. Set link nút "Tải ảnh về" (Gắn URL vào `<a>` href và set `download`).
  2. Thêm HTML nút "Thêm vào giỏ hàng ngay" dưới ảnh preview.
  3. Viết JS: Khi bấm nút này, trigger lệnh submit form giỏ hàng ảo (`#btn-add-to-cart`) ngoại vi hoặc gửi fetch requuest add-to-cart.
- **VERIFY:** Thêm giỏ hàng thành công từ phòng thay đồ AI.

## Phase X: Verification Plan
- **Manual Verification:**
  1. Mở trang chi tiết thẻ sản phẩm. Bấm "Thử Đồ Thực Tế Ảo".
  2. Test Validate: Upload file > 10MB => Có popup báo đỏ.
  3. Upload file đúng (JPG) => Bấm nút => Hiện Spinner (`Trạng thái đợi AI xử lý từ 20-30s`).
  4. Trả về kết quả => Ảnh hiển thị rõ trong Modal.
  5. Bấm download => Tải được ảnh.
  6. Bấm Thêm giỏ hàng => Trigger add to cart, báo thành công.
