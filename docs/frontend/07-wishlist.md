# 07. Danh Sách Yêu Thích (Wishlist)

## Mô tả
Cho phép khách hàng đã đăng nhập lưu các sản phẩm yêu thích để xem lại sau.

---

## Chức Năng

### 7.1 Xem Danh Sách Yêu Thích
- **Route:** `GET /wishlist`
- **Controller:** `Frontend\WishlistController@index`
- **Yêu cầu:** Đã đăng nhập.
- **Hiển thị:** Danh sách sản phẩm đã lưu với ảnh, giá và nút thêm vào giỏ.

---

### 7.2 Thêm Vào Yêu Thích
- **Route:** `POST /wishlist/add`
- **Controller:** `Frontend\WishlistController@store`
- **Đầu vào:** `product_id`
- **Nghiệp vụ:**
  - Nếu chưa đăng nhập → trả về JSON yêu cầu đăng nhập.
  - Nếu sản phẩm đã trong wishlist → bỏ yêu thích (toggle).
  - Trả về JSON để xử lý AJAX không reload trang.

---

### 7.3 Xóa Khỏi Yêu Thích
- **Route:** `DELETE /wishlist/{id}`
- **Controller:** `Frontend\WishlistController@destroy`
- **Yêu cầu:** Đã đăng nhập + chủ sở hữu wishlist item.

---

## Models Liên Quan
- `Wishlist` — Bảng `wishlists` (user_id, product_id)
