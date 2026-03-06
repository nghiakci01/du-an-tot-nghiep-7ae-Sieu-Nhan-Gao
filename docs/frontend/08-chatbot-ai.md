# 08. Chatbot AI Tư Vấn Sản Phẩm

## Mô tả
Chatbot tích hợp Google Gemini AI, sử dụng kỹ thuật RAG (Retrieval-Augmented Generation) để tư vấn sản phẩm và trả lời câu hỏi của khách hàng theo thời gian thực.

---

## Chức Năng

### 8.1 Gửi Tin Nhắn
- **Route:** `POST /api/chat/send`
- **Controller:** `Api\ChatController@sendMessage`
- **Đầu vào:**
  - `message` — Nội dung tin nhắn của khách hàng
  - `session_id` — ID phiên chat (tạo tự động từ browser)
- **Đầu ra:** JSON chứa phản hồi của AI
- **Nghiệp vụ:**
  1. Lưu tin nhắn người dùng vào `chat_messages`.
  2. Truy vấn danh sách sản phẩm liên quan (RAG) từ database.
  3. Gửi context + câu hỏi lên Gemini API.
  4. Nhận và lưu phản hồi AI vào `chat_messages`.
  5. Trả về phản hồi.

---

### 8.2 Lịch Sử Tin Nhắn
- **Route:** `GET /api/chat/messages`
- **Controller:** `Api\ChatController@getMessages`
- **Đầu vào:** `session_id`
- **Đầu ra:** JSON danh sách tin nhắn trong phiên chat hiện tại.

---

### 8.3 Câu Hỏi Gợi Ý
- **Mô tả:** Hiển thị các câu hỏi mẫu để khách hàng nhấn thay vì phải gõ.
- **Nguồn dữ liệu:** Bảng `chatbot_suggested_questions` (do admin cấu hình).

---

### 8.4 Cài Đặt Chatbot (Admin)
- **Route:** `GET/POST /admin/settings/chatbot`
- **Controller:** `Admin\ChatbotSettingController`
- **Cấu hình:**
  - Gemini API Key
  - Tên chatbot, lời chào mừng
  - Bật/tắt chatbot
  - Test kết nối API

---

## Nghiệp Vụ RAG (Retrieval-Augmented Generation)
1. Phân tích câu hỏi → trích xuất từ khóa sản phẩm.
2. Tìm sản phẩm khớp trong database (tên, mô tả, danh mục).
3. Xây dựng context prompt kèm thông tin sản phẩm.
4. Gửi lên Gemini → nhận câu trả lời có thông tin thực tế từ shop.

---

## Models Liên Quan
- `ChatSession` — Bảng `chat_sessions` (session_id, user_id nullable)
- `ChatMessage` — Bảng `chat_messages` (session_id, role: user|bot, content)
- `ChatbotSetting` — Bảng `chatbot_settings`
- `ChatbotSuggestedQuestion` — Bảng `chatbot_suggested_questions`
