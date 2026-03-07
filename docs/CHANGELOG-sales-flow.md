# CHANGELOG: Sales Flow Improvements

> Ngày triển khai: **07/03/2026**

---

## [2026-03-07] — Sales Flow Optimization (All 5 Phases)

### 🔴 Phase 1: Critical Bug Fixes — Stock Management

#### Fixed
- **Stock không trừ khi đặt hàng** — Thêm `lockForUpdate()` + `decrement('stock_quantity')` trong `CheckoutController::store()` để chống overselling
- **Stock không hoàn khi hủy đơn** — Refactored `cancelOrder()` delegate sang `OrderService` xử lý `handleStockAdjustment()` + `restoreStock()`

#### Files Changed
- `app/Http/Controllers/Frontend/CheckoutController.php`
- `app/Services/OrderService.php`

---

### 💰 Phase 2: Increase Order Value

#### Added
- **Loyalty Points System** — Tích luỹ 1 điểm / 10.000đ khi đơn hoàn thành, tự động thu hồi khi hủy/trả
  - Service: `app/Services/LoyaltyPointService.php`
  - Integration: `OrderService::handleLoyaltyPoints()`
  - UI: Tab "Điểm thưởng" trong Account page

- **Cross-sell trên giỏ hàng** — Gợi ý sản phẩm cùng danh mục, loại trừ SP đã có trong giỏ

#### Files Changed
- `app/Services/LoyaltyPointService.php` (NEW)
- `app/Services/OrderService.php`
- `app/Models/User.php` — `loyaltyPoints()`, `total_loyalty_points`
- `app/Http/Controllers/Frontend/AccountController.php`
- `app/Http/Controllers/Frontend/CartController.php`
- `resources/views/frontend/account/index.blade.php`
- `resources/views/frontend/cart/index.blade.php`

---

### ⚡ Phase 3: Urgency & Conversion

#### Added
- **Flash Sale System** — Countdown timer trên homepage, badge giảm giá %
  - Migration: `sale_start`, `sale_end` trên bảng `products`
  - Model: `isOnFlashSale()`, `scopeFlashSale()`, `getTotalSoldAttribute()`

- **Social Proof Badges** — "Đã bán X", "Best Seller", "Flash Sale", "Đánh giá cao" trên trang chi tiết SP

#### Files Changed
- `database/migrations/2026_03_07_115752_add_sale_dates_to_products_table.php` (NEW)
- `app/Models/Product.php`
- `app/Http/Controllers/Frontend/HomeController.php`
- `resources/views/frontend/home.blade.php`
- `resources/views/frontend/products/show.blade.php`

---

### 🎯 Phase 4: Checkout UX Optimization

#### Added
- **Checkout Progress Bar** — 4-step visual stepper (Giỏ hàng ✓ → Thanh toán → Xác nhận → Hoàn tất)
- **ZaloPay Payment** — Phương thức thanh toán thứ 3 trên checkout (COD / VNPAY / ZaloPay)
- **Recently Viewed Products** — Middleware lưu 12 SP gần nhất, hiển thị 6 SP trên trang chi tiết

#### Files Changed
- `app/Http/Middleware/TrackRecentlyViewed.php` (NEW)
- `resources/views/frontend/partials/recently-viewed.blade.php` (NEW)
- `bootstrap/app.php`
- `resources/views/frontend/checkout/index.blade.php`
- `resources/views/frontend/products/show.blade.php`

---

### 📊 Phase 5: Tracking & Optimization

#### Added
- **Cart Abandonment Tracking** — Bảng `cart_abandonments` theo dõi giỏ hàng bị bỏ rơi, tự động đánh dấu recovered khi đặt hàng
- **Conversion Funnel Dashboard** — Widget trên admin dashboard: giỏ bỏ rơi, giỏ phục hồi, tỷ lệ chuyển đổi, AOV, doanh thu tiềm năng bị mất

#### Files Changed
- `database/migrations/2026_03_07_120500_create_cart_abandonments_table.php` (NEW)
- `app/Models/CartAbandonment.php` (NEW)
- `app/Services/ConversionTrackingService.php` (NEW)
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/dashboard.blade.php`
- `app/Http/Controllers/Frontend/CheckoutController.php`

---

## Database Changes

### New Tables
| Table | Columns | Purpose |
|-------|---------|---------|
| `loyalty_points` | user_id, order_id, points, description | Lịch sử điểm thưởng |
| `cart_abandonments` | user_id, session_id, cart_data, cart_total, item_count, status | Giỏ hàng bị bỏ rơi |

### Modified Tables
| Table | Added Columns | Purpose |
|-------|--------------|---------|
| `products` | `sale_start` (timestamp), `sale_end` (timestamp) | Flash Sale thời gian |

---

## New Services

| Service | Methods | Purpose |
|---------|---------|---------|
| `LoyaltyPointService` | `earnPoints()`, `revokePoints()`, `getPointsValue()` | Quản lý điểm thưởng |
| `ConversionTrackingService` | `trackAbandonment()`, `markRecovered()`, `getFunnelStats()` | Theo dõi chuyển đổi |
