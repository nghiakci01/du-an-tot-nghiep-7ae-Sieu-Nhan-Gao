# Hệ Thống Email Notification Toàn Diện

> **Goal**: Hoàn thiện hệ thống gửi email tự động cho mọi sự kiện quan trọng trong vòng đời người dùng và đơn hàng.

---

## 📊 Audit hiện trạng — Cái gì ĐÃ CÓ vs CẦN LÀM

| Sự kiện | Hiện trạng | Cần làm |
|---------|-----------|---------|
| **Đăng ký** → Email chào mừng | ❌ Chỉ tạo coupon, KHÔNG gửi email | 🔨 Tạo `WelcomeMail` + template |
| **Đăng ký** → Xác thực email | ❌ User model KHÔNG implement `MustVerifyEmail` | 🔨 Enable verify + custom template |
| **Đặt hàng** → Xác nhận đơn | ✅ `OrderConfirmationMail` + template đầy đủ | ✅ Đã xong |
| **Thanh toán thành công** → Hóa đơn | ⚠️ Có `PaymentSuccessNotification` nhưng chỉ DB (admin), KHÔNG email user | 🔨 Tạo `PaymentSuccessMail` cho user |
| **Thanh toán thất bại** → Nhắc lại | ✅ `PaymentReminderMail` + auto-cancel flow | ✅ Đã xong |
| **Đơn hàng thay đổi trạng thái** | ⚠️ `OrderStatusNotification` chỉ gửi email khi `shipped`, còn lại chỉ DB | 🔨 Mở rộng gửi email cho processing + completed |
| **Đang giao** → Tracking | ✅ `OrderShippedMail` + tracking link | ✅ Đã xong |
| **Hủy đơn** | ⚠️ `OrderAutoCancelledMail` (auto-cancel), KHÔNG có email cho user tự hủy | 🔨 Tạo `OrderCancelledMail` |
| **Hoàn hàng** | ⚠️ `OrderReturnRequestStatusNotification` chỉ DB notification | 🔨 Thêm email cho return status changes |
| **Quên mật khẩu** | ✅ Laravel built-in `SendsPasswordResetEmails` | ⚠️ Cần custom template tiếng Việt |

---

## 🧠 Brainstorm: Phương Án Triển Khai

### Option A: Laravel Mailable (từng class riêng)
Mỗi sự kiện = 1 Mailable class + 1 Blade view riêng.

✅ **Pros:** Rõ ràng, dễ customize từng email, dễ test
❌ **Cons:** Nhiều file, có thể duplicate layout code
📊 **Effort:** Medium

### Option B: Laravel Notification mở rộng (Unified)
Tất cả dùng Notification system (`via: ['mail', 'database']`), mỗi notification class tự render email.

✅ **Pros:** Nhất quán, 1 class = cả DB + Email, dễ thêm channel (SMS, Slack)
❌ **Cons:** Notification `toMail()` markdown khó design phức tạp
📊 **Effort:** Medium

### Option C: Hybrid (🏆 Đề xuất)
- **Email giàu nội dung** (đơn hàng, hóa đơn) → dùng **Mailable + Blade template** đẹp
- **Email đơn giản** (trạng thái, thông báo) → dùng **Notification `toMail()`** 
- Tất cả dùng **chung 1 master email layout** (header/footer branding)

✅ **Pros:** Linh hoạt, email phức tạp đẹp, email đơn giản nhanh gọn
❌ **Cons:** 2 patterns (nhưng đã có sẵn trong codebase)
📊 **Effort:** Medium

---

## 💡 Recommendation: Option C (Hybrid)

Phù hợp vì codebase **đã dùng cả 2 patterns** (Mailable cho order, Notification cho status). Chỉ cần bổ sung các phần thiếu.

---

## Proposed Changes

### Quy trình Email Flow

```
┌──────────────────────────────────────────────────────────────┐
│                    EMAIL NOTIFICATION MAP                      │
├──────────────┬───────────────────────┬───────────────────────┤
│   Sự kiện    │       Email gửi      │      Loại            │
├──────────────┼───────────────────────┼───────────────────────┤
│ Đăng ký      │ 1. WelcomeMail       │ Mailable (queued)    │
│              │ 2. VerifyEmail       │ Built-in Notification│
├──────────────┼───────────────────────┼───────────────────────┤
│ Đặt hàng     │ 3. OrderConfirmation │ ✅ Đã có             │
├──────────────┼───────────────────────┼───────────────────────┤
│ Thanh toán OK│ 4. PaymentSuccessMail│ Mailable (queued)    │
│ Thanh toán ✗ │ 5. PaymentReminder   │ ✅ Đã có             │
├──────────────┼───────────────────────┼───────────────────────┤
│ Đang xử lý   │ 6. OrderStatus (ext) │ Notification + mail  │
│ Đang giao    │ 7. OrderShipped      │ ✅ Đã có             │
│ Đã giao      │ 8. OrderCompleted    │ Mailable (queued)    │
├──────────────┼───────────────────────┼───────────────────────┤
│ Hủy đơn      │ 9. OrderCancelledMail│ Mailable (queued)    │
│ Auto-cancel  │ 10. OrderAutoCancell │ ✅ Đã có             │
├──────────────┼───────────────────────┼───────────────────────┤
│ Hoàn hàng    │ 11. ReturnStatus ext │ Notification + mail  │
├──────────────┼───────────────────────┼───────────────────────┤
│ Quên MK      │ 12. ResetPassword    │ Custom Notification  │
└──────────────┴───────────────────────┴───────────────────────┘
```

---

### Phase 1: Đăng ký — Welcome + Email Verification

#### [NEW] `App\Mail\WelcomeMail.php`
- Subject: "Chào mừng bạn đến với Elite!"
- Content: Lời chào, coupon WELCOME-xxx (đã auto tạo), link CTA đến shop
- Queue: ShouldQueue

#### [NEW] `resources/views/emails/welcome.blade.php`
- Markdown layout, branding Elite
- Hiển thị coupon code, giá trị, điều kiện sử dụng
- CTA: "Bắt đầu mua sắm ngay"

#### [MODIFY] `App\Http\Controllers\Auth\RegisterController.php`
- Trong `registered()`: thêm `Mail::to($user)->send(new WelcomeMail($user, $coupon))`

#### [MODIFY] `App\Models\User.php`
- Implement `MustVerifyEmail` interface
- Laravel tự gửi verify email khi registered

#### [NEW] `resources/views/emails/verify-email.blade.php`
- Custom template tiếng Việt cho email xác thực

#### [MODIFY] `routes/web.php`
- Thêm `->middleware('verified')` cho routes cần verify (checkout, đặt hàng)

---

### Phase 2: Thanh toán thành công → Hóa đơn

#### [NEW] `App\Mail\PaymentSuccessMail.php`
- Subject: "Thanh toán thành công — Đơn hàng #xxx"
- Content: Hóa đơn chi tiết (sản phẩm, giá, phí ship, giảm giá, tổng)
- Phương thức thanh toán, thời gian thanh toán
- Link tra cứu đơn hàng

#### [NEW] `resources/views/emails/orders/payment_success.blade.php`
- Tương tự confirmation nhưng có **✅ badge "Đã thanh toán"**
- Bảng sản phẩm chi tiết

#### [MODIFY] `App\Observers\OrderObserver.php`
- Khi `payment_status` → `'paid'`: Gửi `PaymentSuccessMail` cho user (ngoài DB notification)

---

### Phase 3: Đơn hàng thay đổi trạng thái

#### [MODIFY] `App\Notifications\OrderStatusNotification.php`
- Mở rộng `via()`: gửi email cho **tất cả** status thay đổi (không chỉ shipped)
- `toMail()`: Render email khác nhau theo trạng thái:
  - `processing` → "Đơn hàng đang được xử lý"
  - `shipped` → ✅ Đã có (giữ nguyên `OrderShippedMail`)
  - `completed` → "Đơn hàng đã giao thành công + CTA đánh giá"

#### [NEW] `App\Mail\OrderCompletedMail.php`
- Subject: "Đơn hàng #xxx đã giao thành công!"
- Content: Tóm tắt đơn hàng, CTA "Đánh giá sản phẩm", upsell

#### [NEW] `resources/views/emails/orders/completed.blade.php`
- Sản phẩm đã nhận
- Nút đánh giá sản phẩm
- Gợi ý sản phẩm tương tự

---

### Phase 4: Hủy đơn → Email xác nhận hủy

#### [NEW] `App\Mail\OrderCancelledMail.php`
- Subject: "Xác nhận hủy đơn hàng #xxx"
- Content: Lý do hủy, thông tin hoàn tiền (nếu đã thanh toán), CTA mua lại

#### [NEW] `resources/views/emails/orders/cancelled.blade.php`
- Thông tin đơn hàng đã hủy
- Nếu đã thanh toán → thông báo "Hoàn tiền trong 3-5 ngày"
- CTA: "Tiếp tục mua sắm"

#### [MODIFY] `OrderService::updateOrderStatus()`
- Khi status = `cancelled` (user tự hủy): `Mail::to()->send(new OrderCancelledMail($order))`

---

### Phase 5: Hoàn hàng → Email trạng thái

#### [MODIFY] `App\Notifications\OrderReturnRequestStatusNotification.php`
- Thêm channel `'mail'` vào `via()`
- `toMail()`: Email content theo trạng thái:
  - `approved` → "Yêu cầu hoàn hàng được chấp nhận"
  - `rejected` → "Yêu cầu hoàn hàng bị từ chối"
  - `completed` → "Hoàn tiền thành công" + số tiền

#### [NEW] `resources/views/emails/returns/status_update.blade.php`
- Template cho return email notifications

---

### Phase 6: Quên mật khẩu → Custom template

#### [NEW] `App\Notifications\CustomResetPassword.php`
- Extends `ResetPassword` notification
- `toMail()`: Template tiếng Việt, branded

#### [MODIFY] `App\Models\User.php`
- Override `sendPasswordResetNotification()` → dùng `CustomResetPassword`

---

## User Review Required

> [!IMPORTANT]
> **Email Verification**: Bật `MustVerifyEmail` sẽ **bắt buộc** user xác thực email trước khi thực hiện các thao tác nhất định. Bạn muốn verify **bắt buộc** (không verify = không checkout) hay **soft** (chỉ nhắc nhở, không block)?

> [!IMPORTANT]
> **Mail Provider**: Bạn dùng mail provider nào? (SMTP/Gmail/Mailtrap/Mailgun). Cần cấu hình `.env` MAIL_* để gửi mail thật.

> [!WARNING]
> **Queue**: Tất cả email nên gửi qua Queue (async) để không block UI. Hiện tại queue driver là gì? (`sync` / `database` / `redis`?). Nếu `sync` thì email sẽ gửi đồng bộ — chậm.

---

## Open Questions

1. Email verification: **Bắt buộc** hay **chỉ nhắc nhở**?
2. Mail provider đang dùng gì? (cần cấu hình `.env`)
3. Có cần **email template thống nhất** (header logo + footer links) cho tất cả email không?
4. Muốn thêm **nút "Đánh giá sản phẩm"** vào email giao hàng thành công không?

---

## Task Breakdown

| # | Phase | Task | Files mới | Files sửa | Effort |
|---|-------|------|-----------|-----------|--------|
| 1 | 1 | WelcomeMail + template | 2 | 1 (RegisterController) | Low |
| 2 | 1 | Email Verification (MustVerifyEmail) | 1 template | 2 (User, routes) | Medium |
| 3 | 2 | PaymentSuccessMail + hóa đơn template | 2 | 1 (OrderObserver) | Medium |
| 4 | 3 | Mở rộng OrderStatusNotification | 0 | 1 | Low |
| 5 | 3 | OrderCompletedMail + template | 2 | 0 | Medium |
| 6 | 4 | OrderCancelledMail + template | 2 | 1 (OrderService) | Medium |
| 7 | 5 | ReturnStatus email | 1 template | 1 (Notification) | Low |
| 8 | 6 | CustomResetPassword tiếng Việt | 1 | 1 (User model) | Low |

**Tổng effort ước tính**: ~6-8 giờ (6 emails mới + 3 files sửa)

---

## Verification Plan

### Configuration Test
```bash
php artisan tinker
> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```

### Manual Verification
- Đăng ký → check inbox WelcomeMail + VerifyEmail
- Đặt hàng → check OrderConfirmation
- Thanh toán online → check PaymentSuccess
- Admin đổi trạng thái → check email processing/shipped/completed
- User hủy đơn → check CancelledMail
- Yêu cầu hoàn hàng → check ReturnStatus email
- Forgot Password → check ResetPassword email tiếng Việt
