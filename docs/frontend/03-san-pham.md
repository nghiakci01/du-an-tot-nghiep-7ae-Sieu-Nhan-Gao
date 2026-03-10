# 03. Sản Phẩm (Product)

## Mô tả
Module hiển thị và tương tác với sản phẩm từ phía khách hàng, bao gồm danh sách, chi tiết, tìm kiếm và đánh giá.

---

## Chức Năng

### 3.1 Danh Sách Sản Phẩm (Shop)
- **Route:** `GET /shop`
- **Controller:** `Frontend\ProductController@index`
- **Mô tả:** Hiển thị toàn bộ sản phẩm với khả năng lọc và sắp xếp.
- **Bộ lọc hỗ trợ:**
  - Theo danh mục (`?category=id`)
  - Theo khoảng giá (`?min_price=&max_price=`)
  - Theo màu sắc, kích thước
  - Theo thương hiệu
- **Sắp xếp:** Mới nhất, Giá tăng dần/giảm dần, Phổ biến
- **Phân trang:** 12 sản phẩm/trang

---

### 3.2 Chi Tiết Sản Phẩm
- **Route:** `GET /product/{slug}`
- **Controller:** `Frontend\ProductController@show`
- **Mô tả:** Trang chi tiết sản phẩm với đầy đủ thông tin.
- **Nội dung hiển thị:**
  - Tên, giá, giá khuyến mãi, mô tả ngắn, mô tả đầy đủ
  - Gallery ảnh (ảnh chính + ảnh phụ)
  - Biến thể sản phẩm: kích thước, màu sắc, số lượng tồn kho
  - Đánh giá & nhận xét của khách hàng (kèm điểm sao trung bình)
  - Sản phẩm liên quan
- **Nghiệp vụ:**
  - Chọn biến thể → tự động cập nhật giá và trạng thái còn hàng/hết hàng.
  - Nút "Thêm vào giỏ" bị vô hiệu hóa nếu hết hàng.

---

### 3.3 Tìm Kiếm Sản Phẩm
- **Route:** `GET /search`
- **Controller:** `Frontend\SearchController@index`
- **Mô tả:** Tìm kiếm full-text theo tên và mô tả sản phẩm.
- **Đầu vào:** `?q=từ_khóa`
- **Đầu ra:** Danh sách sản phẩm khớp với kết quả phân trang.

---

### 3.4 Gợi Ý Tìm Kiếm (AJAX)
- **Route:** `GET /search/suggestions`
- **Controller:** `Frontend\SearchController@suggestions`
- **Mô tả:** Trả về gợi ý tìm kiếm realtime khi người dùng gõ.
- **Đầu vào:** `?q=từ_khóa`
- **Đầu ra:** JSON mảng sản phẩm gợi ý (tên + ảnh + giá)

---

### 3.5 Đánh Giá Sản Phẩm
- **Route:** `POST /product/{id}/review`
- **Controller:** `Frontend\ReviewController@store`
- **Mô tả:** Khách hàng gửi đánh giá sau khi mua hàng.
- **Đầu vào:**
  - `rating` — Điểm sao (1–5)
  - `comment` — Nhận xét (tùy chọn)
- **Nghiệp vụ:**
  - Yêu cầu đăng nhập.
  - Mỗi người chỉ được đánh giá một sản phẩm một lần.

---

## Models Liên Quan
- `Product` — Bảng `products`
- `ProductVariant` — Bảng `product_variants` (size_id, color_id, stock, price_override)
- `ProductImage` — Bảng `product_images`
- `Category` — Bảng `categories`
- `Brand` — Bảng `brands`
- `Review` — Bảng `reviews`
- `Tag` — Bảng `tags` (many-to-many với products)
