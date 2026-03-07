# 02. Trang Chủ & Trang Giới Thiệu

## Mô tả
Các trang tĩnh và động dành cho khách hàng bao gồm trang chủ với sản phẩm nổi bật, trang giới thiệu và tin tức blog.

---

## Chức Năng

### 2.1 Trang Chủ
- **Route:** `GET /` hoặc `GET /home`
- **Controller:** `Frontend\HomeController@index`
- **Mô tả:** Hiển thị nội dung chào mừng với các sản phẩm nổi bật, banner quảng cáo và danh mục.
- **Dữ liệu hiển thị:**
  - Banner quảng cáo (lấy từ bảng `banners` đang hoạt động)
  - Sản phẩm nổi bật (`is_featured = true`)
  - Sản phẩm mới nhất
  - Danh mục sản phẩm

---

### 2.2 Trang Giới Thiệu
- **Route:** `GET /about`
- **Controller:** `Frontend\HomeController@about`
- **Mô tả:** Giới thiệu thương hiệu, lịch sử và giá trị công ty.

---

### 2.3 Tin Tức / Blog
- **Route:** `GET /news`
- **Controller:** `Frontend\HomeController@news`
- **Mô tả:** Danh sách bài viết blog với phân trang.
- **Đầu vào:** Tham số phân trang `?page=N`
- **Đầu ra:** Danh sách bài viết gồm tiêu đề, ảnh thumbnail, ngày đăng, tóm tắt.

---

### 2.4 Chi Tiết Tin Tức
- **Route:** `GET /news/{slug}`
- **Controller:** `Frontend\HomeController@newsDetail`
- **Mô tả:** Hiển thị toàn bộ nội dung bài viết theo slug.
- **Đầu vào:** `slug` — định danh URL thân thiện.
- **Đầu ra:** Nội dung bài viết đầy đủ + bài viết liên quan.

---

## Models Liên Quan
- `Banner` — Bảng `banners`
- `Product` — Bảng `products` (`is_featured = true`)
- `Post` — Bảng `posts`
- `PostCategory` — Bảng `post_categories`
