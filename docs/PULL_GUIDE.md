# Hướng dẫn đồng bộ dự án (Sau khi Pull Code)

Tài liệu này hướng dẫn các bước cần thiết để khởi chạy dự án sau khi bạn pull mã nguồn mới nhất từ Git về.

## 1. Các bước cơ bản (Khuyên dùng)

Dự án đã tích hợp sẵn lệnh setup tự động trong `composer.json`. Bạn chỉ cần chạy một lệnh duy nhất:

```bash
composer setup
```

**Lệnh này sẽ tự động thực hiện:**
- Cài đặt các thư viện PHP (`composer install`).
- Tạo file `.env` từ `.env.example` (nếu chưa có).
- Khởi tạo Key ứng dụng (`php artisan key:generate`).
- Chạy Migrations (`php artisan migrate --force`).
- Cài đặt các thư viện Javascript (`npm install`).
- Build assets (`npm run build`).

---

## 2. Các bước bổ sung (Nếu cần)

### Seeding dữ liệu (Nếu cần dữ liệu mẫu mới)
Nếu có các bảng dữ liệu mới cần seed (như danh mục, sản phẩm mẫu), hãy chạy:
```bash
php artisan db:seed
```

### Tạo liên kết Storage (Khi bị lỗi không hiển thị được ảnh)
```bash
php artisan storage:link
```

### Dọn dẹp Cache (Nếu code không cập nhật)
```bash
php artisan optimize:clear
```

---

## 3. Chạy môi trường Local

### Khởi động Server & Vite (Cùng lúc)
```bash
composer dev
```
*Lệnh này sẽ chạy song hành: artisan serve, queue listener, và npm run dev.*

---

## 4. Troubleshooting (Lỗi thường gặp)

1. **Lỗi database connection:** Kiểm tra lại thông tin `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` trong file `.env`.
2. **Lỗi ảnh không hiển thị:** Đảm bảo đã chạy `php artisan storage:link`.
3. **Lỗi OpCache (Dành cho Laragon):** Nếu sửa code nhưng web không đổi, hãy sử dụng script dọn dẹp cache có sẵn trong project.
