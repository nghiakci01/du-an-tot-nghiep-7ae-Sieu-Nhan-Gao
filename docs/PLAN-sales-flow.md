# PLAN: Cải Thiện Luồng Bán Hàng (Sales Flow Optimization)

> 🤖 **Applying knowledge of `@project-planner` + `@frontend-specialist` + `@backend-specialist`**

---

## 1. TỔNG QUAN

**Mục tiêu:** Tối ưu hóa toàn bộ luồng bán hàng của Elite — từ lúc khách xem sản phẩm đến khi hoàn tất đơn hàng — nhằm **tăng tỷ lệ chuyển đổi (conversion rate)** và **tăng giá trị đơn hàng trung bình (AOV)**.

**Luồng bán hàng hiện tại:**
```
Trang chủ → Shop/Danh mục → Chi tiết SP → Giỏ hàng → Checkout → Thanh toán → Thành công
```

**Hiện trạng đã có:**
- ✅ Sản phẩm với biến thể (size, color, SKU)
- ✅ Giỏ hàng session-based (add/update/remove/clear/change variant)
- ✅ Coupon/Voucher (percentage/fixed, min order, usage limit)
- ✅ Checkout đa phương thức (COD, VNPay, Bank Transfer)
- ✅ Guest order tracking (phone + order code)
- ✅ Review & Rating (1-5★)
- ✅ Wishlist
- ✅ Loyalty Points (model tồn tại nhưng chưa tích hợp checkout)
- ✅ Email notification sau đặt hàng
- ✅ Admin notification

---

## 2. PHÂN TÍCH ĐIỂM YẾU (GAP ANALYSIS)

### 🔴 Nghiêm trọng (Critical)

| # | Vấn đề | Ảnh hưởng | File liên quan |
|---|--------|-----------|----------------|
| C1 | **Không trừ kho khi đặt hàng** — comment `// Removed stock verification and deduction logic` trong `CheckoutController::store()` | Khách mua được SP hết hàng, sai tồn kho | `CheckoutController.php:211-222` |
| C2 | **Không hoàn kho khi hủy đơn** — comment `// Hoàn lại số lượng tồn kho logic removed` | Hủy đơn mất stock vĩnh viễn | `CheckoutController.php:322` |

### 🟡 Quan trọng (High Impact)

| # | Vấn đề | Ảnh hưởng |
|---|--------|-----------|
| H1 | **Loyalty Points chưa hoạt động** — Model có nhưng không tích luỹ/quy đổi tại checkout | Bỏ phí cơ chế giữ chân khách hàng |
| H2 | **Không có upsell/cross-sell** — Trang giỏ hàng và checkout không gợi ý sản phẩm | Mất doanh thu tiềm năng, AOV thấp |
| H3 | **Không có Flash Sale / Sale Price hiệu quả** — Model Product có `sale_price` nhưng không có timer, không có trang riêng | Thiếu FOMO, không tạo urgency |
| H4 | **Checkout không hỗ trợ ZaloPay** — Route `VNPAY` có nhưng validation chỉ cho `COD,BANK_TRANSFER,VNPAY` | `ZaloPayService.php` tồn tại mà chưa dùng |
| H5 | **Không có "Mua ngay" (Buy Now)** — Phải qua giỏ hàng mới checkout được | Tăng friction, giảm impulse purchase |

### 🟢 Cải thiện (Medium)

| # | Vấn đề | Ảnh hưởng |
|---|--------|-----------|
| M1 | **Không có Recently Viewed** — Khách không tìm lại SP đã xem | Giảm khả năng quay lại mua |
| M2 | **Không có Social Proof nổi bật** — Review có nhưng không hiện trên homepage/listing | Thiếu yếu tố thuyết phục |
| M3 | **Cart Abandonment** — Không nhắc nhở khách quay lại giỏ hàng | Mất khách tiềm năng |
| M4 | **Không hiển thị tiến trình checkout** — Không có progress bar/steps | Khách không biết còn mấy bước |
| M5 | **Thiếu Quick View** — Phải vào detail mới xem được SP | Thêm click = thêm rào cản |

---

## 3. KẾ HOẠCH CẢI THIỆN (5 Giai đoạn)

### GIAI ĐOẠN 1: 🔴 Sửa lỗi nghiêm trọng (1-2 ngày)
> **Ưu tiên:** Đảm bảo logic bán hàng cơ bản chính xác

- [ ] **C1: Khôi phục trừ kho tại checkout**
  - Trừ `stock_quantity` trong `product_variants` khi đặt hàng thành công
  - Validate tồn kho real-time ngay trước `DB::beginTransaction()`
  - File: `CheckoutController.php` → method `store()`

- [ ] **C2: Hoàn kho khi hủy đơn**
  - Cộng lại `stock_quantity` khi cancel order
  - File: `CheckoutController.php` → method `cancelOrder()`
  - File: `AccountController.php` → method `cancelOrder()`
  - File: `Admin/OrderController.php` → khi admin hủy đơn

---

### GIAI ĐOẠN 2: 💰 Tăng giá trị đơn hàng (3-4 ngày)
> **Ưu tiên:** Tích hợp các cơ chế tăng AOV

- [ ] **H1: Tích hợp Loyalty Points vào checkout**
  - Tích luỹ points sau khi đơn hàng `completed` (ví dụ: 1 point / 10.000đ)
  - Cho phép quy đổi points → discount tại checkout
  - Hiển thị points trên trang Account
  - Files: `CheckoutController.php`, `AccountController.php`, `Order.php` (observer)
  - New: `LoyaltyPointService.php`

- [ ] **H2: Cross-sell / Upsell**
  - **Cart page:** "Khách hàng cũng mua" — SP cùng category
  - **Checkout page:** "Thêm sản phẩm phụ kiện" — SP bổ sung
  - **Product detail:** Đã có related products → Cải thiện logic (cùng tag, cùng brand)
  - Files: `CartController.php`, `CheckoutController.php`, views

- [ ] **H5: Nút "Mua ngay" (Buy Now)**
  - Thêm nút trên trang chi tiết SP → bypass cart → thẳng checkout
  - Route mới: `POST /checkout/buy-now`
  - Files: `CheckoutController.php`, `products/show.blade.php`

---

### GIAI ĐOẠN 3: ⚡ Tạo URGENCY & tăng chuyển đổi (3-4 ngày)
> **Ưu tiên:** Thúc đẩy khách mua nhanh hơn

- [ ] **H3: Flash Sale System**
  - Trang Flash Sale riêng với countdown timer
  - Hiển thị banner Flash Sale trên homepage
  - SP có `sale_price` + `sale_start` + `sale_end` → tự động kích hoạt
  - New migration: thêm `sale_start`, `sale_end` vào `products`
  - New: `FlashSaleController.php`, `flash-sale.blade.php`

- [ ] **M5: Quick View Modal**
  - Click icon "mắt" trên card SP → modal xem nhanh (ảnh, giá, size, color)
  - Nút "Thêm giỏ hàng" ngay trong modal
  - AJAX load nội dung SP
  - Files: views partials, JS

- [ ] **M2: Social Proof trên Homepage/Listing**
  - Hiển thị rating trung bình + số review trên product card
  - Badge "Bán chạy" cho SP có > X đơn hàng
  - "X người đã mua" text trên chi tiết SP
  - Files: `home.blade.php`, product card partial, `ProductController.php`

---

### GIAI ĐOẠN 4: 🎯 Tối ưu UX checkout (2-3 ngày)
> **Ưu tiên:** Giảm ma sát, tăng completion rate

- [ ] **M4: Checkout Progress Bar**
  - Steps: Giỏ hàng → Thông tin → Thanh toán → Hoàn tất
  - Visual indicator cho step hiện tại
  - Files: `checkout/index.blade.php`, CSS

- [ ] **H4: Tích hợp ZaloPay vào checkout**
  - Thêm `ZALOPAY` vào payment_method validation
  - Kết nối `ZaloPayService.php` vào flow đặt hàng
  - Callback URL xử lý trả về
  - Files: `CheckoutController.php`, `ZaloPayService.php`, routes

- [ ] **M1: Recently Viewed Products**
  - Lưu SP đã xem vào session/cookie (10 SP gần nhất)
  - Hiển thị trên trang chi tiết SP & giỏ hàng
  - Files: `ProductController.php`, middleware hoặc JS localStorage

---

### GIAI ĐOẠN 5: 📊 Theo dõi & Tối ưu (2-3 ngày)
> **Ưu tiên:** Dữ liệu để cải thiện liên tục

- [ ] **M3: Cart Abandonment Tracking**
  - Lưu giỏ hàng vào DB cho user đã đăng nhập
  - Email nhắc nhở "Bạn còn sản phẩm trong giỏ hàng" (queued job)
  - New: `AbandonedCartController.php`, migration, Mail class

- [ ] **Conversion Funnel Dashboard (Admin)**
  - Biểu đồ: View → Add to Cart → Checkout → Paid
  - Tỷ lệ chuyển đổi theo ngày/tuần/tháng
  - Files: `DashboardController.php`, `ReportService.php`

---

## 4. CHI TIẾT KỸ THUẬT

### Cấu trúc file mới cần tạo

```
app/
├── Http/Controllers/Frontend/
│   ├── FlashSaleController.php        [NEW]
│   └── BuyNowController.php           [NEW] (hoặc method trong CheckoutController)
├── Services/
│   └── LoyaltyPointService.php        [NEW]
├── Mail/
│   └── CartAbandonmentMail.php        [NEW]
├── Jobs/
│   └── SendCartAbandonmentEmail.php   [NEW]
├── Observers/
│   └── OrderObserver.php              [MODIFY] - trigger loyalty points
│
database/migrations/
│   └── xxxx_add_sale_dates_to_products.php  [NEW]
│
resources/views/frontend/
│   ├── flash-sale.blade.php            [NEW]
│   ├── partials/
│   │   ├── quick-view-modal.blade.php  [NEW]
│   │   └── recently-viewed.blade.php   [NEW]
│   ├── checkout/
│   │   └── index.blade.php            [MODIFY] - progress bar, ZaloPay, loyalty
│   ├── cart/
│   │   └── index.blade.php            [MODIFY] - cross-sell section
│   └── products/
│       └── show.blade.php             [MODIFY] - buy now, quick view
```

### Files cần chỉnh sửa (MODIFY)

| File | Thay đổi |
|------|----------|
| `CheckoutController.php` | Stock deduction, stock restore, loyalty integration, ZaloPay, buy now |
| `AccountController.php` | Loyalty points display, cancel order stock restore |
| `CartController.php` | Cross-sell data |
| `ProductController.php` | Recently viewed, social proof data |
| `HomeController.php` | Flash sale banner, best sellers |
| `Order.php` | Observer trigger for loyalty |
| `Product.php` | Sale dates scope, bestseller scope |
| `web.php` | New routes (flash-sale, buy-now) |

---

## 5. TECH STACK BỔ SUNG

| Thành phần | Công nghệ | Lý do |
|------------|-----------|-------|
| Countdown Timer | Vanilla JS (or AlpineJS) | Nhẹ, không cần thêm dependency |
| Quick View Modal | Bootstrap Modal + AJAX | Đã có Bootstrap trong project |
| Recently Viewed | `localStorage` + Blade | Không cần back-end, nhanh |
| Cart Persistence | Database `saved_carts` table | Cho user đăng nhập, hỗ trợ abandonment email |
| Loyalty Calculation | `LoyaltyPointService.php` | Tách logic, dễ test |

---

## 6. TIÊU CHÍ THÀNH CÔNG (Success Criteria)

| # | Tiêu chí | Cách đo |
|---|----------|---------|
| 1 | Stock đúng sau mỗi đơn hàng | Test: đặt hàng → kiểm tra `stock_quantity` giảm đúng |
| 2 | Stock hoàn khi hủy đơn | Test: hủy đơn → `stock_quantity` tăng lại |
| 3 | Loyalty Points tích lũy đúng | Test: đơn completed → points tính đúng |
| 4 | Flash Sale hiển thị countdown | Manual: mở trang flash-sale, timer đếm ngược |
| 5 | Buy Now → thẳng checkout | Manual: click "Mua ngay" → vào checkout 1 SP |
| 6 | Cross-sell hiển thị | Manual: giỏ hàng có section gợi ý |
| 7 | ZaloPay thanh toán thành công | Manual: chọn ZaloPay → redirect → callback OK |
| 8 | Quick View modal load đúng | Manual: click icon → modal hiện đúng SP |

---

## 7. VERIFICATION PLAN

### Automated Tests
```bash
# Chạy test hiện có
php artisan test

# Test feature mới (cần tạo)
php artisan test --filter=StockDeductionTest
php artisan test --filter=LoyaltyPointTest
php artisan test --filter=FlashSaleTest
```

### Manual Verification
1. **Stock:** Tạo SP với stock=5 → Đặt 2 → Kiểm tra stock=3 → Hủy → Kiểm tra stock=5
2. **Loyalty:** Đặt hàng 500.000đ → Admin confirm completed → Kiểm tra user có 50 points
3. **Flash Sale:** Set `sale_end` = 1 giờ sau → Mở trang → Thấy countdown
4. **Buy Now:** Vào SP → Click "Mua ngay" → Kiểm tra chỉ có 1 SP tại checkout
5. **ZaloPay:** Chọn ZaloPay tại checkout → Redirect đúng → Callback cập nhật đơn

---

## 8. ƯU TIÊN KHUYẾN NGHỊ

> **Nếu thời gian hạn chế, tập trung vào 3 việc quan trọng nhất:**

| Ưu tiên | Việc cần làm | Impact | Effort |
|---------|-------------|--------|--------|
| 🥇 P0 | Sửa trừ kho + hoàn kho (C1, C2) | 🔴 Critical | Thấp (1 ngày) |
| 🥈 P1 | Loyalty Points + Buy Now (H1, H5) | 🟡 High | Trung bình (2-3 ngày) |
| 🥉 P2 | Flash Sale + Social Proof (H3, M2) | 🟡 High | Trung bình (2-3 ngày) |

---

> **[PENDING REVIEW]** Hãy xem xét plan này và phản hồi:
> - Muốn tập trung vào giai đoạn nào trước?
> - Có feature nào muốn bỏ hoặc thêm?
> - Thời gian dành cho project còn bao lâu?
