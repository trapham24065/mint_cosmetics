document.addEventListener('DOMContentLoaded', function () {
  const chatWidget = document.getElementById('chat-widget');
  if (!chatWidget) return;

  const chatWindow = document.getElementById('chat-window');
  const chatToggle = document.getElementById('chat-toggle');
  const chatInput = document.getElementById('chat-input');
  const chatSendBtn = document.getElementById('chat-send-btn');
  const chatMessages = document.getElementById('chat-messages');
  const minimizeBtn = document.getElementById('chat-minimize');
  const notificationBadge = document.querySelector('.chat-notification-badge');
  const suggestionsContainer = document.getElementById('chat-suggestions');
  const suggestionsList = document.querySelector('.suggestions-list');

  const sendUrl = chatWidget.dataset.sendUrl;
  const fetchUrl = chatWidget.dataset.fetchUrl;
  const suggestionsUrl = chatWidget.dataset.suggestionsUrl;
  const csrfToken = chatWidget.dataset.csrfToken;

  let isChatOpen = false;
  let lastMessageId = 0;
  let isFirstLoad = true;

  chatWidget.style.display = 'block';

  // --- UI FUNCTIONS ---

  chatToggle.addEventListener('click', () => {
    toggleChatWindow(true);
  });

  if (minimizeBtn) {
    minimizeBtn.addEventListener('click', () => {
      toggleChatWindow(false);
    });
  }

  function toggleChatWindow(show) {
    isChatOpen = show;
    if (show) {
      chatWindow.style.display = 'flex';
      chatToggle.style.display = 'none';
      notificationBadge.style.display = 'none';
      notificationBadge.textContent = '0';

      scrollToBottom();

      if (isFirstLoad) {
        fetchNewMessages();
        loadSuggestions();
        isFirstLoad = false;
      }

      setTimeout(() => chatInput.focus(), 100);
    } else {
      chatWindow.style.display = 'none';
      chatToggle.style.display = 'flex';
    }
  }

  function scrollToBottom() {
    if (chatMessages) {
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }
  }

  function loadSuggestions() {
    if (!suggestionsUrl) return;

    fetch(suggestionsUrl)
    .then(response => response.json())
    .then(data => {
      const suggestions = Array.isArray(data) ? data : (data.data || []);
      if (suggestions.length > 0) {
        suggestionsList.innerHTML = '';
        suggestions.forEach(text => {
          const btn = document.createElement('button');
          btn.className = 'suggestion-btn';
          btn.textContent = text;
          btn.style.cssText = "padding: 6px 12px; border: 1px solid #007bff; background: #f0f8ff; color: #007bff; border-radius: 15px; cursor: pointer; font-size: 12px; transition: all 0.2s; margin-bottom: 5px; margin-right: 5px;";
          btn.addEventListener('click', function() {
            sendMessage(text);
          });
          suggestionsList.appendChild(btn);
        });
        suggestionsContainer.style.display = 'block';
        scrollToBottom();
      }
    })
    .catch(err => console.log('Suggestions error:', err));
  }

  // --- SEND MESSAGE ---

  function sendMessage(text = null) {
    const messageText = text || chatInput.value.trim();
    if (!messageText) return;

    // Tạo ID tạm thời duy nhất
    const tempId = 'temp_' + Date.now();

    const now = new Date();
    const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

    appendMessage('user', messageText, tempId, timeString);

    chatInput.value = '';
    sendMessageToServer(messageText, tempId);
  }

  function sendMessageToServer(messageText, tempId) {
    fetch(sendUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ message: messageText }),
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const tempMsg = document.querySelector(`.message-bubble[data-id="${tempId}"]`);
        if (tempMsg) {
          tempMsg.dataset.id = data.message.id;
        }

        if (data.message.id > lastMessageId) {
          lastMessageId = data.message.id;
        }

        if (data.bot_reply) {
          setTimeout(() => {
            if (!document.querySelector(`.message-bubble[data-id="${data.bot_reply.id}"]`)) {
              appendMessage('bot', data.bot_reply.body, data.bot_reply.id, data.bot_reply.created_at);
            }
            if (data.bot_reply.id > lastMessageId) {
              lastMessageId = data.bot_reply.id;
            }
          }, 500);
        }
      }
    })
    .catch(error => {
      console.error('Chat error:', error);
    });
  }

  // --- POLLING ---

  function fetchNewMessages() {
    const url = new URL(fetchUrl, window.location.origin);
    url.searchParams.append('last_id', lastMessageId);

    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => {
      if (!response.ok) return { messages: [] };
      return response.json();
    })
    .then(data => {
      if (data.messages && data.messages.length > 0) {
        let hasNewMessage = false;
        data.messages.forEach(msg => {
          const existingMsg = document.querySelector(`.message-bubble[data-id="${msg.id}"]`);

          if (!existingMsg) {
            const senderType = msg.is_me ? 'user' : 'bot';

            // --- SỬA LỖI LẶP TIN NHẮN ---
            // Nếu là tin nhắn của mình (user), kiểm tra xem có tin nhắn tạm (temp_) nào trùng nội dung không
            let isDuplicateOfTemp = false;
            if (msg.is_me) {
              const tempMsgs = document.querySelectorAll('.message-bubble[data-id^="temp_"]');
              for (const tempMsg of tempMsgs) {
                // So sánh nội dung tin nhắn (trim khoảng trắng để chính xác hơn)
                if (tempMsg.textContent.trim() === msg.body.trim()) {
                  // Nếu trùng, cập nhật ID thật cho nó và đánh dấu là trùng để không thêm mới
                  tempMsg.dataset.id = msg.id;
                  isDuplicateOfTemp = true;
                  break;
                }
              }
            }

            // Chỉ append nếu không phải là bản sao của tin nhắn tạm
            if (!isDuplicateOfTemp) {
              appendMessage(senderType, msg.body, msg.id, msg.created_at);
              if (!msg.is_me) hasNewMessage = true;
            }
          }

          if (msg.id > lastMessageId) lastMessageId = msg.id;
        });

        if (!isChatOpen && hasNewMessage) {
          notificationBadge.style.display = 'flex';
          notificationBadge.textContent = '!';
        }
      }
    })
    .catch(() => {});
  }

  setInterval(fetchNewMessages, 3000);

  // --- UI HELPER ---

  function appendMessage(sender, text, id, time = '') {
    const welcomeMsg = document.querySelector('.welcome-message');
    if (welcomeMsg) welcomeMsg.remove();

    if (document.querySelector(`.message-bubble[data-id="${id}"]`)) return;

    const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
    const alignItem = sender === 'user' ? 'flex-end' : 'flex-start';
    const textAlign = sender === 'user' ? 'right' : 'left';

    const messageDiv = document.createElement('div');
    messageDiv.className = `message-bubble ${messageClass}`;
    messageDiv.dataset.id = id;
    messageDiv.innerHTML = text;

    messageDiv.style.maxWidth = '100%';
    messageDiv.style.padding = '10px 14px';
    messageDiv.style.borderRadius = '12px';
    messageDiv.style.fontSize = '14px';
    messageDiv.style.lineHeight = '1.4';
    messageDiv.style.position = 'relative';
    messageDiv.style.boxShadow = '0 1px 2px rgba(0,0,0,0.1)';
    messageDiv.style.wordWrap = 'break-word';

    if (sender === 'user') {
      messageDiv.style.background = '#007bff';
      messageDiv.style.color = '#fff';
      messageDiv.style.borderBottomRightRadius = '2px';
    } else {
      messageDiv.style.background = '#f1f3f5';
      messageDiv.style.color = '#333';
      messageDiv.style.border = '1px solid #dee2e6';
      messageDiv.style.borderBottomLeftRadius = '2px';
    }

    const timeDiv = document.createElement('div');
    timeDiv.className = 'message-time';
    timeDiv.textContent = time;
    timeDiv.style.fontSize = '10px';
    timeDiv.style.color = '#999';
    timeDiv.style.marginTop = '4px';
    timeDiv.style.textAlign = textAlign;
    timeDiv.style.padding = '0 2px';

    const contentContainer = document.createElement('div');
    contentContainer.style.display = 'flex';
    contentContainer.style.flexDirection = 'column';
    contentContainer.style.maxWidth = '80%';
    contentContainer.style.alignItems = alignItem;

    contentContainer.appendChild(messageDiv);
    contentContainer.appendChild(timeDiv);

    const wrapperDiv = document.createElement('div');
    wrapperDiv.style.display = 'flex';
    wrapperDiv.style.justifyContent = alignItem;
    wrapperDiv.style.marginBottom = '12px';
    wrapperDiv.style.width = '100%';

    wrapperDiv.appendChild(contentContainer);
    chatMessages.appendChild(wrapperDiv);

    scrollToBottom();
  }

  // --- EVENTS ---

  if (chatSendBtn) {
    chatSendBtn.addEventListener('click', (e) => {
      e.preventDefault();
      sendMessage();
    });
  }

  if (chatInput) {
    chatInput.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        sendMessage();
      }
    });
  }
});

// --- DYNAMIC STYLES (Fallback) ---
const style = document.createElement('style');
style.textContent = `
    .suggestion-btn:hover {
        background: #007bff !important;
        color: white !important;
    }
    .message-bubble {
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        word-wrap: break-word;
    }
    .user-message {
        background-color: #007bff !important;
        color: white !important;
        border-bottom-right-radius: 2px;
    }
    .bot-message {
        background-color: #f1f3f5 !important;
        color: #333 !important;
        border: 1px solid #dee2e6;
        border-bottom-left-radius: 2px;
    }
    .message-time {
        font-size: 10px;
        color: #999;
        margin-top: 4px;
        padding: 0 2px;
    }
`;
document.head.appendChild(style);
