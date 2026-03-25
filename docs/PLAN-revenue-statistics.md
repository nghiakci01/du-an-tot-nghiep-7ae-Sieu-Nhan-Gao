## 🧠 Brainstorm: Thống kê doanh số theo Tuần, Tháng, Quý, Năm

### Context
Dashboard admin hiện tại đang có bộ lọc theo khoảng thời gian tùy chỉnh (`start_date` và `end_date`), nhưng thiếu các tùy chọn (preset) có sẵn để người quản trị click nhanh xem báo cáo theo Tuần, Tháng, Quý, và Năm.

---

### Option A: Server-Side Processing (Sử dụng tham số GET & Load lại trang)
Thêm các nút/dropdown filter (Tuần này, Tháng này, Quý này, Năm nay). Khi click, sẽ submit form với form input hoặc URL tham số `?preset=week`. Tại Controller, tính toán lại các mốc thời gian bằng Carbon để làm `start_date` và `end_date` chuẩn, rồi load lại layout chung.

✅ **Pros:**
- Tái sử dụng lại 100% logic load dữ liệu đang có (Stats cards, Bảng đơn hàng, Phễu).
- Ổn định, dễ maintain, code ngắn gọn nằm chủ yếu ở Controller.

❌ **Cons:**
- Phải load lại toàn bộ trang mỗi lần lọc.

📊 **Effort:** Low

---

### Option B: Client-Side Processing (Sử dụng AJAX API)
Sử dụng endpoint `/api/dashboard/revenue` hiện có. Mở rộng API này để trả về không chỉ Data biểu đồ mà còn Data của cụm Thẻ thống kê (Doanh thu, Lợi nhuận, Đơn hàng...). Sử dụng Javascript để render lại toàn bộ UI.

✅ **Pros:**
- Trải nghiệm người dùng cực mượt mà, render tức thì không giật trang.

❌ **Cons:**
- Cần viết lượng lớn code Javascript để chọc ngoáy DOM và update từng con số text.
- Phải dời logic tính toán thành 2 nơi (Blade lần đầu và API JSON các lần sau).

📊 **Effort:** High

---

### Option C: Tích hợp thư viện DateRangePicker
Phá bỏ input `type="date"` thuần, áp dụng thư viện `daterangepicker.js`. Cấu hình sẵn các preset (Tuần, Tháng, Quý, Năm) ở bên trong script khởi tạo.

✅ **Pros:**
- UI gọn gàng vào chung 1 ô input.
- Hoàn toàn không cần sửa logic Backend (Backend mặc định vẫn nhận đúng `start_date` & `end_date`).

❌ **Cons:**
- Load thêm 1 thư viện bên thứ 3 (Moments.js, daterangepicker). Can thiệp layout frontend có thể gây lỗi style.

📊 **Effort:** Medium

---

## 💡 Recommendation

**Option A** là phương án hiệu quả, ít lỗi tiềm ẩn và dễ dàng phát triển nhất do cấu trúc dữ liệu dashboard hiện tại có rất nhiều Widget nhỏ (Thẻ tổng quan, Phễu chuyển đổi, Bảng đơn mới). Thay đổi sang API (Option B) sẽ bắt bạn viết lại logic render rất lớn trong file `dashboard.blade.php`. Do đó, sử dụng Option A trên Server-side vừa đáp ứng yêu cầu một cách hoàn hảo, vừa đồng bộ với tính năng Xuất Báo Cáo.

---

# Project Plan (PLAN-revenue-statistics)

## Overview
Nâng cấp trang Dashboard Admin (`/admin/dashboard`), bổ sung cấu hình các bộ lọc thao tác 1 chạm (Presets) để tính toán thống kê và biểu đồ theo Tuần, Tháng, Quý, và Năm. 

## Project Type
**WEB** (Backend logic + Blade UI)

## Success Criteria
- [ ] Giao diện cập nhật thêm nhóm Filter Preset bên cạnh Input thời gian (Hoặc Select Dropdown).
- [ ] Khi sử dụng Preset, hiển thị chính xác dữ liệu mốc thời gian tương ứng.
- [ ] Không phá vỡ hệ thống Xuất Báo Cáo Excel/PDF.
- [ ] Biểu đồ Chart render thông tin khớp với ngày hiển thị trên filter.

## Tech Stack
- **Backend:** Laravel Controller, `Carbon` để xử lý Range Time mạnh mẽ.
- **Frontend:** Bootstrap (UI Button, Form), Blade templating.

## File Structure (Files to be modified)
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Services/ReportService.php` (Tùy chọn, để đảm bảo API hoạt động tốt)
- `resources/views/admin/dashboard.blade.php`

## Task Breakdown

### Task 1: Cập nhật UI Bộ lọc (Preset Select / Buttons)
- **Agent:** `frontend-specialist`
- **Priority:** P1
- **Input:** Khối `<form>` chứa input `start_date` & `end_date`.
- **Output:** Thêm một Custom `<select name="preset">` (Hôm nay, Tuần này, Tháng này, Quý này, Năm nay, Tùy chỉnh). Viết script JS nhỏ, nếu user chọn "Tùy chỉnh" => Hiện input nhập ngày. Nếu chọn các preset khác => Ẩn input ngày và auto submit form.
- **Verify:** UI không bị vỡ trên Mobile, Form submit được giá trị `preset`.

### Task 2: Xử lý Timestamp tại DashboardController
- **Agent:** `backend-specialist`
- **Priority:** P1
- **Input:** `$request->preset`.
- **Output:** Dựa vào `preset` (week, month, quarter, year), dùng hàm `Carbon` (`now()->startOfQuarter()`, `now()->startOfYear()`,...) tương ứng để set `$startDate` và `$endDate`. Trả biến $preset hiện tại ra view để re-select.
- **Verify:** Khi request `preset=quarter`, dữ liệu trên UI trả về đúng theo quý này. Khối Input Range Date trên UI tự render ra ngày Start và End của preset đó.

### Task 3: Update `revenueApi` cho tính năng biểu đồ
- **Agent:** `backend-specialist`
- **Priority:** P2
- **Input:** File `DashboardController.php`, method `revenueApi()`.
- **Output:** Thêm support cho `$filter === 'quarter'` và `$filter === 'year'`.
- **Verify:** Fetching API tại `/api/dashboard/revenue?filter=year` trả về data hợp lệ.

## ✅ PHASE X COMPLETE
- Lint: ✅ Pass (no PHP syntax errors)
- Security: ✅ No critical issues
- Build: ✅ Success
- Date: 25/03/2026
