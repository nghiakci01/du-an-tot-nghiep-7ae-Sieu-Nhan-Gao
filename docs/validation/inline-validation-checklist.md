# Checklist: Controllers với inline `$request->validate()`

Mục tiêu: liệt kê các controller đang dùng `$request->validate()` và cung cấp tên FormRequest đề xuất cùng hành động tiếp theo để chuyển đổi.

## Danh sách phát hiện (từ grep)
- `App\Http\Controllers\Frontend\WishlistController.php` → đề xuất `App\Http\Requests\Generated\WishlistRequest`
- `App\Http\Controllers\Frontend\WalletController.php` → đề xuất `Generated\WalletRequest`
- `App\Http\Controllers\Frontend\VoucherClaimController.php` → đề xuất `Generated\VoucherClaimRequest`
- `App\Http\Controllers\Frontend\ReviewController.php` → đề xuất `Generated\ReviewRequest`
- `App\Http\Controllers\Api\ShippingController.php` → đề xuất `App\Http\Requests\Generated\ShippingFeesRequest`
- `App\Http\Controllers\Frontend\OrderTrackingController.php` → đề xuất `Generated\OrderTrackingRequest`
- `App\Http\Controllers\Api\ChatController.php` → đề xuất `Generated\ChatMessageRequest`
- `App\Http\Controllers\Frontend\ContactController.php` → đề xuất `Generated\ContactFormRequest`
- `App\Http\Controllers\Frontend\CheckoutController.php` → đề xuất `Generated\CheckoutRequest`
- `App\Http\Controllers\Frontend\CartController.php` → đề xuất `Generated\CartActionRequest`
- `App\Http\Controllers\Frontend\AddressController.php` → đề xuất `Generated\AddressRequest`
- `App\Http\Controllers\Frontend\AccountController.php` → đề xuất `Generated\AccountUpdateRequest`
- `App\Http\Controllers\Admin\WalletController.php` → đề xuất `App\Http\Requests\Generated\AdminWalletRequest`

## Hành động đề xuất cho mỗi item
1. Mở file controller, copy array rules từ `$request->validate([...])`.
2. Tạo FormRequest (sử dụng stub trong `app/Http/Requests/Generated/`) và paste rules vào `rules()`.
3. Replace `$request->validate($rules)` bằng type-hint FormRequest (ví dụ `store(CheckoutRequest $request)`).
4. Chạy tests và sửa bất kỳ chỗ `request()->input()` dùng trực tiếp nào (hoặc giữ như `->validated()` từ FormRequest).

## Ghi chú
- Một số rules có custom closures (ví dụ kiểm tra ảnh, dimension). Khi chuyển, copy nguyên closures vào FormRequest.
- Khi rule dùng `unique:...` hoặc `exists:...` với route model, dùng `Rule::unique(...)->ignore($id)` nếu cần.

---

File này được tạo tự động — nếu muốn tôi có thể tiếp tục và tự động tạo FormRequest thực sự với nội dung rules trích xuất từ controller (A) hoặc chỉ tạo stubs để dev hoàn thiện (B). Hãy chọn A hoặc B.
