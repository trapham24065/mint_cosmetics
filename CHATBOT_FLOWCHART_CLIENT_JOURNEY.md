# 🎯 Client-Side Chat Widget - User Journey Flowchart

## 📋 Using this code:
1. **In Mermaid Live Editor**: Go to https://mermaid.live → Paste this code
2. **In draw.io**: File → Import → Paste mermaid code or convert via https://mermaid.ink/
3. **GitHub**: Paste in markdown files
4. **In your project**: Use mermaid-cli to convert to PNG/SVG

---

```mermaid
graph TD
    Start([👤 User vào website]) --> CheckWidget{Chat widget<br/>có xuất hiện?}
    
    CheckWidget -->|Có| WidgetShow["📱 Hiển thị Chat Widget<br/>- Icon + Badge<br/>- 'Start conversation'"]
    CheckWidget -->|Không| LoadWidget["⏳ Load widget<br/>từ JavaScript"]
    LoadWidget --> WidgetShow
    
    WidgetShow --> UserAction{User tương tác<br/>thế nào?}
    
    %% ===== PATH 1: MỞ CHAT BOX =====
    UserAction -->|Nhấn chat icon| OpenChat["🔓 Mở Chat Box"]
    
    OpenChat --> FirstTime{Lần đầu<br/>chat?}
    
    FirstTime -->|CÓ| Greeting["👋 Hiển thị:<br/>- Greeting message<br/>- Intro about bot"]
    FirstTime -->|KHÔNG| History["📋 Hiển thị lịch sử<br/>tin nhắn cũ"]
    
    Greeting --> ShowSuggestions["💡 Hiển thị Quick Hints<br/>- Danh sách suggestions<br/>từ API<br/>- 'What can I help?'"]
    History --> ShowSuggestions
    
    %% ===== PATH 2: CHỌN QUICK HINTS =====
    UserAction -->|Chọn Quick Hint| SelectQuickHint["👆 User click<br/>vào 1 suggestion"]
    ShowSuggestions -->|User chọn| SelectQuickHint
    
    SelectQuickHint --> SendQuickHint["📤 Gửi Quick Hint<br/>dưới dạng message<br/>to API /chat/send<br/>with is_quick_hint=true"]
    
    SendQuickHint --> ShowUserMsg["💬 Hiển thị message<br/>của user<br/>align: right<br/>color: blue"]
    
    ShowUserMsg --> WaitBotReply["⏳ Chờ reply<br/>từ bot"]
    
    WaitBotReply --> BotCheck{Bot có<br/>tìm thấy<br/>reply?}
    
    BotCheck -->|CÓ| BotReply["🤖 Hiển thị bot reply<br/>- Auto-reply text<br/>- align: left<br/>- color: gray"]
    BotCheck -->|KHÔNG| AdminWait["⏳ Chờ admin<br/>trả lời<br/>Polling..."]
    
    BotReply --> NextAction1{User tiếp<br/>tục?}
    NextAction1 -->|CÓ| ShowSuggestions
    NextAction1 -->|KHÔNG| End1(["✨ Kết thúc"])
    
    %% ===== PATH 3: NHẬP TIN NHẮN THƯỜNG =====
    UserAction -->|Nhập tin nhắn| TypeMsg["⌨️ User nhập<br/>tin nhắn<br/>vào input box"]
    ShowSuggestions -->|User skip suggestions| TypeMsg
    
    TypeMsg --> SendMsg["📤 Gửi tin nhắn<br/>to API /chat/send<br/>with is_quick_hint=false"]
    
    SendMsg --> ShowUserMsg
    
    ShowUserMsg --> AdminWait
    
    AdminWait --> Polling["🔄 Frontend polling<br/>/chat/fetch<br/>mỗi 2-3 giây"]
    
    Polling --> HasNewMsg{Có message<br/>mới từ<br/>admin?}
    
    HasNewMsg -->|CÓ| AdminReply["💬 Hiển thị tin nhắn<br/>từ admin<br/>align: left<br/>color: orange"]
    
    HasNewMsg -->|KHÔNG| ShowTimeout{Timeout<br/>mà chưa có<br/>reply?<br/>30 giây"}
    
    AdminReply --> AdminMsgEnd{User tiếp<br/>tục chat?}
    AdminMsgEnd -->|CÓ| TypeMsg
    AdminMsgEnd -->|KHÔNG| End2(["✨ Kết thúc"])
    
    %% ===== TIMEOUT HANDLING =====
    ShowTimeout -->|CÓ| SendDefault["💬 Gửi tin nhắn mặc định<br/>'Sorry, admin is busy<br/>now. Please check<br/>FAQ below!'"]
    
    SendDefault --> ShowDefaultMsg["📨 Hiển thị default<br/>message trong<br/>chat box"]
    
    ShowDefaultMsg --> ShowFAQ["❓ Hiển thị:<br/>- FAQ suggestions<br/>- 'Try these questions'"]
    
    ShowFAQ --> TimeoutEnd{User muốn<br/>làm gì?}
    
    TimeoutEnd -->|Chọn FAQ| SelectQuickHint
    TimeoutEnd -->|Đóng chat| End3(["✨ Kết thúc"])
    TimeoutEnd -->|Chờ thêm| Polling
    
    ShowTimeout -->|KHÔNG| Polling
    
    %% ===== PATH 4: ĐÓNG CHAT BOX =====
    UserAction -->|Đóng chat| CloseChat["🔒 Đóng Chat Box<br/>- Lưu unread message count<br/>- Badge notification"]
    
    CloseChat --> StayPage["📄 User ở lại<br/>trang web"]
    
    StayPage --> UserContinue{User làm gì<br/>tiếp?}
    
    UserContinue -->|Mua hàng| Shopping["🛍️ Tiếp tục mua sắm"]
    UserContinue -->|Mở lại chat| WidgetShow
    UserContinue -->|Rời khỏi| Leave["👋 Rời website"]
    
    Shopping --> WidgetShow
    
    %% ===== PAGE REFRESH / EDGE CASES =====
    WidgetShow --> EdgeCase{Edge case?}
    
    EdgeCase -->|Refresh page| RefreshMsg["🔄 Refresh<br/>- Reload chat widget<br/>- Fetch new messages<br/>- Preserve session"]
    
    RefreshMsg --> ShowSuggestions
    
    EdgeCase -->|Đóng tab| TabClose["❌ Close tab<br/>- Session lưu<br/>vào database"]
    
    EdgeCase -->|Offline| Offline["📡 No internet<br/>- Show error msg<br/>- Queue messages"]
    
    Offline --> BackOnline{Kết nối<br/>lại?}
    BackOnline -->|CÓ| SendQueue["📤 Gửi queued<br/>messages"]
    SendQueue --> ShowSuggestions
    
    style Start fill:#e1f5ff,stroke:#0288d1,stroke-width:2px
    style End1 fill:#c8e6c9,stroke:#388e3c,stroke-width:2px
    style End2 fill:#c8e6c9,stroke:#388e3c,stroke-width:2px
    style End3 fill:#c8e6c9,stroke:#388e3c,stroke-width:2px
    style Leave fill:#c8e6c9,stroke:#388e3c,stroke-width:2px
    
    style WidgetShow fill:#e3f2fd,stroke:#1976d2
    style OpenChat fill:#e3f2fd,stroke:#1976d2
    style ShowSuggestions fill:#fff9c4,stroke:#f57f17
    style BotReply fill:#ffe0b2,stroke:#f57c00
    style AdminReply fill:#f8bbd0,stroke:#c2185b
    style SendMsg fill:#e0f2f1,stroke:#00796b
    style AdminWait fill:#f8bbd0,stroke:#c2185b
    style SendDefault fill:#ffecb3,stroke:#fbc02d
    style Polling fill:#e8f5e9,stroke:#558b2f
```

---

## 📝 Legends

| Color | Meaning |
|-------|---------|
| 🔵 Light Blue | Widget states / User interactions |
| 🟡 Yellow | Suggestions / Loading states |
| 🟠 Orange | Bot auto-reply |
| 🔴 Red/Pink | Admin reply / Waiting |
| 🟢 Green | Completion / Success |

---

## 🔗 Main API Endpoints Used

- `GET /chat/suggestions` - Get quick hint suggestions
- `POST /chat/send` - Send message (user or quick hint)
- `GET /chat/fetch` - Fetch new messages (polling)
- `POST /chat/default-message` - Send default timeout message
