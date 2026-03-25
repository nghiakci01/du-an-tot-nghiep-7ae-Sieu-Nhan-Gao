# PLAN: Thêm ô nhập Video vào trang Hoàn trả

## 🧠 Brainstorm

### Context
Trang yêu cầu hoàn trả (`/my-account/orders/{id}/return`) hiện chỉ hỗ trợ upload **ảnh** (tối đa 4 ảnh, max 2MB/ảnh). Cần thêm ô upload **video** để khách hàng minh chứng lỗi sản phẩm rõ ràng hơn.

**Files liên quan:**
- [return_form.blade.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/resources/views/frontend/account/orders/return_form.blade.php) — Form UI
- [AccountController.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Http/Controllers/Frontend/AccountController.php) — `submitReturnRequest()` (line 151)
- [OrderReturnRequest.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Models/OrderReturnRequest.php) — Model
- Migration: `order_return_requests` table — `images` JSON column
- [show.blade.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/resources/views/admin/orders/show.blade.php) — Admin hiển thị minh chứng

---

### Option A: Cột `videos` riêng biệt (JSON)

Tạo migration thêm cột `videos` JSON nullable, tách biệt hoàn toàn khỏi `images`.

✅ **Pros:**
- Dữ liệu sạch, tách biệt rõ ràng images vs videos
- Query/filter theo loại dễ dàng
- Không ảnh hưởng logic hiện tại của `images`

❌ **Cons:**
- Cần thêm migration mới
- Phải cập nhật Model `$fillable` + `$casts`
- Admin view cần thêm block render video riêng

📊 **Effort:** Medium

---

### Option B: Gộp video vào cột `images` hiện tại

Dùng lại cột `images` JSON, lưu cả ảnh lẫn video paths cùng nhau. Phân biệt bằng extension khi render.

✅ **Pros:**
- Không cần migration
- Ít thay đổi model/controller

❌ **Cons:**
- Tên cột `images` gây hiểu nhầm khi chứa videos
- Logic render phức tạp hơn (phải check extension)
- Khó maintain về lâu dài

📊 **Effort:** Low

---

### Option C: Cột `videos` riêng + dùng `move()` thay `store()`

Giống Option A nhưng dùng `move()` thay vì `store()` (tránh lỗi `getRealPath()` như avatar).

✅ **Pros:**
- Tất cả ưu điểm của Option A
- Tránh được bug `getRealPath()` đã gặp trước đó trên Windows/Laragon
- Nhất quán với cách đã fix avatar upload

❌ **Cons:**
- Cần thêm migration
- Effort hơi cao hơn Option B

📊 **Effort:** Medium

---

## 💡 Recommendation

**Option C** — vì:
1. Cột riêng `videos` giữ dữ liệu sạch
2. Dùng `move()` tránh lỗi `getRealPath()` đã xảy ra
3. Cũng nên đổi cả images sang `move()` cho nhất quán

---

## Proposed Changes

### Phase 1: Database

#### [NEW] Migration `add_videos_to_order_return_requests_table`
- Thêm cột `videos` JSON nullable vào `order_return_requests`

#### [MODIFY] [OrderReturnRequest.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Models/OrderReturnRequest.php)
- Thêm `'videos'` vào `$fillable`
- Thêm `'videos' => 'array'` vào `$casts`

---

### Phase 2: Controller

#### [MODIFY] [AccountController.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/app/Http/Controllers/Frontend/AccountController.php)

Tại `submitReturnRequest()` (line 151):
- Thêm validation: `'videos.*' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200'` (50MB)
- Thêm logic upload video bằng `move()` vào `storage/app/public/returns/videos/`
- Truyền `$videoPaths` vào `OrderReturnRequest::create()`
- Cũng đổi images sang dùng `move()` cho nhất quán

---

### Phase 3: Frontend View

#### [MODIFY] [return_form.blade.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/resources/views/frontend/account/orders/return_form.blade.php)

- Thêm CSS cho `.video-upload-wrap` (giống `.image-upload-wrap`)
- Thêm ô upload video sau ô upload ảnh:
  - Icon: `bi-camera-reels`
  - Accept: `video/*`
  - Text: "Tải video lên (Tối đa 1 video, 50MB)"
  - Input name: `videos[]`
- Thêm preview video (`<video>` tag) trong JS

---

### Phase 4: Admin View

#### [MODIFY] [show.blade.php](file:///c:/laragon/www/du-an-tot-nghiep-7ae-Sieu-Nhan-Gao/resources/views/admin/orders/show.blade.php)

- Thêm block hiển thị video nếu `$order->returnRequest->videos` có dữ liệu
- Dùng `<video controls>` tag để admin xem trực tiếp

---

## Verification Plan

1. Vào `/my-account/orders/{id}/return`
2. Upload cả ảnh và video → submit → không lỗi
3. Kiểm tra file tồn tại trong `storage/app/public/returns/videos/`
4. Kiểm tra admin order detail hiển thị video đúng
5. Submit form không có video → vẫn hoạt động bình thường

---

## Checklist

- [ ] Create migration `add_videos_to_order_return_requests_table`
- [ ] Run `php artisan migrate`
- [ ] Update `OrderReturnRequest` model (`$fillable` + `$casts`)
- [ ] Update `AccountController::submitReturnRequest()` (validation + upload)
- [ ] Update `return_form.blade.php` (video upload UI + preview JS)
- [ ] Update admin `show.blade.php` (render video)
- [ ] Test: upload video + ảnh cùng lúc
- [ ] Test: submit mà không có video
