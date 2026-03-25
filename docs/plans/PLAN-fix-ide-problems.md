# Kế hoạch Khắc phục Các Lỗi IDE (IDE Problems Fix Plan)

## Tóm tắt vấn đề
Hệ thống báo cáo một loạt các lỗi (Errors), cảnh báo (Warnings) và thông tin (Infos) từ IDE sau khi merge code, bao gồm:
1. **Lỗi cú pháp (ParseError):** Do còn sót marker conflict `<<<<<<<` trong `OrderReturnController.php` và `AccountController.php`.
2. **Lỗi Type Hint / Parameter:** Tham số truyền vào `debit()` trong `WalletController` không khớp với type declaration của `WalletService`.
3. **Lỗi `unknown class` & Import dư thừa:** Báo lỗi thiếu import `Log` hoặc namespace chưa tối ưu trong `OrderService.php`.
4. **Lỗi Return Type:** Type `false` trong PHP 8+ union types bị định dạng sai namespace (`App\Services\false`) trong `WalletService.php`.
5. **Cú pháp Route lỗi:** Gọi `->name()` trên một string thay vì Route object ở `routes\web.php:260`.
6. **Lỗi PHPUnit:** Dùng sai phương thức `assertIn()` (phải là `assertContains()`) trong test `FrontendHomeTest.php`.
7. **Hàm Deprecated:** Dùng `$request->get()` thay vì `$request->input()` trong `Admin/WalletController.php`.

*(Bỏ qua các file trong thư mục `tmp/` vì đây chỉ là file nháp sinh ra tạm thời).*

---

## Phân chia Task & Giải pháp 

### Task 1: Dọn dẹp Git Conflict Marker Còn Sót (Critical)
- **File:**
  - `app/Http/Controllers/Admin/OrderReturnController.php` (Line 48)
  - `app/Http/Controllers/Frontend/AccountController.php` (Line 76)
- **Giải pháp:** Mở từng file, tìm chuỗi `<<<<<<< HEAD` và tiến hành xóa bỏ để hợp nhất (merge) code đúng logic từ cả 2 nhánh (nếu có) hoặc chọn code mới nhất từ mục đích merge.

### Task 2: Fix Lỗi Type Hint & Return Type của Wallet
- **File:** `app/Services/WalletService.php` (Lines 36, 39)
- **Giải pháp:** Nếu dùng PHP 8 Union Types, thay vì PHPDoc type báo lỗi không hiểu `false` keyword, đổi return type thành `WalletTransaction|bool` hoặc bỏ phần type không hợp lệ. Khắc phục việc IDE phân tích nhầm `App\Services\false`.
- **File:** `app/Http/Controllers/Frontend/WalletController.php` (Line 83)
- **Giải pháp:** Tham số thứ nhất của `debit()` yêu cầu instance của `User` (ví dụ: `Auth::user()`), hiện tại code có thể đang truyền `null` hoặc sai biến. Cần sửa lại cho đúng.

### Task 3: Tối ưu Import và Namespace
- **File:** `app/Services/OrderService.php` (Lines 68, 70, 71)
- **Giải pháp:** 
  - Tại Line 71: Sửa gọi `Log::error` thành `\Log::error` hoặc thêm `use Illuminate\Support\Facades\Log;`.
  - Tại Line 68 & 70: Xóa bỏ backslash `\` thừa (vd: `\Illuminate\Support\Facades\Mail`) vì đã có khai báo `use` ở trên đầu file.

### Task 4: Khắc phục lỗi Syntax Laravel
- **File:** `routes\web.php` (Line 260)
- **Giải pháp:** Kiểm tra dòng khởi tạo route. Việc gọi `->name()` trên string xảy ra khi thiếu `Route::get(...)` hoặc đặt sai vị trí dấu ngoặc. Ví dụ: `Route::get('/foo')->name('bar')` mới hợp lệ.
- **File:** `app/Http/Controllers/Admin/WalletController.php` (Line 21, 108)
- **Giải pháp:** Thay thế toàn bộ `$request->get('key')` thành `$request->input('key')` hoặc `$request->key` theo chuẩn Laravel.

### Task 5: Sửa Unit/Feature Test 
- **File:** `tests/Feature/FrontendHomeTest.php` (Line 17)
- **Giải pháp:** Khung Testing của Laravel/PHPUnit không có `$this->assertIn()`. Đổi thành `$this->assertContains(v, array);` hoặc dùng Collection assertion tương ứng.

---

## 🚦 Verification / Kiểm tra sau khi thực hiện
1. **Static Analysis:** Không còn báo đỏ/vàng trên IDE.
2. **Runtime Check:** Chạy `php artisan test` phải xanh toàn bộ.
3. Chạy `php artisan route:list` đảm bảo không bị lỗi syntax route.

---
**Người được phân công (Agent Assignment):** `orchestrator` hoặc `backend-specialist`.
