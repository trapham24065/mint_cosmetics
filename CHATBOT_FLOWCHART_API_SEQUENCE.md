# 🌐 Frontend API Calls - Sequence Diagram

## 📋 Using this code:

### Option 1: View as Sequence Diagram (Recommended)
1. Go to **https://mermaid.live**
2. Select **Diagram Type**: `Sequence`
3. Paste the code below

### Option 2: Convert to draw.io
1. Go to **https://mermaid.ink/svg/[encoded-mermaid]**
2. Copy the SVG
3. Import in draw.io

### Option 3: Use mermaid-cli
```bash
npm install -g mermaid-cli
mmdc -i diagram.mmd -o diagram.png
```

---

```mermaid
sequenceDiagram
    participant User as 👤 User/Browser
    participant Widget as 🎨 Chat Widget<br/>JavaScript
    participant API as 🌐 Backend API
    participant DB as 💾 Database
    
    Note over User,DB: 1️⃣ USER MỞ WEBSITE
    User->>Widget: Load page
    Widget->>API: GET /chat/suggestions
    API-->DB: Query active ChatbotRule
    DB-->>API: Return keywords list
    API-->>Widget: [suggestion1, suggestion2, ...]
    Widget->>User: Render Chat Icon + Suggestions Cache
    
    Note over User,DB: 2️⃣ USER CHỌN QUICK HINT
    User->>Widget: Click Quick Hint
    Widget->>API: POST /chat/send<br/>{message, is_quick_hint: true}
    API-->DB: Create/Get Conversation<br/>Create Message (user)
    API-->DB: Find ChatbotRule by keyword<br/>Get reply
    API-->DB: Create Message (bot)<br/>sender='bot'
    DB-->>API: Saved messages
    API-->>Widget: {<br/>success: true,<br/>message: {id, body},<br/>bot_reply: {id, body}<br/>}
    Widget->>User: Show user msg (right, blue)<br/>Show bot reply (left, gray)
    
    Note over User,DB: 3️⃣ USER NHẬP & GỬI TIN NHẮN
    User->>Widget: Type message & press Enter
    Widget->>API: POST /chat/send<br/>{message, is_quick_hint: false}
    API-->DB: Create/Get Conversation<br/>Create Message (user)
    API-->DB: Create Message (admin)<br/>sender='admin'<br/>DEFAULT: "Sorry, admin busy"
    DB-->>API: Messages saved
    API-->>Widget: {<br/>success: true,<br/>message: {id, body}<br/>}
    Widget->>User: Show user msg (right, blue)<br/>Show 'Waiting for admin...'
    
    Note over User,DB: 4️⃣ POLLING - CHỜ ADMIN REPLY
    Widget->>API: GET /chat/fetch<br/>?last_id=X
    API-->DB: Query messages<br/>where id > last_id
    DB-->>API: Return new messages
    loop Polling (every 2-3 sec)
        Widget->>API: GET /chat/fetch
        API-->>Widget: Messages or []
        Widget->>User: Update if new messages
    end
    
    Note over User,DB: 5️⃣ ADMIN TRÌNH REPLY
    rect rgb(200, 100, 100)
    Note right of API: Admin Dashboard<br/>sends reply
    API->>API: POST /admin/chat/{id}/reply
    API-->DB: Create Message (admin)
    DB-->>API: Saved
    end
    
    Note over User,DB: 6️⃣ FRONTEND NHẬN ADMIN REPLY
    Widget->>API: GET /chat/fetch<br/>?last_id=X
    API-->DB: Find new messages
    DB-->>API: Return admin message
    API-->>Widget: {messages: [{..., body: "reply"}]}
    Widget->>User: Show admin msg (left, orange)<br/>Stop polling
    
    Note over User,DB: 7️⃣ TIMEOUT - DEFAULT MESSAGE
    rect rgb(255, 200, 100)
    Note right of Widget: After 30s no admin reply
    Widget->>API: POST /chat/default-message
    API-->DB: Create Message (system)
    DB-->>API: Saved
    API-->>Widget: {message: "Sorry admin busy..."}
    Widget->>User: Show default msg<br/>Show FAQ suggestions
    end
    
    Note over User,DB: 8️⃣ PAGE REFRESH/RECONNECT
    User->>Widget: Refresh page
    Widget->>API: GET /chat/fetch<br/>?last_id=0
    API-->DB: Get all messages
    DB-->>API: Return history
    API-->>Widget: Message history
    Widget->>User: Render all old messages
    
    style API fill:#4CAF50,color:#fff
    style DB fill:#2196F3,color:#fff
    style Widget fill:#FF9800,color:#fff
    style User fill:#9C27B0,color:#fff
```

---

## 📝 API Endpoints Reference

### 1️⃣ `GET /chat/suggestions`
**Purpose**: Load initial quick hint suggestions when page loads

**Request**:
```javascript
fetch('/chat/suggestions')
```

**Response**:
```json
{
  "success": true,
  "suggestions": [
    "💅 See our FAQs",
    "?❓ Shipping Info",
    "🛍️ How to Order",
    "📦 Returns & Refunds"
  ]
}
```

---

### 2️⃣ `POST /chat/send`
**Purpose**: Send user message or quick hint

**Request**:
```javascript
fetch('/chat/send', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    message: "What's the shipping cost?",
    is_quick_hint: false  // or true for suggestions
  })
})
```

**Response** (if quick hint):
```json
{
  "success": true,
  "message": {
    "id": 123,
    "body": "What's the shipping cost?",
    "created_at": "14:32"
  },
  "bot_reply": {
    "id": 124,
    "body": "Shipping is FREE for orders over $30!",
    "created_at": "14:32"
  }
}
```

**Response** (if regular message):
```json
{
  "success": true,
  "message": {
    "id": 125,
    "body": "Can I use multiple coupons?",
    "created_at": "14:35"
  },
  "bot_reply": null  // Wait for admin
}
```

---

### 3️⃣ `GET /chat/fetch`
**Purpose**: Poll for new messages from admin

**Request**:
```javascript
fetch('/chat/fetch?last_id=124', {
  headers: { 'Accept': 'application/json' }
})
```

**Response** (no new messages):
```json
{
  "messages": []
}
```

**Response** (new admin message):
```json
{
  "messages": [
    {
      "id": 126,
      "body": "No, sorry. Only one coupon per order.",
      "sender": "admin",
      "created_at": "14:37"
    }
  ]
}
```

---

### 4️⃣ `POST /chat/default-message`
**Purpose**: Send default timeout message after 30 seconds

**Request**:
```javascript
fetch('/chat/default-message', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' }
})
```

**Response**:
```json
{
  "success": true,
  "message": {
    "id": 127,
    "body": "Sorry, our admin is busy right now. Please try FAQ or wait a bit!",
    "sender": "system"
  }
}
```

---

## 🔄 Polling Strategy

### Frontend Polling Loop
```javascript
setInterval(async () => {
  const response = await fetch(`/chat/fetch?last_id=${lastMessageId}`);
  const data = await response.json();
  
  if (data.messages && data.messages.length > 0) {
    data.messages.forEach(msg => {
      displayMessage(msg);
      lastMessageId = msg.id;
    });
    
    // Stop polling if admin replied
    if (msg.sender === 'admin') {
      stopPolling();
    }
  }
}, 2000); // Poll every 2 seconds
```

### Timeout Logic
```javascript
const pollingTimeout = setTimeout(() => {
  // 30 seconds passed, send default message
  fetch('/chat/default-message', { method: 'POST' });
  
  // Show FAQ suggestions
  showFAQSuggestions();
}, 30000);
```

---

## 📊 API Call Timing

| Step | Duration | Action |
|------|----------|--------|
| 1 | Page load | GET /chat/suggestions |
| 2 | User click | POST /chat/send (quick hint) |
| 3 | Instant | Response with bot reply |
| 4 | 2-3 sec intervals | GET /chat/fetch (polling) |
| 5 | 30 sec max | POST /chat/default-message |
| 6 | Continuous | Keep polling until admin replies |

---

## 🛡️ Error Handling

### Network Error
```json
{
  "success": false,
  "message": "Network error. Your message will be sent when reconnected.",
  "queued": true
}
```

### Message Send Failed
```json
{
  "success": false,
  "message": "Failed to send. Please try again.",
  "error": "message_invalid"
}
```

### Database Error
```json
{
  "success": false,
  "message": "Server error. Please try again later.",
  "error": "server_error"
}
```

---

## 🔐 Security Considerations

- ✅ Validate `message` length (max 1000 chars)
- ✅ Rate limit on `/chat/send` (max 5 messages/minute)
- ✅ Validate `is_quick_hint` boolean
- ✅ Sanitize message content (XSS prevention)
- ✅ Use CSRF tokens for POST requests
- ✅ Authenticate user/guest with session

---

## 📈 Frontend Implementation Tips

1. **Cache suggestions** on page load → Avoid repeated API calls
2. **Debounce polling** → Stop when admin messages arrive
3. **Show loading states** → Spinner during message send
4. **Queue messages** → If user goes offline
5. **Preserve session** → Use localStorage for conversation ID
6. **Auto-scroll** → Keep chat at bottom when new messages arrive
7. **Unread badge** → Show count when chat is closed
8. **Typing indicator** → Show animation when waiting for admin

