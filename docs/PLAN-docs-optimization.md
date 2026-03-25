# Kế hoạch Tối ưu hóa Tài liệu (Docs Optimization Plan)

## Mục tiêu
Dọn dẹp thư mục gốc (root directory) của dự án bằng cách di chuyển các file đặc tả, hướng dẫn, và kế hoạch vào thư mục `docs/`. Việc này giúp source code gọn gàng, chuyên nghiệp và dễ bảo trì hơn.

## User Review Required
> [!IMPORTANT]
> Cần xác nhận phương án tổ chức thư mục `docs/`. Có nên tạo các thư mục con như `guides/`, `plans/`, `architecture/` hay chỉ ném tất cả vào thư mục `docs/` gốc?

## Đề xuất Thay đổi (Proposed Changes)

Dưới đây là danh sách các file markdown không tiêu chuẩn đang nằm ở thư mục gốc sẽ được di chuyển:

### Di chuyển và Phân loại file

#### [NEW] docs/architecture/DATABASE_SCHEMA.md
#### [DELETE] DATABASE_SCHEMA.md

#### [NEW] docs/guides/FIX-PATH-EMPTY-ERROR.md
#### [DELETE] FIX-PATH-EMPTY-ERROR.md

#### [NEW] docs/guides/OPCACHE-FIX-GUIDE.md
#### [DELETE] OPCACHE-FIX-GUIDE.md

#### [NEW] docs/guides/PULL_GUIDE.md
#### [DELETE] PULL_GUIDE.md

#### [NEW] docs/guides/read.md
#### [DELETE] read.md

#### [NEW] docs/plans/PLAN-cleanup-refactor.md
#### [DELETE] PLAN-cleanup-refactor.md

#### [NEW] docs/plans/PROJECT_PLAN.md
#### [DELETE] PROJECT_PLAN.md

#### [NEW] docs/plans/project-completion.md
#### [DELETE] project-completion.md

*(Lưu ý: Các file hệ thống quan trọng như `README.md` và `GEMINI.md` sẽ ĐƯỢC GIỮ LẠI ở thư mục gốc).*

## Kế hoạch Kiểm thử (Verification Plan)
### Chạy thử thủ công (Manual Verification)
1. Liệt kê lại thư mục gốc (`dir` hoặc `ls`) để đảm bảo các file `.md` thừa đã biến mất.
2. Kiểm tra bên trong thư mục `docs/` để cấu trúc và các file đã nằm đúng chỗ.
3. Đảm bảo các liên kết (nếu có) nội bộ giữa các file tài liệu không bị hỏng (sử dụng global search nếu cần cập nhật link).
