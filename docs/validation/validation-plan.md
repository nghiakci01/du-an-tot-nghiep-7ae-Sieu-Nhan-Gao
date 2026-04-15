# Kế hoạch Validate Dữ liệu (Backend + Frontend)

## Mục lục
- Mục tiêu
- Inventory endpoints & files liên quan
- Inventory trường (field) mẫu: `StoreUser`, `StoreProduct`
- Validation rules (Laravel FormRequest, JSON Schema, Frontend TS)
- Thực thi backend (file cần chỉnh, migration, rollback)
- Thực thi frontend (component, validator snippets, UX)
- Kiểm thử & CI
- Rollout, monitoring, và acceptance criteria

---

## Mục tiêu
Thiết lập hệ thống xác thực dữ liệu thống nhất giữa backend (Laravel) và frontend (JS/TS) để:
- Ngăn dữ liệu không hợp lệ/độc hại nhập vào hệ thống
- Đồng bộ rules giữa client và server
- Cung cấp contract (JSON Schema / OpenAPI fragment) cho các endpoint

---

## Inventory endpoints & file tham chiếu (bắt đầu)
- Product create (Admin): route `admin/products` -> Controller: `app/Http/Controllers/Admin/ProductController.php` (method `store`) — FormRequest: `app/Http/Requests/StoreProductRequest.php`
- User create (Admin): `app/Http/Controllers/Admin/UserController.php` (nếu có) — FormRequest: `app/Http/Requests/StoreUserRequest.php`
- Checkout (frontend): `App\\Http\\Controllers\\Frontend\\CheckoutController@store` — kiểm tra `routes/web.php` và `routes/api.php`

Nếu thiếu file trong danh sách trên, cung cấp file controller tương ứng để mở rộng inventory.

---

## Inventory trường mẫu

1) StoreUser (file: app/Http/Requests/StoreUserRequest.php)
- `name`: string, required, max 255
- `email`: string, required, email, unique:users
- `password`: string, required, min 8, confirmed
- `phone`: string, nullable, max 20
- `address`: string, nullable
- `role`: enum ['admin','user'] required

2) StoreProduct (file: app/Http/Requests/StoreProductRequest.php)
- `name`: string, required, max 255
- `category_id`: integer, required, exists:categories,id
- `price`: numeric, nullable, min 0, max 99999999
- `sale_price`: numeric, nullable, min 0, max 99999999, must be <= price
- `short_description`: string, nullable, max 500
- `description`: string, nullable, max 5000
- `image`: file image (jpeg,png,jpg,webp), max 10MB, min-dimensions 400x400
- `gallery_images[]`: array max 6, each image rules same as `image`
- `variants[]`: array required, min 1, each variant: `size_id` exists, `color_id` exists, `price`, `sale_price`, `stock_quantity` integer >=0, `sku` distinct

---

## Validation Rules — Ví dụ xuất ra

1) Laravel FormRequest (mẫu — từ code hiện có)

StoreUser rules:

```
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
'phone' => 'nullable|string|max:20',
'address' => 'nullable|string',
'role' => 'required|in:admin,user',
```

StoreProduct rules (tóm tắt):

```
'name' => 'required|string|max:255',
'category_id' => 'required|exists:categories,id',
'price' => 'nullable|numeric|min:0|max:99999999',
'sale_price' => 'nullable|numeric|min:0|max:99999999',
'image' => ['nullable','image','mimes:jpeg,png,jpg,webp','max:10240', /* custom dimension check */],
'gallery_images' => 'nullable|array|max:6',
'variants' => 'required|array|min:1',
'variants.*.size_id' => 'required|exists:sizes,id',
'variants.*.color_id' => 'required|exists:colors,id',
'variants.*.stock_quantity' => 'required|integer|min:0',
```

2) JSON Schema (machine-readable) — ví dụ rút gọn (xem file `validation-schemas/api-inputs.json`)

3) Frontend validator (TS) — snippet (ví dụ using `yup` or custom):

```ts
import * as yup from 'yup';

export const userSchema = yup.object({
  name: yup.string().required().max(255),
  email: yup.string().email().required().max(255),
  password: yup.string().required().min(8),
  password_confirmation: yup.string().oneOf([yup.ref('password')]),
  phone: yup.string().nullable().max(20),
  role: yup.string().oneOf(['admin','user']).required(),
});
```

Ghi chú: Đồng bộ hoá schema frontend từ JSON Schema hoặc generate tự động để tránh lệch rules.

---

## Backend enforcement — các file cần chỉnh/kiểm tra
- `app/Http/Requests/*` — đảm bảo mọi endpoint nhận input đều dùng FormRequest thay vì `request()->validate()` trực tiếp.
- Controller: đảm bảo phương thức type-hints FormRequest (ví dụ `store(StoreProductRequest $request)`).
- Model: cài `protected $casts` cho các field (numeric, boolean, json) và `protected $fillable`/`$guarded` để tránh mass assignment.
- Migration: thêm `->nullable(false)` hoặc `->unique()` khi rule yêu cầu, thêm index cho các field truy vấn thường xuyên (email, sku).
- DB constraints: thêm foreign keys và check constraints nếu DB hỗ trợ (Postgres). Với MySQL, ưu tiên unique/index + application-level checks.

Rollback plan:
- Tạo migration tạo index/unique/alter column với `up()`; `down()` revert lại. Test trên staging trước khi deploy.

---

## Frontend enforcement
- Xác định file component (ví dụ `resources/js/components/CheckoutForm.vue` hoặc `resources/js/admin/products/CreateProduct.vue`).
- Áp schema validator (yup / ajv) trên client; chạy sync validation trước submit.
- Async checks: email uniqueness, SKU uniqueness, inventory checks — gọi API endpoint `HEAD`/`POST` riêng trả về `{ valid: boolean, message?: string }`.
- UX: show validation error inline, use aria-invalid, aria-describedby cho accessibility.

Ví dụ API async check (frontend):

```ts
async function checkEmailUnique(email: string) {
  const res = await fetch('/api/check-email', { method: 'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({email}) });
  return res.json(); // { valid: true }
}
```

---

## Kiểm thử & CI
- Unit tests (PHPUnit): test FormRequest rules by instantiating request and asserting passes/fails. File: `tests/Unit/Validation/StoreProductRequestTest.php`.
- Integration tests: test API endpoints return 422 with normalized error body when invalid payload.
- E2E (Playwright): test common form flows: create product with invalid images, checkout with missing address, etc.

Example PHPUnit command:

```bash
./vendor/bin/phpunit --filter StoreProductRequestTest
```

Playwright command (project uses Playwright):

```bash
npx playwright test tests/e2e/checkout.spec.ts
```

Threshold: Aim 90% coverage for validation-related code paths (unit + integration for FormRequest behaviors).

---

## Rollout & monitoring
- Rollout: deploy migration + server code to staging, enable feature flag `validation_strict=true` for gradual enforcement.
- Logging: centralize validation failure logs (include endpoint, user id if any, payload hash) — use `Log::warning('validation_failed', [...])` hoặc một metric riêng.
- Metrics: expose `validation_failures_total{endpoint}` cho alerting.

---

## Security & edge cases
- Mass assignment: enforce `$fillable` hoặc `$guarded` đúng.
- Type coercion: cast numeric/boolean fields rõ ràng; validate trước khi cast.
- File uploads: check mime type, max size, và image dimensions server-side.
- Locale/encoding: normalize input bằng `mb_*` nếu cần; trim; remove control chars.
- XSS: escape khi render user input; sanitize HTML nếu hỗ trợ rich text.

---

## Acceptance criteria (DONE khi tất cả đúng)
- Tất cả endpoint reject payload invalid với HTTP 422 và JSON lỗi chuẩn.
- Frontend hiển thị cùng một bộ rules và không submit payload invalid; có fallback khi JS tắt.
- Database có constraints/index cần thiết (unique/email/sku) và migration có rollback.
- Tests (unit + integration + e2e) cover invalid/valid cases cho top endpoints.

---

## Next steps (ngắn hạn)
1. Sinh JSON Schema cho `StoreUser` và `StoreProduct` (tôi đã tạo file `validation-schemas/api-inputs.json`).
2. Tạo tests mẫu cho `StoreProductRequest`.
3. Tạo checklist thay đổi file cụ thể (FormRequests, Controllers, Migrations).

---

File này là bản khởi tạo. Muốn tiếp tục, tôi sẽ: quét thêm mã để liệt kê toàn bộ endpoints nhận input (API + web) và sinh JSON Schema đầy đủ. Bạn muốn tôi tiếp tục quét toàn bộ repo không (có thể mất vài phút)?
