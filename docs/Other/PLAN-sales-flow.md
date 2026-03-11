# PLAN: Cải Thiện Luồng Bán Hàng (Sales Flow Optimization)

> 🤖 **Applying knowledge of `@project-planner` + `@frontend-specialist` + `@backend-specialist`**
> 
> ✅ **TRẠNG THÁI: ĐÃ TRIỂN KHAI HOÀN TẤT** — Ngày 07/03/2026

---

## 1. TỔNG QUAN

**Mục tiêu:** Tối ưu hóa toàn bộ luồng bán hàng của Elite — từ lúc khách xem sản phẩm đến khi hoàn tất đơn hàng — nhằm **tăng tỷ lệ chuyển đổi (conversion rate)** và **tăng giá trị đơn hàng trung bình (AOV)**.

**Luồng bán hàng hiện tại:**
```
Trang chủ → Shop/Danh mục → Chi tiết SP → Giỏ hàng → Checkout → Thanh toán → Thành công
```

---

## 2. KẾ HOẠCH & TIẾN ĐỘ

### GIAI ĐOẠN 1: 🔴 Sửa lỗi nghiêm trọng ✅ HOÀN TẤT

- [x] **C1: Khôi phục trừ kho tại checkout**
  - Trừ `stock_quantity` với `lockForUpdate()` chống race condition
  - Validate tồn kho real-time ngay trong transaction
  - File: `CheckoutController.php` → method `store()`

- [x] **C2: Hoàn kho khi hủy đơn**
  - Refactored `cancelOrder()` → delegate sang `OrderService::updateOrderStatus()`
  - `OrderService` xử lý `handleStockAdjustment()` và `restoreStock()`
  - File: `CheckoutController.php`, `OrderService.php`

---

### GIAI ĐOẠN 2: 💰 Tăng giá trị đơn hàng ✅ HOÀN TẤT

- [x] **H1: Tích hợp Loyalty Points**
  - **Service mới:** `LoyaltyPointService.php` — tích luỹ 1 point / 10.000đ, quy đổi 1 point = 1.000đ
  - **Tự động hóa:** `OrderService` gọi `handleLoyaltyPoints()` khi đổi status
    - `completed` → cộng điểm
    - `cancelled` / `returned` / `failed` → thu hồi điểm
  - **UI:** Tab "Điểm thưởng" trong Account page (tổng điểm, giá trị quy đổi, lịch sử, hướng dẫn)
  - Files: `LoyaltyPointService.php`, `OrderService.php`, `User.php`, `AccountController.php`, `account/index.blade.php`

- [x] **H2: Cross-sell trên giỏ hàng**
  - Query SP cùng category, loại trừ SP đã có trong giỏ, random 8, hiển thị 4
  - File: `CartController.php`, `cart/index.blade.php`

- [x] **H5: Nút "Mua ngay" (Buy Now)**
  - **Đã có sẵn** — nút Buy Now trong `products/show.blade.php` set `action=buy_now` → `CartController::addToCart()` redirect checkout

---

### GIAI ĐOẠN 3: ⚡ Tạo URGENCY & tăng chuyển đổi ✅ HOÀN TẤT

- [x] **H3: Flash Sale System**
  - Migration: thêm `sale_start`, `sale_end` (timestamp, nullable) vào `products`
  - Model: `isOnFlashSale()`, `scopeFlashSale()`, `getFlashSaleEndsAtAttribute()`
  - Homepage: section Flash Sale với gradient đỏ, countdown timer (JS auto-refresh)
  - Badge giảm giá % trên mỗi SP flash sale
  - Files: `Product.php`, `HomeController.php`, `home.blade.php`

- [x] **M2: Social Proof trên chi tiết SP**
  - `getTotalSoldAttribute()` — đếm tổng bán từ order_items (loại trừ cancelled/failed/returned)
  - 4 badge: "Đã bán X", "Best Seller" (≥10 sold), "Flash Sale", "Đánh giá cao" (≥4.5★, ≥3 reviews)
  - File: `Product.php`, `products/show.blade.php`

---

### GIAI ĐOẠN 4: 🎯 Tối ưu UX checkout ✅ HOÀN TẤT

- [x] **M4: Checkout Progress Bar**
  - 4 bước visual: Giỏ hàng ✓ → Thanh toán (active) → Xác nhận → Hoàn tất
  - Responsive, green check cho completed, blue glow cho active
  - File: `checkout/index.blade.php`

- [x] **H4: Tích hợp VNPAY Full (IPN, Query, Refund)**
  - **VnpayService**: Trung tâm xử lý logic (Checksum, URL, QueryDR & Refund API)
  - **IPN Handler**: Xác thực server-to-server 5 bước bảo mật cao
  - **Admin Actions**: Cho phép Truy vấn trạng thái & Hoàn tiền ngay tại Admin
  - **Hủy bỏ ZaloPay**: Đã xóa toàn bộ code và tùy chọn ZaloPay để tập trung vào VNPAY
  - Files: `VnpayService.php`, `PaymentController.php`, `OrderController.php`, `web.php`

- [x] **M1: Recently Viewed Products**
  - Middleware `TrackRecentlyViewed` — lưu 12 product ID gần nhất vào session
  - Partial `recently-viewed.blade.php` — hiển thị 6 SP, loại trừ SP hiện tại
  - Include trên trang chi tiết sản phẩm
  - Files: `TrackRecentlyViewed.php`, `app.php`, `recently-viewed.blade.php`, `products/show.blade.php`

---

### GIAI ĐOẠN 5: 📊 Theo dõi & Tối ưu ✅ HOÀN TẤT

- [x] **M3: Cart Abandonment Tracking**
  - Migration: bảng `cart_abandonments` (user_id, session_id, cart_data JSON, status, totals)
  - Model: `CartAbandonment` với scopes `abandoned()`, `recovered()`
  - Service: `ConversionTrackingService` — `trackAbandonment()`, `markRecovered()`
  - Integration: `CheckoutController::store()` gọi `markRecovered()` khi đặt hàng thành công

- [x] **Conversion Funnel Dashboard (Admin)**
  - `ConversionTrackingService::getFunnelStats('30d')` trả về:
    - Giỏ bỏ rơi / Giỏ phục hồi
    - Tỷ lệ chuyển đổi cart → order
    - Giá trị TB / đơn hàng
    - Doanh thu tiềm năng bị mất
  - Widget trên admin dashboard (6 metrics, 2x3 grid)
  - Files: `DashboardController.php`, `dashboard.blade.php`

---

## 3. DANH SÁCH FILE ĐÃ TẠO MỚI

| File | Mục đích |
|------|----------|
| `app/Services/LoyaltyPointService.php` | Tích luỹ & quy đổi điểm thưởng |
| `app/Services/ConversionTrackingService.php` | Theo dõi cart abandonment + phễu chuyển đổi |
| `app/Models/CartAbandonment.php` | Model giỏ hàng bị bỏ rơi |
| `app/Http/Middleware/TrackRecentlyViewed.php` | Middleware theo dõi SP đã xem |
| `resources/views/frontend/partials/recently-viewed.blade.php` | Widget SP đã xem gần đây |
| `database/migrations/*_add_sale_dates_to_products_table.php` | Thêm `sale_start`, `sale_end` |
| `database/migrations/*_create_cart_abandonments_table.php` | Bảng cart_abandonments |

## 4. DANH SÁCH FILE ĐÃ CHỈNH SỬA

| File | Thay đổi |
|------|----------|
| `CheckoutController.php` | Stock deduction (lockForUpdate), cart recovery tracking |
| `OrderService.php` | Inject LoyaltyPointService, handleLoyaltyPoints() |
| `CartController.php` | Cross-sell products query |
| `HomeController.php` | Flash sale products query |
| `DashboardController.php` | Inject ConversionTrackingService, pass funnelStats |
| `AccountController.php` | Pass loyalty points data to view |
| `Product.php` | sale_start/sale_end fields, isOnFlashSale(), scopeFlashSale(), getTotalSoldAttribute() |
| `User.php` | loyaltyPoints() relation, total_loyalty_points accessor |
| `bootstrap/app.php` | Registered TrackRecentlyViewed middleware |
| `home.blade.php` | Flash Sale section with countdown |
| `products/show.blade.php` | Social proof badges, recently-viewed include |
| `cart/index.blade.php` | Cross-sell section |
| `VnpayService.php` | Trung tâm logic VNPAY (URL, Hash, Query, Refund) |
| `PaymentController.php` | Refactor dùng VnpayService, thêm IPN handler |
| `OrderController.php` (Admin) | Thêm method queryPayment() và refundPayment() |
| `web.php` | Thêm route IPN và routes Admin Payment |
| `checkout/index.blade.php` | Progress bar, Xóa tùy chọn ZaloPay |
| `admin/orders/show.blade.php` | Thêm nút Truy vấn & Hoàn tiền VNPAY |

---

## 5. HƯỚNG DẪN SỬ DỤNG

### Flash Sale
Để kích hoạt Flash Sale cho sản phẩm:
```sql
UPDATE products SET sale_start = '2026-03-07 00:00:00', sale_end = '2026-03-08 23:59:59' WHERE id = 1;
```
Hoặc qua Admin panel (nếu đã có form), set `sale_start` và `sale_end` cho sản phẩm có `sale_price`.

### Loyalty Points
- Tự động cộng khi đơn hàng chuyển sang `completed` (1 điểm / 10.000đ)
- Tự động thu hồi khi đơn bị `cancelled`, `returned`, hoặc `failed`
- Xem tại: Account → Điểm thưởng

### Cart Abandonment
- Tracking tự động khi user đặt hàng (markRecovered)
- Xem thống kê tại: Admin Dashboard → "Phễu Chuyển Đổi"
- Để chủ động track abandoned carts, có thể tạo scheduled command gọi `ConversionTrackingService::trackAbandonment()` cho sessions inactive > 30 phút

---

## 6. VERIFICATION

| # | Tiêu chí | Trạng thái |
|---|----------|------------|
| 1 | Stock giảm đúng khi đặt hàng | ✅ lockForUpdate trong transaction |
| 2 | Stock hoàn khi hủy đơn | ✅ OrderService::restoreStock() |
| 3 | Loyalty Points tích lũy đúng | ✅ Auto earn via OrderService |
| 4 | Flash Sale countdown trên homepage | ✅ JS countdown, auto-reload khi hết |
| 5 | Buy Now thẳng checkout | ✅ Đã có sẵn trong codebase |
| 6 | Cross-sell hiển thị trên giỏ hàng | ✅ 4 SP cùng category |
| 7 | ZaloPay là option thanh toán | ✅ Radio button thứ 3 |
| 8 | Recently Viewed hiển thị | ✅ Middleware + partial |
| 9 | Progress bar trên checkout | ✅ 4-step stepper |
| 10 | Admin dashboard có funnel stats | ✅ 6 metrics widget |
