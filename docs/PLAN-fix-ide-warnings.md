## 🧠 Brainstorm: Sửa các lỗi và cảnh báo (Warnings & Errors) từ IDE

### Context
IDE Scanner vừa báo cáo khoảng ~60 vấn đề rải rác trong hệ thống. Các lỗi này không hẳn làm sập website nhưng là các rủi ro về Type Hinting, Missing Import, Duplicate Array Keys và sử dụng các method bị deprecated (ví dụ `->get()` thay vì `->input()`), cùng cảnh báo về `LazyPromise` (tới từ Http::async hoặc method không support đúng chuẩn).

---

### Option A: Sửa cuốn chiếu theo từng file
Đi từng file từ trên xuống dưới theo danh sách báo cáo, từ Console Commands, Controllers, đến Services, Config, Tests.

✅ **Pros:**
- Dễ duyệt theo checklist, cứ hết file nào là đánh dấu xong file đó.

❌ **Cons:**
- Nhảy qua lại giữa các loại context (lúc thì sửa Eloquent, lúc thì sửa HTTP, lúc thì sửa UI) làm mất đi sự nhất quán.
- Nếu nhiều file gặp chung 1 chuẩn lỗi (như quên use `Log`), làm từng file sẽ chậm.

📊 **Effort:** High

---

### Option B: Phân nhóm và xử lý theo Loại Lỗi (Error Types)
Chia các bản tóm tắt thành 4 nhóm cụ thể:
1. **Missing Imports (Class not found) & Code Style:** Thiếu thư viện `Illuminate\Support\Facades\Log`, lỗi name simplification, chuyển `->get()` thành `->input()`.
2. **Type Matching & Eloquent Builder:** Fix closures truyền vào `->where()`, lỗi model properties truy xuất vào `TValue`, `Auth::user()` vs `User`.
3. **HTTP Client & Promises:** Lỗi `LazyPromise::successful()` chưa chính xác trong `GeminiProvider`, `ChatbotSettingController`.
4. **Cấu hình & Dữ liệu:** Xóa các key trùng trong `messages.php`, sửa class `Pdo\Mysql` trong config.

✅ **Pros:**
- Sửa rất nhanh theo từng thao tác đồng loạt (hàng loạt sửa `Log`, hàng loạt sửa `get()`).
- Đưa các lỗi có nguy cơ tác động vào database quy về 1 phiên test.

❌ **Cons:**
- Vẫn có rủi ro nếu một file vừa bị lỗi Type vừa bị lỗi HTTP, nhưng quản lý được.

📊 **Effort:** Medium

---

## 💡 Recommendation
**Option B** là lựa chọn phù hợp nhất cho dự án vào cuối giai đoạn. Phương pháp này giảm thiểu rủi ro Regression Bug và giúp dễ review PR (commit theo category thay vì commit một đống file hỗn độn).

---

# PLAN-fix-ide-warnings

## Overview
Dọn dẹp và sửa chữa tất cả các cảnh báo từ IDE nhằm đảm bảo trạng thái "Zero Warnings" cho dự án, tối ưu chuẩn PHP 8.x / Laravel 11.x, đảm bảo Type Hinting hoàn chỉnh và không bị deprecated.

## Project Type
**WEB / BACKEND** (Chủ yếu Core PHP logic)

## Success Criteria
- [ ] KHÔNG làm thay đổi / gãy (break) luồng logic nghiệp vụ hiện tại.
- [ ] Quét lại bằng Intelephense hoặc IDE Helper không còn báo lỗi ở những path đã report.
- [ ] Tuân thủ tối đa PSR-12 và Type Safety.

## File Structure (Các nhóm file ảnh hưởng)
- Nằm rải rác: `App\Console`, `App\Http\Controllers`, `App\Services`, `config`, `lang`, `routes`, `tests`.

## Task Breakdown

### Task 1: Dọn dẹp Code Style, Missing Imports & Deprecations
- **Agent:** `backend-specialist`
- **Priority:** P1
- **Focus Areas:**
  - Add `use Illuminate\Support\Facades\Log;` ở `CartController`, `CheckoutController`, `ContactController`, `OrderService`.
  - Đổi `$request->get()` sang `$request->input()` trong `OrderReturnController`, `CartController`, `DashboardController`.
  - Simplify classes: `\App\Models\Order` -> `Order`, `\Exception` -> `Exception` ở `DashboardController`, `HomeController`, vv...
  - Xóa dòng `use of unknown class App\Models\WarehouseStock` và `InventoryVoucherDetail` ở `CategoryController`.
- **Output:** Tất cả warning dạng "unknown class" và "deprecated get" biến mất.

### Task 2: Type Hinting & Eloquent Relationships `TValue` issues
- **Agent:** `backend-specialist`
- **Priority:** P1
- **Focus Areas:**
  - `Product.php`: Xác định lại docblock cho method `images()` hoặc đổi return type (từ `HasMany` -> `Builder` hoặc fix ngược lại).
  - Khai báo param chuẩn `App\Models\User` hay `Authenticatable` cho `OrderReturnController` (approve, reject, markAsShipping,...). Hoặc dùng type hinting `User|Authenticatable|null` bằng `/** @var User $user */`.
  - File `SupplementProductInfo.php`, `SearchController.php`: Sửa syntax sử dụng `where()` với Closure (Laravel mong đợi `$query->where(fn(Builder $q) => ...)` cần add đúng type hint cho callable).
  - TValue: AppServiceProvider, ChatService, ChatManagementController, ReportService: Thêm docblock `/** @var \App\Models\ModelName $var */` trước object fetch ra để giải quyết strict type check.

### Task 3: API HTTP Promise Fixes
- **Agent:** `backend-specialist`
- **Priority:** P1
- **Focus Areas:**
  - Các module dùng GenAI: `ListGeminiModels.php`, `ChatbotSettingController.php`, `GeminiProvider.php`, `OpenAIProvider.php`.
  - Hệ thống đang dùng `Http::async()` trả về `Illuminate\Http\Client\Promises\PromiseInterface`. Các method `->successful()`, `->json()` không trực tiếp gọi được trên object Promise mà phải `->wait()->successful()`.
- **Output:** Sửa lại luồng resolve Promise để IDE hiểu (Gọi `wait()` trước, hoặc đổi sang dạng sync nếu tác vụ không chạy đua).

### Task 4: Fix Duplicate Language Keys & Config Files
- **Agent:** `orchestrator`
- **Priority:** P2
- **Focus Areas:**
  - Mở file `lang/vi/messages.php`, dòng 199, 250 và `lang/en/messages.php` dòng 190. Xoá/Gộp key trùng lặp.
  - Sửa config `database.php` dòng 62, 82: `Pdo\Mysql` phải là namespace hợp chuẩn `PDO` gốc hoặc PHP module (thường Laravel ko check lỗi thư viện ngoài). Khả năng cấu hình DSN sai.
  - File `routes/web.php` báo `name() on non-object`. Có thể do Route::group lồng hoặc config route rỗng gây gãy chaining.

## ✅ Phase X: Verification
- [ ] Chạy `php artisan route:list` đảm bảo không lỗi file routes.
- [ ] Chạy `php artisan config:cache` đảm bảo database config không gãy.
- [ ] Chạy `php artisan test` nếu sẵn rảnh rỗi.
