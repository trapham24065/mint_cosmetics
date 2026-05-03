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

  const attachBtn = document.getElementById('chat-attach-btn');
  const attachmentInput = document.getElementById('chat-attachment-input');
  const attachmentPreview = document.getElementById('chat-attachment-preview');
  const attachmentGallery = document.getElementById('chat-attachment-gallery');
  const attachmentCounter = document.getElementById('chat-attachment-count');
  const lightbox = document.getElementById('chat-lightbox');
  const lightboxImg = document.getElementById('chat-lightbox-img');
  const lightboxCloseBtn = document.getElementById('chat-lightbox-close');

  const sendUrl = chatWidget.dataset.sendUrl;
  const fetchUrl = chatWidget.dataset.fetchUrl;
  const suggestionsUrl = chatWidget.dataset.suggestionsUrl;
  const defaultMessageUrl = chatWidget.dataset.defaultMessageUrl || '/chat/default-message';
  const csrfToken = chatWidget.dataset.csrfToken;

  const MAX_ATTACHMENT_BYTES = 5 * 1024 * 1024;
  const MAX_ATTACHMENTS = 5;
  const ALLOWED_ATTACHMENT_MIMES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

  let isChatOpen = false;
  let lastMessageId = 0;
  let isFirstLoad = true;
  let waitingForAdminTimeout = null;
  let lastRenderedDateKey = null;
  let pendingAttachments = [];

  // Generate and persist guest_id in localStorage for session continuity
  let guestId = localStorage.getItem('chat_guest_id');
  if (!guestId) {
    guestId = 'guest_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    localStorage.setItem('chat_guest_id', guestId);
    console.log('[Chat] Generated new guest_id:', guestId);
  } else {
    console.log('[Chat] Restored guest_id from localStorage:', guestId);
  }

  chatWidget.style.display = 'block';

  // Load messages immediately on page load (before user opens chat)
  function loadInitialMessages() {
    const url = new URL(fetchUrl, window.location.origin);
    url.searchParams.append('last_id', 0);
    url.searchParams.append('guest_id', guestId);

    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => {
      if (!response.ok) return { messages: [] };
      return response.json();
    })
    .then(data => {
      if (data.messages && data.messages.length > 0) {
        data.messages.forEach(msg => {
          if (msg.id > lastMessageId) {
            lastMessageId = msg.id;
          }
          // Render message even if chat window not open
          const senderType = msg.is_me ? 'user' : 'bot';
          const attachments = msg.attachments || (msg.attachment ? [msg.attachment] : []);
          appendMessage(senderType, msg.body, msg.id, msg.created_at, msg.created_at_date || toDateKey(msg.created_at_raw || new Date()), formatDateLabel(msg.created_at_raw || new Date()), attachments);
        });
      }
    })
    .catch(err => {
      console.warn('[Chat] Initial load error:', err);
    });
  }

  // Trigger initial load
  loadInitialMessages();

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

  function toDateKey(dateValue) {
    const date = dateValue instanceof Date ? dateValue : new Date(dateValue);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
  }

  function formatDateLabel(dateValue) {
    const date = dateValue instanceof Date ? dateValue : new Date(dateValue);
    const today = new Date();
    const startOfToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    const startOfDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    const diffDays = Math.round((startOfToday - startOfDate) / 86400000);

    if (diffDays === 0) return 'Hôm nay';
    if (diffDays === 1) return 'Hôm qua';

    return new Intl.DateTimeFormat('vi-VN').format(date);
  }

  function appendDateSeparator(dateKey, label) {
    if (!dateKey || dateKey === lastRenderedDateKey) {
      return;
    }

    const separator = document.createElement('div');
    separator.className = 'chat-date-separator';
    separator.dataset.date = dateKey;
    separator.style.display = 'flex';
    separator.style.justifyContent = 'center';
    separator.style.margin = '14px 0';

    const badge = document.createElement('span');
    badge.textContent = label;
    badge.style.display = 'inline-flex';
    badge.style.alignItems = 'center';
    badge.style.justifyContent = 'center';
    badge.style.padding = '6px 12px';
    badge.style.borderRadius = '999px';
    badge.style.border = '1px solid rgba(255, 107, 157, 0.18)';
    badge.style.background = 'rgba(255, 255, 255, 0.85)';
    badge.style.color = '#718096';
    badge.style.fontSize = '12px';
    badge.style.fontWeight = '600';
    badge.style.boxShadow = '0 2px 8px rgba(0,0,0,0.04)';

    separator.appendChild(badge);
    chatMessages.appendChild(separator);
    lastRenderedDateKey = dateKey;
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
          btn.style.cssText = "padding: 6px 12px; background: #f0f8ff; border-radius: 15px; cursor: pointer; font-size: 12px; transition: all 0.2s; margin-bottom: 5px; margin-right: 5px;";
          btn.addEventListener('click', function() {
            // Đánh dấu tin nhắn này là từ Quick Hint
            sendMessage(text, true);
          });
          suggestionsList.appendChild(btn);
        });
        suggestionsContainer.style.display = 'block';
        scrollToBottom();
      }
    })
    .catch(err => console.log('Suggestions error:', err));
  }

  // --- ATTACHMENT HANDLING ---

  function setPendingAttachment(files) {
    const incomingFiles = Array.isArray(files) ? files : (files ? [files] : []);

    if (incomingFiles.length === 0) {
      clearPendingAttachments();
      return;
    }

    const remainingSlots = MAX_ATTACHMENTS - pendingAttachments.length;
    if (remainingSlots <= 0) {
      alert(`Bạn chỉ có thể chọn tối đa ${MAX_ATTACHMENTS} ảnh.`);
      attachmentInput.value = '';
      return;
    }

    const filesToAdd = incomingFiles.slice(0, remainingSlots);

    filesToAdd.forEach((file) => {
      if (!ALLOWED_ATTACHMENT_MIMES.includes(file.type)) {
        alert(`File "${file.name}" không phải là hình ảnh hợp lệ.`);
        return;
      }

      if (file.size > MAX_ATTACHMENT_BYTES) {
        alert(`File "${file.name}" vượt quá 5MB.`);
        return;
      }

      pendingAttachments.push(file);
    });

    if (attachmentGallery) {
      attachmentGallery.innerHTML = '';
      pendingAttachments.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          try {
            addPreviewThumb(file.name, e.target.result, idx);
          } catch (err) {
            console.error('Preview render error:', err, file.name);
          }
        };
        reader.readAsDataURL(file);
      });
    } else {
      // If gallery element is missing, avoid FileReader operations to prevent runtime errors
      console.warn('Attachment gallery not found in DOM');
    }

    attachmentCounter.textContent = pendingAttachments.length;
    attachmentPreview.style.display = pendingAttachments.length > 0 ? 'block' : 'none';
  }

  function addPreviewThumb(fileName, dataUrl, index) {
    const thumbContainer = document.createElement('div');
    thumbContainer.className = 'chat-attachment-thumb';
    thumbContainer.dataset.index = index;
    thumbContainer.style.cssText = `
      position: relative;
      width: 80px;
      height: 80px;
      border-radius: 8px;
      overflow: hidden;
      background: #f0f0f0;
      cursor: pointer;
    `;

    const img = document.createElement('img');
    try {
      img.src = dataUrl;
    } catch (err) {
      console.error('Failed to set preview image src', err, fileName);
      img.src = '';
    }
    img.alt = fileName;
    img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
    img.addEventListener('click', () => openLightbox(dataUrl));

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'chat-attachment-thumb-remove';
    removeBtn.setAttribute('aria-label', 'Xoá ảnh');
    removeBtn.style.cssText = `
      position: absolute;
      top: 0;
      right: 0;
      width: 20px;
      height: 20px;
      background: rgba(0,0,0,0.6);
      color: white;
      border: none;
      border-radius: 0;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      padding: 0;
    `;
    removeBtn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
    removeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      removeAttachmentAtIndex(index);
    });

    thumbContainer.appendChild(img);
    thumbContainer.appendChild(removeBtn);
    if (attachmentGallery) {
      attachmentGallery.appendChild(thumbContainer);
    } else {
      console.warn('Cannot append preview thumb, gallery missing');
    }
  }

  function removeAttachmentAtIndex(index) {
    pendingAttachments.splice(index, 1);
    if (attachmentGallery) {
      attachmentGallery.innerHTML = '';
      pendingAttachments.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          try {
            addPreviewThumb(file.name, e.target.result, idx);
          } catch (err) {
            console.error('Preview render error:', err, file.name);
          }
        };
        reader.readAsDataURL(file);
      });
    }

    attachmentCounter.textContent = pendingAttachments.length;
    if (attachmentPreview) attachmentPreview.style.display = pendingAttachments.length > 0 ? 'block' : 'none';
    attachmentInput.value = '';
  }

  function clearPendingAttachments() {
    pendingAttachments = [];
    if (attachmentGallery) attachmentGallery.innerHTML = '';
    attachmentInput.value = '';
    attachmentCounter.textContent = '0';
    if (attachmentPreview) attachmentPreview.style.display = 'none';
  }

  if (attachBtn) {
    attachBtn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        attachmentInput.click();
      }
    });
  }

  if (attachmentInput) {
    attachmentInput.addEventListener('change', (e) => {
      const files = e.target.files;
      if (files && files.length > 0) {
        // Validate total count
        if (pendingAttachments.length + files.length > MAX_ATTACHMENTS) {
          alert(`Bạn chỉ có thể chọn tối đa ${MAX_ATTACHMENTS} ảnh. Hiện tại đã có ${pendingAttachments.length} ảnh, bạn chỉ có thể thêm ${MAX_ATTACHMENTS - pendingAttachments.length} ảnh nữa.`);
          attachmentInput.value = '';
          return;
        }

        // Validate each file
        const validFiles = [];
        for (let i = 0; i < files.length; i++) {
          const file = files[i];
          if (!ALLOWED_ATTACHMENT_MIMES.includes(file.type)) {
            alert(`File "${file.name}" không phải là hình ảnh hợp lệ.`);
            continue;
          }
          if (file.size > MAX_ATTACHMENT_BYTES) {
            alert(`File "${file.name}" vượt quá 5MB.`);
            continue;
          }
          validFiles.push(file);
        }

        setPendingAttachment(validFiles);
        attachmentInput.value = '';
      }
    });
  }

  // --- LIGHTBOX ---

  function openLightbox(src) {
    if (!lightbox || !lightboxImg) return;
    try {
      lightboxImg.src = src;
    } catch (err) {
      console.error('Lightbox image error', err, src);
      lightboxImg.src = '';
    }
    lightbox.style.display = 'flex';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.style.display = 'none';
    lightboxImg.src = '';
  }

  if (lightboxCloseBtn) {
    lightboxCloseBtn.addEventListener('click', closeLightbox);
  }

  if (lightbox) {
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox) closeLightbox();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && lightbox && lightbox.style.display === 'flex') {
      closeLightbox();
    }
  });

  // --- SEND MESSAGE ---

  function sendMessage(text = null, isFromQuickHint = false) {
    const messageText = (text !== null ? text : chatInput.value).trim();
    const hasAttachments = pendingAttachments.length > 0;

    if (!messageText && !hasAttachments) return;

    const tempId = 'temp_' + Date.now();
    const now = new Date();
    const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    const dateKey = toDateKey(now);

    // Build optimistic attachments array from preview gallery
    let optimisticAttachments = [];
    if (hasAttachments) {
      const thumbs = document.querySelectorAll('.chat-attachment-thumb') || [];
      thumbs.forEach((thumb) => {
        try {
          const img = thumb ? thumb.querySelector('img') : null;
          const url = img && img.src ? img.src : null;
          if (url) {
            optimisticAttachments.push({
              url: url, // Data URL from preview
              is_image: true,
              original_name: img.alt || '',
            });
          }
        } catch (err) {
          console.error('Error reading preview thumb src', err);
        }
      });
    }

    appendMessage('user', messageText, tempId, timeString, dateKey, formatDateLabel(now), optimisticAttachments);

    chatInput.value = '';
    const filesToSend = Array.from(pendingAttachments);
    clearPendingAttachments();

    sendMessageToServer(messageText, tempId, isFromQuickHint, filesToSend);
  }

  function sendMessageToServer(messageText, tempId, isFromQuickHint = false, files = []) {
    const formData = new FormData();
    formData.append('message', messageText || '');
    formData.append('is_quick_hint', isFromQuickHint ? '1' : '0');
    formData.append('guest_id', guestId);

    // Append multiple files
    files.forEach((file) => {
      formData.append('attachments[]', file);
    });

    fetch(sendUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: formData,
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
          if (waitingForAdminTimeout) {
            clearTimeout(waitingForAdminTimeout);
            waitingForAdminTimeout = null;
          }

          setTimeout(() => {
            if (!document.querySelector(`.message-bubble[data-id="${data.bot_reply.id}"]`)) {
              const attachments = data.bot_reply.attachments || (data.bot_reply.attachment ? [data.bot_reply.attachment] : []);
              appendMessage('bot', data.bot_reply.body, data.bot_reply.id, data.bot_reply.created_at, data.bot_reply.created_at_date || toDateKey(data.bot_reply.created_at_raw || new Date()), formatDateLabel(data.bot_reply.created_at_raw || new Date()), attachments);
            }
            if (data.bot_reply.id > lastMessageId) {
              lastMessageId = data.bot_reply.id;
            }
          }, 500);
        } else if (!isFromQuickHint) {
          waitingForAdminTimeout = setTimeout(() => {
            sendDefaultMessageIfNoReply();
          }, 120000);
        }
      } else if (data.message) {
        alert(data.message);
      }
    })
    .catch(error => {
      console.error('Chat error:', error);
    });
  }

  function sendDefaultMessageIfNoReply() {
    fetch(defaultMessageUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ guest_id: guestId })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success && data.bot_reply) {
        if (!document.querySelector(`.message-bubble[data-id="${data.bot_reply.id}"]`)) {
          const attachments = data.bot_reply.attachments || (data.bot_reply.attachment ? [data.bot_reply.attachment] : []);
          appendMessage('bot', data.bot_reply.body, data.bot_reply.id, data.bot_reply.created_at, data.bot_reply.created_at_date || toDateKey(data.bot_reply.created_at_raw || new Date()), formatDateLabel(data.bot_reply.created_at_raw || new Date()), attachments);
        }
        if (data.bot_reply.id > lastMessageId) {
          lastMessageId = data.bot_reply.id;
        }
      }
    })
    .catch(error => {
      console.error('Default message error:', error);
    });
  }

  // --- POLLING ---

  function fetchNewMessages() {
    const url = new URL(fetchUrl, window.location.origin);
    url.searchParams.append('last_id', lastMessageId);
    url.searchParams.append('guest_id', guestId);

    fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => {
      if (!response.ok) {
        console.warn('[Chat] Fetch failed with status:', response.status);
        return { messages: [] };
      }
      return response.json();
    })
    .then(data => {
      if (data.messages && data.messages.length > 0) {
        let hasNewMessage = false;
        data.messages.forEach(msg => {
          // Check if message already exists (either from optimistic render or previous fetch)
          let existingMsg = document.querySelector(`.message-bubble[data-id="${msg.id}"]`);

          if (!existingMsg && msg.is_me) {
            // For customer's own messages, check if there's a temp message that needs updating
            const tempMsgs = document.querySelectorAll('.message-bubble[data-id^="temp_"]');
            for (const tempMsg of tempMsgs) {
              // Match by first temp message (FIFO approach) to avoid duplicate appends
              tempMsg.dataset.id = msg.id;

              // Update attachment URLs if server provided real URLs
              const attachments = msg.attachments || (msg.attachment ? [msg.attachment] : []);
              if (attachments.length > 0) {
                const tempImgs = tempMsg.querySelectorAll('img.chat-bubble-image');
                attachments.forEach((att, index) => {
                  if (tempImgs[index] && att && att.url) {
                    tempImgs[index].src = att.url;
                  }
                });
              }
              existingMsg = tempMsg;
              break;
            }
          }

          if (!existingMsg) {
            // Only append if message doesn't already exist
            const senderType = msg.is_me ? 'user' : 'bot';
            const attachments = msg.attachments || (msg.attachment ? [msg.attachment] : []);
            appendMessage(senderType, msg.body, msg.id, msg.created_at, msg.created_at_date || toDateKey(msg.created_at_raw || new Date()), formatDateLabel(msg.created_at_raw || new Date()), attachments);

            if (!msg.is_me) {
              hasNewMessage = true;
              if (waitingForAdminTimeout) {
                clearTimeout(waitingForAdminTimeout);
                waitingForAdminTimeout = null;
              }
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
    .catch(err => {
      console.warn('[Chat] Polling error:', err);
    });
  }

  // Set up continuous polling - runs regardless of chat window state
  let pollInterval = setInterval(fetchNewMessages, 3000);

  // --- UI HELPER ---

  function appendMessage(sender, text, id, time = '', dateKey = null, dateLabel = '', attachment = null) {
    const welcomeMsg = document.querySelector('.welcome-message');
    if (welcomeMsg) welcomeMsg.remove();

    if (document.querySelector(`.message-bubble[data-id="${id}"]`)) return;

    if (dateKey) {
      appendDateSeparator(dateKey, dateLabel || formatDateLabel(new Date()));
    }

    const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
    const alignItem = sender === 'user' ? 'flex-end' : 'flex-start';
    const textAlign = sender === 'user' ? 'right' : 'left';

    // Support both single attachment and array of attachments
    const attachments = Array.isArray(attachment) ? attachment.filter(a => a) : (attachment ? [attachment] : []);
    const hasImages = attachments.some(a => a && a.is_image && a.url);
    const hasText = !!(text && String(text).trim().length);

    const messageDiv = document.createElement('div');
    messageDiv.className = `message-bubble ${messageClass}` + (hasImages ? ' has-image' : '');
    messageDiv.dataset.id = id;

    if (hasImages) {
      // Create image gallery container
      const galleryContainer = document.createElement('div');
      galleryContainer.className = 'chat-image-gallery';
      galleryContainer.style.cssText = `
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: ${hasText ? '8px' : '0'};
      `;

      attachments.forEach(att => {
        if (att && att.is_image && att.url) {
          const imgWrapper = document.createElement('div');
          imgWrapper.style.cssText = `
            flex: 0 0 calc(50% - 2px);
            min-height: 100px;
            max-height: 150px;
            border-radius: 8px;
            overflow: hidden;
            background: #f0f0f0;
          `;

          const img = document.createElement('img');
          img.className = 'chat-bubble-image';
          img.src = att.url;
          img.alt = att.original_name || 'image';
          img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; cursor: pointer;';
          img.addEventListener('click', () => openLightbox(att.url));

          imgWrapper.appendChild(img);
          galleryContainer.appendChild(imgWrapper);
        }
      });

      messageDiv.appendChild(galleryContainer);

      if (hasText) {
        const caption = document.createElement('div');
        caption.className = 'chat-bubble-caption';
        caption.textContent = text;
        messageDiv.appendChild(caption);
      }
    } else {
      messageDiv.innerHTML = text;
    }

    messageDiv.style.maxWidth = '100%';
    messageDiv.style.padding = hasImages ? '6px' : '10px 14px';
    messageDiv.style.borderRadius = '12px';
    messageDiv.style.fontSize = '14px';
    messageDiv.style.lineHeight = '1.4';
    messageDiv.style.position = 'relative';
    messageDiv.style.boxShadow = '0 1px 2px rgba(0,0,0,0.1)';
    messageDiv.style.wordWrap = 'break-word';

    if (sender === 'user') {
      if (!hasImages) {
        messageDiv.style.background = '#007bff';
        messageDiv.style.color = '#fff';
      }
      messageDiv.style.borderBottomRightRadius = '2px';
    } else {
      if (!hasImages) {
        messageDiv.style.background = '#f1f3f5';
        messageDiv.style.color = '#333';
        messageDiv.style.border = '1px solid #dee2e6';
      }
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
        background: #ff6565 !important;
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
        background-color: #ff6565 !important;
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
