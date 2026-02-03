<div id="chat-widget" class="chat-widget"
     data-send-url="{{ route('chat.send') }}"
     data-fetch-url="{{ route('chat.fetch') }}"
     data-suggestions-url="{{ route('chat.suggestions') }}"
     data-default-message-url="{{ route('chat.default-message') }}"
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
                    <i class="fa fa-heart" aria-hidden="true"></i>
                </div>
                <div class="header-text">
                    <h5>Mint Beauty Care</h5>
                    <span class="status">Online</span>
                </div>
            </div>
            <button id="chat-minimize" class="minimize-btn">
                <i class="fa fa-minus" aria-hidden="true"></i>
            </button>
        </div>

        <div id="chat-messages" class="chat-messages">
            <div class="message-bubble bot-message welcome-message">
                💕 Hello! How can we help you today? ✨
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
        bottom: 80px;
        right: 20px;
        z-index: 1000;
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ===== TOGGLE BUTTON ===== */
    .chat-toggle {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        color: white;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        cursor: pointer;
        box-shadow: 0 10px 30px rgba(255, 107, 157, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        animation: pulse 2.5s infinite;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .chat-toggle:hover {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 15px 40px rgba(255, 107, 157, 0.5);
    }

    .chat-notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        animation: bounce 1s infinite;
        border: 2px solid white;
        box-shadow: 0 4px 10px rgba(255, 71, 87, 0.4);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.4);
        }

        50% {
            box-shadow: 0 10px 40px rgba(255, 107, 157, 0.6), 0 0 0 10px rgba(255, 107, 157, 0.1);
        }

        100% {
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.4);
        }
    }

    @keyframes bounce {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    /* ===== CHAT WINDOW ===== */
    .chat-window {
        display: none;
        width: 350px;
        height: 500px;
        background: linear-gradient(to bottom, #fff5f8 0%, #ffffff 100%);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(255, 107, 157, 0.2), 0 0 0 1px rgba(255, 107, 157, 0.1);
        flex-direction: column;
        overflow: hidden;
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(255, 182, 193, 0.3);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ===== HEADER ===== */
    .chat-header {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        color: white;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(255, 107, 157, 0.2);
        position: relative;
    }

    .chat-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .header-text h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .status {
        font-size: 12px;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status::before {
        content: '';
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        display: inline-block;
        animation: statusPulse 2s infinite;
        box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.3);
    }

    @keyframes statusPulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    .minimize-btn {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
        backdrop-filter: blur(10px);
    }

    .minimize-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: scale(1.05);
    }

    /* ===== MESSAGES AREA ===== */
    .chat-messages {
        flex-grow: 1;
        padding: 25px 20px;
        overflow-y: auto;
        background: linear-gradient(to bottom, #fff5f8 0%, #ffffff 50%);
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: rgba(255, 182, 193, 0.1);
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #ff6b9d, #c44569);
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #ff5a8c, #b33559);
    }

    .welcome-message {
        text-align: center;
        margin-bottom: 25px;
        padding: 15px;
        background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(196, 69, 105, 0.05));
        border-radius: 15px;
        border: 1px dashed rgba(255, 107, 157, 0.3);
        color: #c44569;
        font-weight: 500;
    }

    .chat-message {
        margin-bottom: 18px;
        animation: messageSlide 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(15px);
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
        padding: 14px 18px;
        border-radius: 20px;
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
        font-size: 14px;
        line-height: 1.5;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .user-message .message-bubble {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
    }

    .bot-message .message-bubble {
        background: white;
        border: 2px solid rgba(255, 182, 193, 0.3);
        color: #2d3748;
        border-bottom-left-radius: 6px;
        box-shadow: 0 3px 12px rgba(255, 107, 157, 0.1);
    }

    /* ===== TYPING INDICATOR ===== */
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 10px 0;
    }

    .typing-indicator span {
        width: 9px;
        height: 9px;
        background: linear-gradient(135deg, #ff6b9d, #c44569);
        border-radius: 50%;
        animation: typing 1.4s infinite;
        box-shadow: 0 2px 5px rgba(255, 107, 157, 0.3);
    }

    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.7;
        }

        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }

    /* ===== SUGGESTIONS ===== */
    .chat-suggestions {
        border-top: 2px solid rgba(255, 182, 193, 0.2);
        background: linear-gradient(to bottom, #ffffff, #fff5f8);
        max-height: 140px;
        overflow-y: auto;
        padding: 5px 0;
    }

    .suggestions-header {
        padding: 12px 18px 8px;
        font-size: 13px;
        font-weight: 700;
        color: #c44569;
        display: flex;
        align-items: center;
        gap: 8px;
        background: transparent;
        letter-spacing: 0.3px;
    }

    .suggestions-header i {
        font-size: 16px;
        color: #ff6b9d;
    }

    .suggestions-list {
        padding: 8px 18px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .suggestion-btn {
        background: linear-gradient(135deg, #fff 0%, #fff5f8 100%);
        border: 2px solid rgba(255, 107, 157, 0.3);
        color: #c44569;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(255, 107, 157, 0.1);
    }

    .suggestion-btn:hover {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 107, 157, 0.3);
        border-color: transparent;
    }

    .suggestion-btn:active {
        transform: translateY(0);
    }

    /* ===== INPUT AREA ===== */
    .chat-input-area {
        border-top: 2px solid rgba(255, 182, 193, 0.2);
        background: linear-gradient(to top, #fff5f8, #ffffff);
        padding: 18px 20px;
    }

    .input-wrapper {
        display: flex;
        background: white;
        border-radius: 30px;
        padding: 6px;
        align-items: center;
        border: 2px solid rgba(255, 107, 157, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 3px 10px rgba(255, 107, 157, 0.1);
    }

    .input-wrapper:focus-within {
        border-color: #ff6b9d;
        box-shadow: 0 5px 20px rgba(255, 107, 157, 0.25);
        transform: translateY(-1px);
    }

    #chat-input {
        flex-grow: 1;
        border: none;
        background: none;
        padding: 12px 18px;
        outline: none;
        font-size: 14px;
        color: #2d3748;
        font-weight: 500;
    }

    #chat-input::placeholder {
        color: #cbd5e0;
        font-weight: 400;
    }

    #chat-send-btn {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(255, 107, 157, 0.3);
    }

    #chat-send-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 20px rgba(255, 107, 157, 0.4);
    }

    #chat-send-btn:active {
        transform: scale(0.95) rotate(0deg);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 480px) {
        .chat-window {
            width: 95vw;
            height: 75vh;
            bottom: 70px;
            right: 2.5vw;
            border-radius: 15px;
        }

        .chat-toggle {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }

        .message-bubble {
            max-width: 85%;
            font-size: 13px;
        }

        .chat-header {
            padding: 15px;
        }

        .avatar {
            width: 40px;
            height: 40px;
        }
    }

    /* ===== DARK MODE SUPPORT ===== */
    @media (prefers-color-scheme: dark) {
        .chat-window {
            background: linear-gradient(to bottom, #2d1b2e 0%, #1a1a1a 100%);
            color: white;
            border-color: rgba(255, 107, 157, 0.2);
        }

        .chat-messages {
            background: linear-gradient(to bottom, #2d1b2e 0%, #1a1a1a 50%);
        }

        .bot-message .message-bubble {
            background: #3d2a3e;
            border-color: rgba(255, 107, 157, 0.3);
            color: #f7fafc;
        }

        .welcome-message {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(196, 69, 105, 0.1));
            border-color: rgba(255, 107, 157, 0.4);
            color: #ff9ec4;
        }

        .suggestions-header {
            background: transparent;
            color: #ff9ec4;
        }

        .chat-suggestions {
            background: linear-gradient(to bottom, #1a1a1a, #2d1b2e);
        }

        .suggestion-btn {
            background: linear-gradient(135deg, #2d1b2e 0%, #3d2a3e 100%);
            border-color: rgba(255, 107, 157, 0.4);
            color: #ff9ec4;
        }

        .suggestion-btn:hover {
            background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
            color: white;
        }

        .input-wrapper {
            background: #2d1b2e;
            border-color: rgba(255, 107, 157, 0.3);
        }

        .input-wrapper:focus-within {
            border-color: #ff6b9d;
            box-shadow: 0 5px 20px rgba(255, 107, 157, 0.3);
        }

        #chat-input {
            color: white;
        }

        #chat-input::placeholder {
            color: #a0aec0;
        }

        .chat-input-area {
            background: linear-gradient(to top, #2d1b2e, #1a1a1a);
        }
    }
</style>
