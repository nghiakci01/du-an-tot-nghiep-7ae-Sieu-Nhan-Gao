# Admin 08. Quản Lý Blog

## Mô tả
Module viết và quản lý bài viết blog/tin tức hiển thị trên trang frontend.

---

## Chức Năng

### A8.1 Danh Mục Bài Viết
- **Route:** `GET /admin/post-categories` (resource)
- **Controller:** `Admin\PostCategoryController`
- **Thông tin:** Tên danh mục, slug, mô tả.
- **Model:** `PostCategory` — Bảng `post_categories`

---

### A8.2 Danh Sách Bài Viết
- **Route:** `GET /admin/posts`
- **Controller:** `Admin\PostController@index`
- **Hiển thị:** Tiêu đề, danh mục, tác giả, ngày tạo, trạng thái (published / draft).

---

### A8.3 Thêm/Sửa Bài Viết
- **Route:** `GET/POST /admin/posts/create`, `GET/PUT /admin/posts/{id}/edit`
- **Thông tin bài viết:**
  - `title` — Tiêu đề
  - `slug` — URL (tự sinh)
  - `post_category_id` — Danh mục
  - `thumbnail` — Ảnh đại diện
  - `excerpt` — Tóm tắt
  - `content` — Nội dung đầy đủ (rich text editor)
  - `is_published` — Bản nháp hay đã xuất bản
  - `published_at` — Ngày xuất bản
- **Model:** `Post` — Bảng `posts`

---

### A8.4 Xóa Bài Viết
- **Route:** `DELETE /admin/posts/{id}`

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| CRUD Bài viết | ✅ | ✅ |
| CRUD Danh mục bài viết | ✅ | ✅ |
