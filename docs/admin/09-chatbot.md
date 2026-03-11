# Admin 09. Quản Lý Chatbot & Chat

## Mô tả
Module quản lý toàn bộ phiên chat của khách hàng với chatbot AI, cho phép admin theo dõi, phản hồi và cấu hình chatbot.

---

## Chức Năng

### A9.1 Danh Sách Phiên Chat
- **Route:** `GET /admin/chat`
- **Controller:** `Admin\ChatManagementController@index`
- **Hiển thị:** Danh sách phiên chat đang hoạt động với thời gian, số tin nhắn, trạng thái bot.

---

### A9.2 Chi Tiết Phiên Chat
- **Route:** `GET /admin/chat/{sessionId}`
- **Controller:** `Admin\ChatManagementController@show`
- **Hiển thị:** Toàn bộ lịch sử tin nhắn trong phiên (dạng bubble chat).

---

### A9.3 Phản Hồi Chat (Staff/Admin)
- **Route:** `POST /admin/chat/{sessionId}/reply`
- **Controller:** `Admin\ChatManagementController@reply`
- **Mô tả:** Admin có thể gửi tin nhắn vào phiên chat của khách.
- **Nghiệp vụ:** Tin nhắn được đánh dấu là từ `admin`, không phải từ bot.

---

### A9.4 Bật/Tắt Bot Trong Phiên
- **Route:** `POST /admin/chat/{sessionId}/toggle-bot`
- **Mô tả:** Tắt bot AI trong phiên cụ thể để admin tự tiếp quản hỗ trợ.

---

### A9.5 Xóa Phiên Chat
- **Route:** `DELETE /admin/chat/{sessionId}`
- **Nghiệp vụ:** Xóa mềm (soft delete) → chuyển vào Trash.

---

### A9.6 Thùng Rác Chat
- **Route:** `GET /admin/chat/trash`
- **Hỗ trợ:** Khôi phục (`POST /restore`) hoặc xóa vĩnh viễn (`DELETE /permanent`).

---

### A9.7 Câu Hỏi Gợi Ý Chatbot
- **Route:** `GET /admin/chatbot/questions` (resource)
- **Controller:** `Admin\ChatbotSuggestedQuestionController`
- **Mô tả:** CRUD các câu hỏi mẫu hiển thị cho khách hàng trong chat widget.
- **Chỉ Admin** mới được quản lý.

---

### A9.8 Cài Đặt Chatbot AI
- **Route:** `GET/POST /admin/settings/chatbot`
- **Controller:** `Admin\ChatbotSettingController`
- **Cấu hình:**
  - Gemini API Key
  - Tên bot, avatar, lời chào
  - System prompt / hướng dẫn hành vi bot
  - Bật/tắt chatbot toàn cục
  - Test kết nối Gemini API

---

### A9.9 Quản Lý Đánh Giá Sản Phẩm
- **Route:** `GET /admin/reviews`
- **Controller:** `Admin\ReviewController@index`
- **Mô tả:** Xem tất cả review khách hàng và xóa review không phù hợp.

---

## Phân Quyền
| Hành động | Staff | Admin |
|-----------|-------|-------|
| Xem & Reply chat | ✅ | ✅ |
| Quản lý câu hỏi gợi ý | ❌ | ✅ |
| Cài đặt Chatbot AI | ❌ | ✅ |
| Quản lý Review | ✅ | ✅ |
