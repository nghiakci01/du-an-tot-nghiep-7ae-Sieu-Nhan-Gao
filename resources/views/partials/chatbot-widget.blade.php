
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@300;400;500;600;700&display=swap');

    .chatbot-widget {
        font-family: 'Libre Franklin', sans-serif;
        --primary-color: #7146ce; /* Purple color from BizChatAI image */
        --primary-gradient: linear-gradient(135deg, #7146ce 0%, #9063f2 100%);
        --secondary-color: #242424;
        --bg-color: #ffffff;
        --light-grey: #f4f6f9;
        --shadow-lg: 0 15px 50px -12px rgba(0,0,0,0.1);
        --border-radius: 24px;
    }
    .chat-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .chat-window {
        background: #ffffff;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        width: 400px;
        height: 600px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #edeff2;
        margin-bottom: 20px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        transform-origin: bottom right;
    }
    .chat-window.fullscreen {
        width: calc(100vw - 60px);
        height: calc(100vh - 120px);
        max-width: 1200px;
        border-radius: 12px;
    }
    @media (max-width: 480px) {
        .chat-window {
            width: calc(100vw - 40px);
            height: calc(100vh - 100px);
            bottom: 20px;
            right: 20px;
        }
        .chat-window.fullscreen {
            width: calc(100vw - 20px);
            height: calc(100vh - 40px);
            bottom: 10px;
            right: 10px;
        }
    }
    .chat-header {
        background: #ffffff;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #1a1a1a;
        border-bottom: 1px solid #f0f2f5;
    }
    .chat-header .chat-avatar {
        background: #f0ebff;
        color: var(--primary-color);
    }
    .chat-header .chat-title h3 {
        color: #1a1a1a;
    }
    .chat-header .chat-title p {
        color: #6b7280;
    }
    .chat-header .chat-close {
        color: #9ca3af;
        background: transparent;
    }
    .chat-header .chat-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }
    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .chat-avatar {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: 700;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        font-size: 16px;
    }
    .chat-title h3 {
        font-weight: 700;
        font-size: 16px;
        margin: 0;
        color: #1a1a1a;
        letter-spacing: 0.5px;
    }
    .chat-title p {
        font-size: 12px;
        margin: 0;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .status-dot {
        width: 8px;
        height: 8px;
        background-color: #4ade80;
        border-radius: 50%;
    }
    .chat-header .chat-close {
        color: #9ca3af;
        background: transparent;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .chat-header .chat-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }
    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 16px;
        scroll-behavior: smooth;
    }
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    .chat-messages::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 3px;
    }
    .message-wrapper {
        max-width: 85%;
        display: flex;
        flex-direction: column;
        animation: slideIn 0.3s ease-out forwards;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .message-wrapper.user {
        align-self: flex-end;
        align-items: flex-end;
    }
    .message-wrapper.bot {
        align-self: flex-start;
        align-items: flex-start;
    }
    .message-box {
        padding: 14px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.6;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        position: relative;
    }
    .message-wrapper.user .message-box {
        background: var(--primary-gradient);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(113, 70, 206, 0.2);
    }
    .message-wrapper.bot .message-box {
        background-color: #f8f9fa;
        color: #1f2937;
        border: none;
        border-bottom-left-radius: 4px;
    }
    .message-wrapper.staff .message-box {
        background: var(--primary-gradient);
        color: white;
        border-bottom-left-radius: 4px;
        box-shadow: 0 4px 12px rgba(113, 70, 206, 0.2);
    }
    .message-wrapper.staff {
        align-self: flex-start;
        align-items: flex-start;
    }
    .message-time {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 6px;
        font-weight: 500;
    }
    .chat-input-area {
        padding: 20px;
        background: white;
        border-top: 1px solid #f3f4f6;
    }
    .chat-form {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        padding: 8px 8px 8px 20px;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .chat-form:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 4px 20px rgba(113, 70, 206, 0.1);
    }
    .chat-input {
        flex: 1;
        background: transparent;
        border: none;
        padding: 8px 12px;
        font-size: 14px;
        outline: none;
        color: #374151;
    }
    .chat-send-btn {
        background: var(--primary-color);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
        box-shadow: 0 4px 10px rgba(113, 70, 206, 0.3);
    }
    .chat-send-btn:hover {
        transform: scale(1.05);
    }
    .chat-send-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Product List Design */
    .product-cards {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 12px;
    }
    .product-card {
        background: #ffffff;
        border: 1px solid #f0f2f5;
        border-radius: 16px;
        display: flex; /* Horizontal Layout */
        overflow: hidden;
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding: 10px;
        gap: 12px;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: var(--primary-color);
    }
    .product-card-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
    }
    .product-card-body {
        padding: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .product-card-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
        height: auto;
    }
    .product-card-description {
        font-size: 11px;
        color: #6b7280;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-card-price {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-color);
    }
    .view-detail-btn {
        font-size: 11px;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Toggle Button */
    .chat-toggle-btn {
        background: var(--primary-gradient);
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(113, 70, 206, 0.3);
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        position: relative;
    }
    .chat-toggle-btn:hover {
        transform: scale(1.1) rotate(-5deg);
    }
    .ping-animation {
        position: absolute;
        top: 2px;
        right: 2px;
    }
    .ping-dot {
        background-color: #fff;
    }
    .ping-static {
        background-color: #ffffff;
        border: 2px solid var(--primary-color);
    }

    /* Typing */
    .typing-indicator {
        display: flex;
        gap: 5px;
        padding: 4px;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background-color: #d1d5db;
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    .action-chip {
        background: white;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .action-chip:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(113, 70, 206, 0.2);
    }
    .chat-footer {
        text-align: center;
        margin-top: 10px;
    }
    .chat-footer span {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
    }
</style>

<div x-data="chatBot({ mode: '{{ $chatbot_mode ?? 'rules' }}' })" x-init="initChat()" class="chatbot-widget chat-container">
    
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="chat-window"
         :class="{ 'fullscreen': isFullscreen }"
         style="display: none;">
        
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2 2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/><path d="m5.2 6.8 1.4-1.4"/><path d="m17.4 5.4 1.4 1.4"/><path d="M5 13a7 7 0 1 0 14 0"/><path d="M8 12h8"/><path d="M12 12v4"/></svg>
                </div>
                <div class="chat-title">
                    <h3>Trợ lý ảo Reid</h3>
                    <p><span class="status-dot"></span> Sẵn sàng hỗ trợ</p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button @click="toggleFullscreen" class="chat-close" :title="isFullscreen ? 'Thu nhỏ' : 'Phóng to'">
                    <svg x-show="!isFullscreen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    <svg x-show="isFullscreen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16h4v4m0-4L3 21m18-5h-4v4m0-4l4 5M3 8h4V4M7 8L3 3m18 5h-4V4m0 4l4-5" />
                    </svg>
                </button>
                <button @click="toggle" class="chat-close" title="Đóng">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="chat-messages" id="chat-messages">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="['message-wrapper', msg.isUser ? 'user' : (msg.sender_type === 'staff' ? 'staff' : 'bot')]">
                    <div class="message-box">
                        <!-- Text Message -->
                        <p x-show="!msg.products || msg.products.length === 0" 
                           x-text="msg.text" 
                           style="white-space: pre-wrap; margin: 0;"></p>
                        
                        <!-- Message with Products -->
                        <template x-if="msg.products && msg.products.length > 0">
                            <div>
                                <p x-text="msg.text" style="white-space: pre-wrap; margin: 0 0 12px 0;"></p>
                                <div class="product-cards">
                                    <template x-for="product in msg.products" :key="product.id">
                                        <a :href="product.url" class="product-card" target="_blank">
                                            <img :src="product.image" :alt="product.name" class="product-card-image">
                                            <div class="product-card-body">
                                                <h4 class="product-card-title" x-text="product.name"></h4>
                                                <p class="product-card-description" x-text="product.description"></p>
                                                <div class="product-card-footer">
                                                    <p class="product-card-price" x-text="product.price_formatted"></p>
                                                    <span class="view-detail-btn">
                                                        Xem chi tiết
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Quick Actions (Suggested Questions) -->
                        <template x-if="msg.quickActions && msg.quickActions.length > 0">
                            <div class="quick-actions">
                                <template x-for="action in msg.quickActions">
                                    <button @click="sendMessage(action)" class="action-chip" x-text="action"></button>
                                </template>
                            </div>
                        </template>
                    </div>
                    <p class="message-time" 
                       :style="{ textAlign: msg.isUser ? 'right' : 'left' }"
                       x-text="msg.time">
                    </p>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="message-wrapper bot">
                <div class="message-box">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <form @submit.prevent="sendMessage()" class="chat-form">
                <input type="text" 
                       x-model="newMessage"
                       placeholder="Nhập tin nhắn..." 
                       class="chat-input"
                       :disabled="isLoading">
                
                <button type="submit" 
                        class="chat-send-btn"
                        :disabled="!newMessage.trim() || isLoading">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            <div class="chat-footer">
                <span>Powered by BizChatAI</span>
            </div>
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="toggle" 
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-50"
            x-transition:enter-end="opacity-100 scale-100"
            class="chat-toggle-btn">
        <!-- Chat Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" style="color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <!-- Pulse effect -->
        <span class="ping-animation">
            <span class="ping-dot"></span>
            <span class="ping-static"></span>
        </span>
    </button>

</div>

<script>
    function chatBot(config = {}) {
        return {
            isOpen: false,
            isFullscreen: false,
            messages: [],
            newMessage: '',
            isLoading: false,
            mode: config.mode || 'rules',

            async initChat() {
                console.log('Chatbot initialized (History Sync Enabled)');
                
                // Fetch initial history
                await this.pollMessages();
                
                // If no messages, show greeting
                if (this.messages.length === 0) {
                    this.messages.push({
                        text: "Xin chào! 👋 Chào mừng bạn đến với Reid Fashion. Tôi giúp được gì cho bạn?",
                        isUser: false,
                        sender_type: 'bot',
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        quickActions: {!! json_encode($chatbot_suggested_questions ?? ['Hàng mới về 🆕', 'Khuyến mãi 🔥', 'Áo thun', 'Váy đầm', 'Liên hệ']) !!}
                    });
                }

                // Start polling for new messages (staff replies)
                setInterval(() => {
                    if (this.isOpen && !this.isLoading) {
                        this.pollMessages();
                    }
                }, 3000); // Poll every 3 seconds for better responsiveness
            },

            async pollMessages() {
                try {
                    const response = await fetch('/api/chat/messages');
                    if (response.ok) {
                        const data = await response.json();
                        if (data.status === 'success') {
                            // Extract existing message IDs to find only new ones
                            const existingIds = this.messages.filter(m => m.id).map(m => m.id);
                            const newMessages = data.messages.filter(m => !existingIds.includes(m.id));
                            
                            if (newMessages.length > 0) {
                                // Add only truly new messages
                                this.messages.push(...newMessages);
                                this.scrollToBottom();
                            } else if (this.messages.length === 0 && data.messages.length > 0) {
                                // Initial load
                                this.messages = data.messages;
                                this.scrollToBottom();
                            }
                        }
                    }
                } catch (e) {
                    console.warn('Polling failed', e);
                }
            },

            toggle() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.scrollToBottom();
                    // Sync on open
                    this.pollMessages();
                } else {
                    this.isFullscreen = false;
                }
            },

            toggleFullscreen() {
                this.isFullscreen = !this.isFullscreen;
                this.scrollToBottom();
            },

            async sendMessage(textInput = null) {
                const text = (typeof textInput === 'string') ? textInput : this.newMessage.trim();
                if (!text || typeof text !== 'string') return;

                // Add User Message locally with a temp flag
                const tempUserMsg = {
                    text: text,
                    isUser: true,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    isTemp: true
                };
                this.messages.push(tempUserMsg);
                
                this.newMessage = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('/api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify({ message: text })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();
                    
                    // Remove the temporary message before syncing
                    this.messages = this.messages.filter(m => !m.isTemp);

                    // Re-sync messages from server to get official IDs and the bot reply
                    await this.pollMessages();

                } catch (error) {
                    console.error('Chat Error:', error);
                    // Remove temp on error too
                    this.messages = this.messages.filter(m => !m.isTemp);
                    
                    const errorText = "Xin lỗi, hiện tại tôi đang gặp sự cố kết nối. Vui lòng thử lại sau.";
                    this.messages.push({
                        text: errorText,
                        isUser: false,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = document.getElementById('chat-messages');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        }
    }
</script>

