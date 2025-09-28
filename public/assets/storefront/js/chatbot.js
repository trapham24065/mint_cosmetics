document.addEventListener('DOMContentLoaded', function () {
    const chatWidget = document.getElementById('chat-widget');
    if (!chatWidget) return;

    const chatWindow = document.getElementById('chat-window');
    const chatToggle = document.getElementById('chat-toggle');
    const chatInput = document.getElementById('chat-input');
    const chatSendBtn = document.getElementById('chat-send-btn');
    const chatMessages = document.getElementById('chat-messages');
    const chatSuggestions = document.getElementById('chat-suggestions');
    const minimizeBtn = document.getElementById('chat-minimize');
    const notificationBadge = document.querySelector('.chat-notification-badge');

    // Get URLs from data attributes
    const sendUrl = chatWidget.dataset.sendUrl;
    const suggestionsUrl = chatWidget.dataset.suggestionsUrl;
    const csrfToken = chatWidget.dataset.csrfToken;

    let isFirstOpen = true;

    // Toggle chat window
    chatToggle.addEventListener('click', () => {
        const isOpening = chatWindow.style.display !== 'flex';
        toggleChatWindow(isOpening);
    });

    // Minimize chat
    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', () => {
            toggleChatWindow(false);
        });
    }

    function toggleChatWindow(show) {
        if (show) {
            chatWindow.style.display = 'flex';
            chatToggle.style.display = 'none';
            notificationBadge.style.display = 'none';

            if (isFirstOpen) {
                setTimeout(() => {
                    showWelcomeMessage();
                    loadSuggestions();
                }, 300);
                isFirstOpen = false;
            }

            // Focus input
            setTimeout(() => chatInput.focus(), 100);
        } else {
            chatWindow.style.display = 'none';
            chatToggle.style.display = 'flex';
        }
    }

    // Show a welcome message with typing effect
    function showWelcomeMessage() {
        const welcomeMessage = document.querySelector('.welcome-message');
        if (welcomeMessage) {
            setTimeout(() => {
                const typingIndicator = welcomeMessage.querySelector('.typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }

                const messageText = "Welcome to Mint Cosmetics! 🌸 How can I help you today?";
                const messageBubble = welcomeMessage.querySelector('.message-bubble');
                messageBubble.innerHTML = messageText;
            }, 2000);
        }
    }

    // Load suggestions
    async function loadSuggestions() {
        try {
            const response = await fetch(suggestionsUrl);
            const suggestions = await response.json();

            const suggestionsList = document.querySelector('.suggestions-list');
            if (suggestionsList) {
                suggestionsList.innerHTML = '';

                suggestions.forEach(text => {
                    const btn = document.createElement('button');
                    btn.className = 'suggestion-btn';
                    btn.textContent = text;
                    suggestionsList.appendChild(btn);
                });

                // Add animation
                setTimeout(() => {
                    suggestionsList.querySelectorAll('.suggestion-btn').forEach((btn, index) => {
                        btn.style.animation = `fadeInUp 0.3s ease-out ${index * 0.1}s both`;
                    });
                }, 100);
            }
        } catch (error) {
            console.error('Failed to load suggestions:', error);
        }
    }

    // Handle suggestion clicks
    chatSuggestions.addEventListener('click', function(event) {
        if (event.target.classList.contains('suggestion-btn')) {
            const messageText = event.target.textContent;
            appendMessage('user', messageText);
            sendMessageToServer(messageText);

            // Add click effect
            event.target.style.transform = 'scale(0.95)';
            setTimeout(() => {
                event.target.style.transform = '';
            }, 100);
        }
    });

    // Send a message
    function sendMessage() {
        const messageText = chatInput.value.trim();
        if (messageText === '') return;

        appendMessage('user', messageText);
        chatInput.value = '';
        sendMessageToServer(messageText);
    }

    // Send a message to server
    function sendMessageToServer(messageText) {
        // Show typing indicator
        showTypingIndicator();

        fetch(sendUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ message: messageText }),
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            if (data.reply) {
                setTimeout(() => {
                    appendMessage('bot', data.reply);
                }, 500); // Small delay for natural feel
            }
        })
        .catch(error => {
            hideTypingIndicator();
            console.error('Chatbot error:', error);
            appendMessage('bot', 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau! 😔');
        });
    }

    // Show typing indicator
    function showTypingIndicator() {
        const typingHtml = `
            <div class="chat-message bot-message typing-message">
                <div class="message-bubble">
                    <div class="typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>`;
        chatMessages.insertAdjacentHTML('beforeend', typingHtml);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Hide typing indicator
    function hideTypingIndicator() {
        const typingMessage = chatMessages.querySelector('.typing-message');
        if (typingMessage) {
            typingMessage.remove();
        }
    }

    // Event listeners
    chatSendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Append message with animation
    function appendMessage(sender, text) {
        const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
        const messageHtml = `
            <div class="chat-message ${messageClass}">
                <div class="message-bubble">${text}</div>
            </div>`;

        chatMessages.insertAdjacentHTML('beforeend', messageHtml);

        // Add entrance animation
        const newMessage = chatMessages.lastElementChild;
        newMessage.style.opacity = '0';
        newMessage.style.transform = 'translateY(10px)';

        setTimeout(() => {
            newMessage.style.transition = 'all 0.3s ease';
            newMessage.style.opacity = '1';
            newMessage.style.transform = 'translateY(0)';
        }, 50);

        // Auto-scroll
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

// Add CSS animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
