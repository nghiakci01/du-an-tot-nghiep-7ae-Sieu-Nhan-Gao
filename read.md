# SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

## Website bán quần áo thời trang

---

## 1. Giới thiệu

### 1.1 Mục đích

Tài liệu SRS (Software Requirements Specification) mô tả đầy đủ các yêu cầu chức năng và phi chức năng của Website bán quần áo cho một cửa hàng thời trang tại Hà Nội. Tài liệu là cơ sở cho việc:

* Phân tích nghiệp vụ
* Thiết kế hệ thống
* Phát triển phần mềm
* Kiểm thử và đánh giá

Tài liệu phục vụ cho dự án tốt nghiệp trong thời gian 4 tháng.

### 1.2 Phạm vi dự án

Website cung cấp nền tảng bán quần áo trực tuyến, cho phép:

* Khách hàng xem sản phẩm, đặt hàng, thanh toán và theo dõi đơn hàng
* Nhân viên hỗ trợ bán hàng, xử lý đơn và tồn kho
* Chủ cửa hàng quản lý toàn bộ hệ thống, doanh thu và báo cáo

Việc giao hàng được thực hiện thông qua đơn vị vận chuyển bên thứ ba.

Website phục vụ:

* Khách hàng tại Hà Nội (ưu tiên nội thành)
* Mô hình bán lẻ B2C
* Bán hàng online kết hợp bán trực tiếp tại cửa hàng

### 1.3 Định nghĩa, từ viết tắt

* **SRS:** Software Requirements Specification
* **Admin:** Chủ cửa hàng / Quản trị hệ thống
* **Nhân viên:** Nhân viên bán hàng, hỗ trợ vận hành
* **User:** Khách hàng đã đăng ký tài khoản
* **Guest:** Khách hàng vãng lai

### 1.4 Tài liệu tham khảo

* IEEE 830 / IEEE 29148 – Software Requirements Specification
* Tài liệu môn Dự án tốt nghiệp / Quản trị Website

### 1.5 Đối tượng sử dụng tài liệu

* Nhóm sinh viên thực hiện dự án
* Giảng viên hướng dẫn và hội đồng chấm đồ án
* Lập trình viên phát triển hệ thống
* Người kiểm thử hệ thống

### 1.6 Phạm vi áp dụng

* Phân tích – thiết kế hệ thống
* Phát triển và kiểm thử
* Nghiệm thu và bảo trì

---

## 2. Tổng quan hệ thống

### 2.1 Mô tả hệ thống

Website bán quần áo là hệ thống web cho phép:

* Trưng bày sản phẩm thời trang (áo, quần, váy, phụ kiện)
* Đặt hàng và thanh toán trực tuyến
* Quản lý đơn hàng, khách hàng và trạng thái giao hàng
* Thống kê doanh thu và sản phẩm bán chạy

Hệ thống hoạt động trên trình duyệt web, hỗ trợ:

* Máy tính để bàn
* Máy tính bảng
* Điện thoại di động

### 2.2 Các Actor

| Actor               | Mô tả                                      |
| ------------------- | ------------------------------------------ |
| Admin               | Quản lý toàn bộ hệ thống                   |
| Nhân viên           | Hỗ trợ bán hàng, xử lý đơn và kho          |
| Khách hàng          | Đăng ký tài khoản và mua hàng              |
| Khách hàng vãng lai | Xem sản phẩm, cần đăng nhập khi thanh toán |

### 2.3 Sơ đồ tổng quát (mô tả)

* Khách hàng / Guest ↔ Website ↔ Cơ sở dữ liệu
* Admin / Nhân viên ↔ Trang quản trị ↔ Cơ sở dữ liệu

### 2.4 Ràng buộc hệ thống

* Hoạt động trên nền tảng Web
* Yêu cầu kết nối Internet
* Phụ thuộc cổng thanh toán bên thứ ba
* Phụ thuộc đơn vị vận chuyển

### 2.5 Giả định

* Người dùng có kiến thức cơ bản về sử dụng website
* Người dùng có email hợp lệ
* Admin và nhân viên được đào tạo sử dụng hệ thống

---

## 3. Yêu cầu chức năng

### 3.1 Chức năng cho Guest

#### FR-01: Xem danh sách sản phẩm

* **Actor:** Guest
* **Mô tả:** Xem danh sách sản phẩm theo danh mục
* **Luồng chính:** Truy cập trang chủ → hiển thị sản phẩm

#### FR-02: Xem chi tiết sản phẩm

* **Actor:** Guest
* **Bao gồm:** hình ảnh, giá, size, màu, tồn kho, mô tả

#### FR-03: Tìm kiếm và lọc sản phẩm

* **Actor:** Guest
* **Tiêu chí:** tên, danh mục, giá, size, màu

#### FR-04: Thêm sản phẩm vào giỏ hàng

* **Actor:** Guest
* **Điều kiện:** Phải đăng nhập khi thanh toán

---

### 3.2 Chức năng cho User

#### FR-05: Đăng ký tài khoản

* **Actor:** Guest
* **Ngoại lệ:** Email đã tồn tại

#### FR-06: Đăng nhập / đăng xuất

* **Actor:** User

#### FR-07: Quản lý thông tin cá nhân

* **Actor:** User

#### FR-08: Đặt hàng

* **Actor:** User
* **Hậu điều kiện:** Đơn hàng trạng thái "Chờ xác nhận"

#### FR-09: Theo dõi và quản lý đơn hàng

* **Actor:** User

#### FR-10: Đánh giá sản phẩm

* **Actor:** User
* **Điều kiện:** Đã mua sản phẩm

---

### 3.3 Chức năng giỏ hàng

#### FR-11: Quản lý giỏ hàng

* Thêm, sửa, xóa sản phẩm
* Tính tổng tiền và phí vận chuyển

---

### 3.4 Thanh toán & Trả hàng

#### FR-12: Thanh toán

* COD
* Chuyển khoản (MoMo, ZaloPay, Ngân hàng)

#### FR-13: Trả hàng & hoàn tiền

* Trong vòng 7 ngày
* Đủ điều kiện sản phẩm

---

### 3.5 Chức năng cho Nhân viên

#### FR-14: Xử lý đơn hàng

* Cập nhật trạng thái đơn

#### FR-15: Quản lý tồn kho

---

### 3.6 Chức năng cho Admin

#### FR-16: Quản lý sản phẩm

* CRUD sản phẩm
* Quản lý size, màu, tồn kho

#### FR-17: Quản lý danh mục

#### FR-18: Quản lý người dùng & phân quyền

#### FR-19: Thống kê – báo cáo

* Doanh thu
* Đơn hàng
* Sản phẩm bán chạy

---

## 4. Yêu cầu phi chức năng

### 4.1 Hiệu năng

* Thời gian phản hồi < 3 giây
* ≥ 100 người dùng đồng thời

### 4.2 Bảo mật

* Mã hóa mật khẩu
* Phân quyền rõ ràng
* Chống SQL Injection, XSS, CSRF

### 4.3 Khả dụng

* Giao diện thân thiện
* Hỗ trợ đa thiết bị

### 4.4 Khả năng mở rộng

* Mở rộng thanh toán
* Hỗ trợ nhiều chi nhánh

### 4.5 Sao lưu & phục hồi

* Sao lưu dữ liệu định kỳ
* Phục hồi khi sự cố

### 4.6 Logging & Audit

* Ghi log đăng nhập
* Ghi log thao tác Admin

---

## 5. Yêu cầu hệ thống

### 5.1 Công nghệ đề xuất

* Frontend: HTML, CSS, JavaScript (Bootstrap / React)
* Backend: PHP (Laravel) hoặc Node.js
* Database: MySQL
* Server: Hosting / Laragon

### 5.2 Trình duyệt hỗ trợ

* Google Chrome
* Microsoft Edge
* Firefox

---

## 6. Kế hoạch thực hiện (4 tháng)

| Thời gian | Công việc                                  |
| --------- | ------------------------------------------ |
| Tháng 1   | Phân tích yêu cầu, viết SRS, thiết kế CSDL |
| Tháng 2   | Thiết kế giao diện, xây dựng Frontend      |
| Tháng 3   | Xây dựng Backend, xử lý chức năng          |
| Tháng 4   | Kiểm thử, hoàn thiện, viết báo cáo         |

---

## 7. Phụ lục

* Danh sách Use Case chi tiết
* Mockup giao diện
* ERD cơ sở dữ liệu
* Test Case theo từng chức năng
