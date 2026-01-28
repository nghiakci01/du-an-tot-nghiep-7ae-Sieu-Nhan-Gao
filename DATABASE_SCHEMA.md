# Thiết Kế Cơ Sở Dữ Liệu (Bản Nháp)

## 1. Người dùng & Xác thực (Users & Authentication)

### `users`
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | ID duy nhất |
| `name` | VARCHAR(255) | | Họ và tên |
| `email` | VARCHAR(255) | Unique | Email đăng nhập |
| `password` | VARCHAR(255) | | Mật khẩu đã mã hóa |
| `phone` | VARCHAR(20) | Nullable | Số điện thoại liên hệ |
| `address` | TEXT | Nullable | Địa chỉ giao hàng mặc định |
| `role` | ENUM | 'admin', 'user' | Mặc định: 'user' |
| `created_at` | TIMESTAMP | | |
| `updated_at` | TIMESTAMP | | |

## 2. Sản phẩm & Danh mục (Products & Catalog)

### `categories`
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | |
| `name` | VARCHAR(255) | | Tên danh mục (vd: Nam, Nữ) |
| `slug` | VARCHAR(255) | Unique | Đường dẫn thân thiện URL |
| `parent_id` | BIGINT | Nullable | Cho danh mục đa cấp |
| `image` | VARCHAR(255) | Nullable | Ảnh đại diện danh mục |

### `products`
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | |
| `category_id` | BIGINT | FK | Liên kết bảng `categories` |
| `name` | VARCHAR(255) | | Tên sản phẩm |
| `slug` | VARCHAR(255) | Unique | Đường dẫn thân thiện |
| `description` | TEXT | Nullable | Mô tả (HTML hoặc Text) |
| `price` | DECIMAL(10,2)| | Giá gốc |
| `is_active` | BOOLEAN | | Mặc định: true |
| `image`| VARCHAR(255) | Nullable | Ảnh chính |

### `product_variants` (SKUs - Biến thể)
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | |
| `product_id` | BIGINT | FK | Liên kết bảng `products` |
| `size` | VARCHAR(50) | | vd: S, M, L, XL |
| `color` | VARCHAR(50) | | vd: Đỏ, Xanh |
| `stock_quantity` | INT | | Số lượng tồn kho |
| `sku` | VARCHAR(100) | Unique | Mã quản lý kho (SKU) |

## 3. Đơn hàng & Thanh toán (Orders & Checkout)

### `orders`
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | |
| `user_id` | BIGINT | FK | Liên kết bảng `users` |
| `status` | ENUM | PENDING (Chờ), CONFIRMED (Xác nhận), SHIPPED (Giao), COMPLETED (Xong), CANCELLED (Hủy) | Trạng thái đơn hàng |
| `total_price` | DECIMAL(12,2)| | Tổng tiền cuối cùng |
| `payment_method` | VARCHAR(50) | | COD, BANK_TRANSFER (Chuyển khoản) |
| `shipping_address`| TEXT | | Địa chỉ giao hàng tại thời điểm đặt |
| `note` | TEXT | Nullable | Ghi chú của khách |
| `created_at` | TIMESTAMP | | |

### `order_items`
| Cột | Kiểu | Thuộc tính | Mô tả |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT | PK, AI | |
| `order_id` | BIGINT | FK | Liên kết bảng `orders` |
| `product_id` | BIGINT | FK | Liên kết bảng `products` |
| `variant_id` | BIGINT | FK | Liên kết bảng `product_variants` |
| `quantity` | INT | | Số lượng |
| `price` | DECIMAL(10,2)| | Giá bán tại thời điểm mua |

## 4. Mối quan hệ (Relationships - Eloquent)

- **User** hasMany **Orders** (Người dùng có nhiều Đơn hàng)
- **Category** hasMany **Products** (Danh mục có nhiều Sản phẩm)
- **Product** belongsTo **Category** (Sản phẩm thuộc về Danh mục)
- **Product** hasMany **ProductVariants** (Sản phẩm có nhiều Biến thể)
- **Order** belongsTo **User** (Đơn hàng thuộc về Người dùng)
- **Order** hasMany **OrderItems** (Đơn hàng có nhiều Chi tiết đơn hàng)
