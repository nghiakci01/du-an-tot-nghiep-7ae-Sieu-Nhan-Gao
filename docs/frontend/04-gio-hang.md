# 04. Giỏ Hàng (Cart)

## Mô tả
Module quản lý giỏ hàng phía khách hàng, lưu trữ bằng Session. Hỗ trợ thêm/sửa/xóa sản phẩm, đổi biến thể và áp mã coupon.

---

## Chức Năng

### 4.1 Xem Giỏ Hàng
- **Route:** `GET /cart`
- **Controller:** `Frontend\CartController@index`
- **Mô tả:** Hiển thị danh sách sản phẩm trong giỏ hàng, tổng tiền, phí vận chuyển và nút thanh toán.

---

### 4.2 Thêm Vào Giỏ Hàng
- **Route:** `POST /cart/add`
- **Controller:** `Frontend\CartController@addToCart`
- **Đầu vào:**
  - `product_id` — ID sản phẩm
  - `variant_id` — ID biến thể (kích thước + màu sắc)
  - `quantity` — Số lượng (mặc định 1)
- **Nghiệp vụ:**
  - Kiểm tra tồn kho trước khi thêm.
  - Nếu biến thể đã có trong giỏ → cộng dồn số lượng.
  - Trả về JSON để cập nhật số đếm giỏ không reload trang.

---

### 4.3 Cập Nhật Số Lượng
- **Route:** `PATCH /cart/update`
- **Controller:** `Frontend\CartController@updateCart`
- **Đầu vào:**
  - `cart_item_id` — ID mục giỏ hàng
  - `quantity` — Số lượng mới
- **Nghiệp vụ:**
  - Nếu số lượng = 0 → xóa khỏi giỏ.
  - Kiểm tra tồn kho thực tế.

---

### 4.4 Xóa Sản Phẩm Khỏi Giỏ
- **Route:** `DELETE /cart/remove`
- **Controller:** `Frontend\CartController@remove`
- **Đầu vào:** `cart_item_id`

---

### 4.5 Xóa Toàn Bộ Giỏ Hàng
- **Route:** `POST /cart/clear`
- **Controller:** `Frontend\CartController@clearCart`

---

### 4.6 Đổi Biến Thể Sản Phẩm Trong Giỏ
- **Route:** `POST /cart/change-variant`
- **Controller:** `Frontend\CartController@changeVariant`
- **Mô tả:** Cho phép thay đổi kích cỡ/màu sắc của sản phẩm đã có trong giỏ.
- **Đầu vào:** `cart_item_id`, `new_variant_id`

---

### 4.7 Áp Mã Giảm Giá (Coupon)
- **Route:** `POST /cart/apply-coupon`
- **Controller:** `Frontend\CartController@applyCoupon`
- **Đầu vào:** `coupon_code`
- **Nghiệp vụ:**
  - Kiểm tra coupon hợp lệ (còn hạn, chưa hết lượt dùng, đạt đơn tối thiểu).
  - Tính số tiền giảm và lưu vào session.
  - Trả về thông báo lỗi nếu mã không hợp lệ.

---

### 4.8 Gỡ Mã Giảm Giá
- **Route:** `POST /cart/remove-coupon`
- **Controller:** `Frontend\CartController@removeCoupon`

---

### 4.9 Đếm Sản Phẩm Trong Giỏ (AJAX)
- **Route:** `GET /cart/count`
- **Controller:** `Frontend\CartController@getCartCount`
- **Mô tả:** Trả về số lượng item trong giỏ để cập nhật badge trên header.

---

## Nghiệp Vụ Tính Giá
```
Tổng hàng (subtotal) = Σ (giá biến thể × số lượng)
Giảm giá coupon      = calculateDiscount(subtotal)
Phí ship             = Theo tỉnh/thành
Tổng thanh toán      = subtotal - discount + shipping_fee
```

## Lưu Trữ
Giỏ hàng được lưu trong **Session** của Laravel (key: `cart`). Không yêu cầu đăng nhập.
