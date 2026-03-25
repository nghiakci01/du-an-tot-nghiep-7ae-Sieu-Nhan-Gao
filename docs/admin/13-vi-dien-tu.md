# Admin 13. Ví Điện Tử (Wallet Management)

## Mô tả
Module cho phép Admin quản lý ví điện tử của khách hàng: duyệt yêu cầu nạp tiền, duyệt yêu cầu rút tiền và điều chỉnh số dư thủ công.

---

## Chức Năng

### A13.1 Danh Sách Yêu Cầu Nạp Tiền
- **Route:** `GET /admin/wallet`
- **Controller:** `Admin\WalletController@index`
- **Lọc theo trạng thái:** `pending` / `approved` / `rejected` / `all`
- **Thông tin hiển thị:** Khách hàng, số tiền, bằng chứng chuyển khoản, thời gian tạo, trạng thái.

---

### A13.2 Duyệt Yêu Cầu Nạp Tiền
- **Route:** `POST /admin/wallet/{topupRequest}/approve`
- **Controller:** `Admin\WalletController@approve`
- **Nghiệp vụ:**
  - Chỉ được duyệt khi trạng thái là `pending`.
  - Gọi `WalletService::credit()` để cộng tiền vào ví khách hàng.
  - Ghi nhận `processed_by` (admin_id) và `processed_at`.
  - Tạo `WalletTransaction` loại `topup`.

---

### A13.3 Từ Chối Yêu Cầu Nạp Tiền
- **Route:** `POST /admin/wallet/{topupRequest}/reject`
- **Controller:** `Admin\WalletController@reject`
- **Nghiệp vụ:**
  - Chỉ được từ chối khi trạng thái là `pending`.
  - Admin nhập ghi chú lý do từ chối (`admin_note`).
  - Không thay đổi số dư ví.

---

### A13.4 Danh Sách Yêu Cầu Rút Tiền
- **Route:** `GET /admin/wallet/withdrawals`
- **Controller:** `Admin\WalletController@withdrawals`
- **Lọc theo trạng thái:** `pending` / `approved` / `rejected` / `all`
- **Thông tin hiển thị:** Khách hàng, số tiền, tài khoản ngân hàng nhận, trạng thái, thời gian.

---

### A13.5 Duyệt Yêu Cầu Rút Tiền
- **Route:** `POST /admin/wallet/withdrawals/{withdrawRequest}/approve`
- **Controller:** `Admin\WalletController@approveWithdraw`
- **Nghiệp vụ:**
  - Chỉ được duyệt khi trạng thái là `pending`.
  - Admin upload ảnh chứng từ chuyển khoản (`proof_image`, tùy chọn).
  - Ghi nhận `processed_by` và `processed_at`.
  - Tiền đã bị giữ từ trước khi đặt yêu cầu — không trừ thêm.

---

### A13.6 Từ Chối Yêu Cầu Rút Tiền
- **Route:** `POST /admin/wallet/withdrawals/{withdrawRequest}/reject`
- **Controller:** `Admin\WalletController@rejectWithdraw`
- **Nghiệp vụ:**
  - Chỉ được từ chối khi trạng thái là `pending`.
  - Gọi `WalletService::credit()` để hoàn lại tiền vào ví khách hàng.
  - Admin nhập ghi chú lý do (`admin_note`).

---

### A13.7 Điều Chỉnh Ví Thủ Công
- **Route:** `POST /admin/wallet/manual-adjust`
- **Controller:** `Admin\WalletController@manualAdjust`
- **Nghiệp vụ:**
  - Admin chọn khách hàng, loại (`credit` / `debit`), số tiền và mô tả.
  - Số tiền tối thiểu 1.000₫.
  - Tạo `WalletTransaction` loại `manual`.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem yêu cầu nạp tiền | ❌ | ✅ |
| Duyệt/Từ chối nạp tiền | ❌ | ✅ |
| Xem yêu cầu rút tiền | ❌ | ✅ |
| Duyệt/Từ chối rút tiền | ❌ | ✅ |
| Điều chỉnh thủ công | ❌ | ✅ |

## Models Liên Quan
- `WalletTopupRequest` — Bảng `wallet_topup_requests`
- `WalletWithdrawRequest` — Bảng `wallet_withdraw_requests`
- `WalletTransaction` — Bảng `wallet_transactions`
- `UserBankAccount` — Tài khoản ngân hàng nhận tiền rút
