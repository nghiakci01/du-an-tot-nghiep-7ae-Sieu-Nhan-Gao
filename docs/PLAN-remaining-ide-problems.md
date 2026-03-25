# Kế hoạch Fix IDE Problems (Phase 2)

## Mục tiêu
Khắc phục triệt để các cảnh báo (warnings) được liệt kê trong danh sách IDE Analysis (`current_problems`) sử dụng phương pháp Type Hinting an toàn, không làm thay đổi logic hoạt động của ứng dụng.

## 🤖 Phân công Agent
- `@backend-specialist`: Xử lý Type Hint cho Eloquent, HTTP Client.
- `@orchestrator`: Cập nhật cấu trúc route và quản lý tiến độ.

## 📋 Task Breakdown

### Task 1: Xử lý cảnh báo `LazyPromise` (HTTP Client)
Nguyên nhân: IDE hiểu nhầm kết quả trả về của `Http::xxx()` là `LazyPromise` thay vì `Response`.
- **Target:** 
  - `app/Console/Commands/ListGeminiModels.php` (lines 26, 29, 30, 49)
  - `app/Http/Controllers/Admin/ChatbotSettingController.php` (lines 90, 94, 96, 120, 124, 126)
- **Hành động:** Bổ sung `/** @var \Illuminate\Http\Client\Response $response */` cho các biến nhận HTTP response.

### Task 2: Xử lý cảnh báo Eloquent Closure `where()`
Nguyên nhân: IDE không nội suy được tham số `$query` trong hàm ẩn danh của method `where()`.
- **Target:**
  - `app/Console/Commands/SupplementProductInfo.php` (lines 33-38)
  - `app/Http/Controllers/Admin/ProductController.php` (lines 407-412)
  - `app/Http/Controllers/Frontend/SearchController.php` (lines 20-27, 54-57)
- **Hành động:** Gắn Type hint cụ thể `function (\Illuminate\Database\Eloquent\Builder $query)`.

### Task 3: Xử lý cảnh báo `TValue` property & Unknown Class
- **Target 1:** `app/Services/ChatService.php` (line 21) - `Trying to get property of non-object of type TValue`.
  - **Hành động:** Kiểm tra kiểu trả về (có thể từ `first()` đang bị null hoặc mảng). Sửa lại thao tác lấy thuộc tính an toàn (vd: `optional()`, `value()` hoặc type casting).
- **Target 2:** `database/seeders/WarehouseSeeder.php` (line 22) - `Use of unknown class: App\Models\Warehouse`.
  - **Hành động:** Kiểm tra lại Model `Warehouse` có tồn tại không, nếu không thì comment/sửa lại logic Seeder.

### Task 4: Cảnh báo `name() on non-object` trong thư mục Routes
Nguyên nhân: Cú pháp chain `Route::prefix()->name()->group()` hợp lệ với Laravel nhưng IDE không nhận dạng được `RouteRegistrar`.
- **Target:** `routes/web.php` (lines 94, 177, 197, 204)
- **Hành động:** Refactor tách riêng hoặc ignore IDE warning tuỳ theo tính thẩm mỹ của code.

---

## ✅ Phase X: Verification
- Chạy `php artisan route:list` đảm bảo việc fix route không gây sập cấu hình.
- Chạy `php artisan db:seed --class=WarehouseSeeder` (nếu cần) để test file seeder.
- Rà soát code không báo đỏ trên các file đã sửa.
