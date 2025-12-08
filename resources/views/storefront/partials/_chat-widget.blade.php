<div id="chat-widget" class="chat-widget"
     data-send-url="{{ route('chat.send') }}"
     data-fetch-url="{{ route('chat.fetch') }}"
     data-suggestions-url="{{ route('chatbot.suggestions') }}"
     data-csrf-token="{{ csrf_token() }}"
     style="display: none;">

    <div id="chat-toggle" class="chat-toggle">
        <i class="fa fa-comments" aria-hidden="true"></i>
        <span class="chat-notification-badge" style="display: none;">0</span>
    </div>

    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <div class="chat-header-info">
                <div class="avatar">
                    <i class="fa fa-android" aria-hidden="true"></i>
                </div>
                <div class="header-text">
                    <h5>Mint Support</h5>
                    <span class="status">● Online</span>
                </div>
            </div>
            <button id="chat-minimize" class="minimize-btn">
                <i class="fa fa-minus" aria-hidden="true"></i>
            </button>
        </div>

        <div id="chat-messages" class="chat-messages">
            <div class="message-bubble bot-message welcome-message">
                Hello! How can I help you today?
            </div>
        </div>

        <div id="chat-suggestions" class="chat-suggestions" style="padding: 10px 15px; display: none;">
            <div class="suggestions-header" style="font-size: 12px; color: #666; margin-bottom: 5px;">
                <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                <span>Quick hint</span>
            </div>
            <div class="suggestions-list" style="display: flex; flex-wrap: wrap; gap: 5px;">
            </div>
        </div>

        <div class="chat-input-area">
            <div class="input-wrapper">
                <input type="text" id="chat-input" placeholder="Enter message..." autocomplete="off">
                <button id="chat-send-btn">
                    <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== MAIN CONTAINER ===== */
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ===== TOGGLE BUTTON ===== */
    .chat-toggle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
        animation: pulse 2s infinite;
    }

    .chat-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }

    .chat-notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4757;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        animation: bounce 1s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
        50% { box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
        100% { box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    /* ===== CHAT WINDOW ===== */
    .chat-window {
        display: none;
        width: 380px;
        height: 520px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ===== HEADER ===== */
    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .header-text h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .status {
        font-size: 12px;
        opacity: 0.9;
    }

    .minimize-btn {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        padding: 5px;
        border-radius: 5px;
        transition: background 0.2s;
    }

    .minimize-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    /* ===== MESSAGES AREA ===== */
    .chat-messages {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .welcome-message {
        text-align: center;
        margin-bottom: 20px;
    }

    .chat-message {
        margin-bottom: 15px;
        animation: messageSlide 0.3s ease-out;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .user-message {
        text-align: right;
    }

    .bot-message {
        text-align: left;
    }

    .message-bubble {
        display: inline-block;
        padding: 12px 16px;
        border-radius: 18px;
        max-width: 80%;
        word-wrap: break-word;
        position: relative;
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 5px;
    }

    .bot-message .message-bubble {
        background: white;
        border: 1px solid #e9ecef;
        color: #333;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* ===== TYPING INDICATOR ===== */
    .typing-indicator {
        display: flex;
        gap: 3px;
        padding: 8px 0;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #999;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(1) { animation-delay: 0s; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    /* ===== SUGGESTIONS ===== */
    .chat-suggestions {
        border-top: 1px solid #e9ecef;
        background: white;
        max-height: 120px;
        overflow-y: auto;
    }

    .suggestions-header {
        padding: 10px 15px;
        font-size: 12px;
        font-weight: 600;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
        background: #f8f9fa;
    }

    .suggestions-list {
        padding: 10px 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .suggestion-btn {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .suggestion-btn:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* ===== INPUT AREA ===== */
    .chat-input-area {
        border-top: 1px solid #e9ecef;
        background: white;
        padding: 15px;
    }

    .input-wrapper {
        display: flex;
        background: #f8f9fa;
        border-radius: 25px;
        padding: 5px;
        align-items: center;
        border: 1px solid #e9ecef;
        transition: border-color 0.2s;
    }

    .input-wrapper:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    #chat-input {
        flex-grow: 1;
        border: none;
        background: none;
        padding: 12px 15px;
        outline: none;
        font-size: 14px;
        color: #333;
    }

    #chat-input::placeholder {
        color: #999;
    }

    #chat-send-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    #chat-send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    #chat-send-btn:active {
        transform: scale(0.95);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 480px) {
        .chat-window {
            width: 95vw;
            height: 70vh;
            bottom: 80px;
            right: 2.5vw;
        }

        .chat-toggle {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }

    /* ===== DARK MODE SUPPORT ===== */
    @media (prefers-color-scheme: dark) {
        .chat-window {
            background: #2d3748;
            color: white;
        }

        .chat-messages {
            background: #1a202c;
        }

        .bot-message .message-bubble {
            background: #4a5568;
            border-color: #2d3748;
            color: white;
        }

        .suggestions-header {
            background: #2d3748;
            color: #cbd5e0;
        }

        .input-wrapper {
            background: #2d3748;
            border-color: #4a5568;
        }

        #chat-input {
            color: white;
        }

        #chat-input::placeholder {
            color: #a0aec0;
        }
    }
</style>
