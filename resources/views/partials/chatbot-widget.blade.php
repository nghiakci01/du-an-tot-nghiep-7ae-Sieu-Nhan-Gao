
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

<style>
    .chatbot-widget {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .chat-window {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 350px;
        height: 500px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    .chat-header {
        background-color: #511d9a;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: white;
    }
    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chat-avatar {
        width: 32px;
        height: 32px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #511d9a;
        font-weight: bold;
    }
    .chat-title h3 {
        font-weight: bold;
        font-size: 14px;
        margin: 0;
        color: white;
    }
    .chat-title p {
        font-size: 12px;
        opacity: 0.8;
        margin: 0;
        color: white;
    }
    .chat-close {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
    }
    .chat-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        background-color: #f9fafb;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .message-wrapper {
        max-width: 80%;
    }
    .message-wrapper.user {
        align-self: flex-end;
    }
    .message-wrapper.bot {
        align-self: flex-start;
    }
    .message-box {
        padding: 12px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.5;
    }
    .message-wrapper.user .message-box {
        background-color: #511d9a;
        color: white;
        border-top-right-radius: 0;
    }
    .message-wrapper.bot .message-box {
        background-color: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-top-left-radius: 0;
    }
    .message-time {
        font-size: 10px;
        color: #9ca3af;
        margin-top: 4px;
        padding: 0 4px;
    }
    .chat-input-area {
        padding: 12px;
        background: white;
        border-top: 1px solid #f3f4f6;
    }
    .chat-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chat-input {
        flex: 1;
        background-color: #f3f4f6;
        border-radius: 9999px;
        padding: 8px 16px;
        font-size: 14px;
        border: none;
        outline: none;
    }
    .chat-input:focus {
        box-shadow: 0 0 0 2px rgba(81, 29, 154, 0.5);
    }
    .chat-send-btn {
        background-color: #511d9a;
        color: white;
        padding: 8px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }
    .chat-send-btn:hover {
        background-color: #3e1675;
    }
    .chat-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .chat-footer {
        text-align: center;
        margin-top: 8px;
    }
    .chat-footer span {
        font-size: 10px;
        color: #9ca3af;
    }
    .chat-toggle-btn {
        background-color: #511d9a;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s;
        border: none;
        position: relative;
    }
    .chat-toggle-btn:hover {
        background-color: #3e1675;
        transform: scale(1.05);
    }
    .ping-animation {
        position: absolute;
        top: 0;
        right: 0;
        margin-top: -4px;
        margin-right: -4px;
        display: flex;
        height: 12px;
        width: 12px;
    }
    .ping-dot {
        position: absolute;
        display: inline-flex;
        height: 100%;
        width: 100%;
        border-radius: 50%;
        background-color: #f87171;
        opacity: 0.75;
        animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    .ping-static {
        position: relative;
        display: inline-flex;
        border-radius: 50%;
        height: 12px;
        width: 12px;
        background-color: #ef4444;
    }
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    /* Loading dots */
    .typing-indicator {
        display: flex;
        gap: 4px;
    }
    .typing-dot {
        width: 6px;
        height: 6px;
        background-color: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out both;
    }
    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
</style>

<div x-data="chatBot()" x-init="initChat()" class="chatbot-widget chat-container">
    
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="chat-window"
         style="display: none;">
        
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="chat-avatar">AI</div>
                <div class="chat-title">
                    <h3>BizChatAI Assistant</h3>
                    <p>Hỗ trợ trực tuyến 24/7</p>
                </div>
            </div>
            <button @click="toggle" class="chat-close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="chat-messages" id="chat-messages">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="['message-wrapper', msg.isUser ? 'user' : 'bot']">
                    <div class="message-box">
                        <p x-text="msg.text" style="white-space: pre-wrap; margin: 0;"></p>
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
            <form @submit.prevent="sendMessage" class="chat-form">
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
    function chatBot() {
        return {
            isOpen: false,
            messages: [
                {
                    text: "Xin chào! 👋 Tôi là trợ lý ảo AI. Tôi có thể giúp gì cho bạn hôm nay?",
                    isUser: false,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                }
            ],
            newMessage: '',
            isLoading: false,

            initChat() {
                console.log('Chatbot intialized (Inline Styles)');
            },

            toggle() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.scrollToBottom();
                }
            },

            async sendMessage() {
                const text = this.newMessage.trim();
                if (!text) return;

                // Add User Message
                this.messages.push({
                    text: text,
                    isUser: true,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                });
                
                this.newMessage = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    // Call API
                    const response = await fetch('/api/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: text })
                    });

                    const data = await response.json();

                    if (data.reply) {
                        this.messages.push({
                            text: data.reply,
                            isUser: false,
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                    } else {
                        throw new Error('No reply');
                    }

                } catch (error) {
                    console.error('Chat Error:', error);
                    this.messages.push({
                        text: "Xin lỗi, hiện tại tôi đang gặp sự cố kết nối. Vui lòng thử lại sau.",
                        isUser: false,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                    // Force re-focus input on desktop? optional
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

