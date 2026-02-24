# 🎨 Chat Widget - UI States & Components

## 📋 Using this code:
1. Go to **https://mermaid.live**
2. Paste the code below
3. Download as PNG/SVG
4. Or use in draw.io by converting via **https://mermaid.ink/**

---

```mermaid
graph TD
    subgraph WidgetStates["🎨 CHAT WIDGET STATES"]
        State1["🔴 STATE 1: CLOSED<br/>━━━━━━━<br/>- Chat icon floating<br/>- Badge: unread count<br/>- Tooltip: 'Chat with us'"]
        
        State2["🟢 STATE 2: OPENING<br/>━━━━━━━<br/>- Animation: zoom in<br/>- Loading spinner"]
        
        State3["🟠 STATE 3: OPEN - NEW CHAT<br/>━━━━━━━<br/>- Header: Greeting<br/>- Body: Suggestions<br/>- Footer: Input box"]
        
        State4["🟠 STATE 4: OPEN - CHATTING<br/>━━━━━━━<br/>- Header: Close btn<br/>- Body: Message history<br/>- Footer: Input box"]
        
        State5["🟡 STATE 5: WAITING FOR ADMIN<br/>━━━━━━━<br/>- Typing indicator<br/>- 'Admin is typing...'<br/>- Disable input"]
        
        State6["⚫ STATE 6: TIMEOUT<br/>━━━━━━━<br/>- Default message<br/>- FAQ suggestions<br/>- 'Try these'"]
    end
    
    subgraph WidgetLayout["📐 WIDGET LAYOUT STRUCTURE"]
        Layout["
┌─────────────────────┐
│  Header             │  (Close X | Title)
├─────────────────────┤
│                     │
│  Chat Messages      │  (scrollable)
│  Body               │
│                     │
├─────────────────────┤
│  Input Box          │  (text input + send btn)
│  Footer             │
└─────────────────────┘
        "]
    end
    
    subgraph MessageTypes["💬 MESSAGE BUBBLE TYPES"]
        UserMsg["👤 USER MESSAGE<br/>━━━━━━━<br/>- Align: RIGHT<br/>- Color: Blue/Primary<br/>- Border: Rounded<br/>- Time: Show below"]
        
        BotMsg["🤖 BOT MESSAGE<br/>━━━━━━━<br/>- Align: LEFT<br/>- Color: Gray/Secondary<br/>- Border: Rounded<br/>- Show: 'BOT' label"]
        
        AdminMsg["👨‍💼 ADMIN MESSAGE<br/>━━━━━━━<br/>- Align: LEFT<br/>- Color: Orange/Warning<br/>- Avatar: Admin icon<br/>- Show: 'ADMIN' label"]
        
        SystemMsg["ℹ️ SYSTEM MESSAGE<br/>━━━━━━━<br/>- Align: CENTER<br/>- Color: Gray/Subtle<br/>- Text: Small<br/>- Example: 'Chat started'"]
    end
    
    subgraph InteractionElements["🖱️ INTERACTIVE ELEMENTS"]
        SuggestBtn["💡 Quick Hint Button<br/>━━━━━━━<br/>- Style: Outline<br/>- Click: Send + Auto-reply<br/>- Hover: Highlight"]
        
        InputBox["⌨️ Message Input<br/>━━━━━━━<br/>- Placeholder: 'Type...'<br/>- Max length: 1000<br/>- Enter to send"]
        
        SendBtn["📤 Send Button<br/>━━━━━━━<br/>- Icon: Arrow/Send<br/>- Disabled: when empty<br/>- Loading: show spinner"]
        
        TypingIndicator["✍️ Typing Indicator<br/>━━━━━━━<br/>- Animation: dots<br/>- Text: 'Admin is typing...'"]
    end
    
    State1 --> State2
    State2 --> State3
    State3 --> State4
    State4 --> State5
    State5 --> State6
    State6 -->|User reply| State4
    
    State3 -->|User skip| State4
    
    SuggestBtn -.->|On click| SendMsg["📤 Send message<br/>with is_quick_hint=true"]
    InputBox -.->|On enter| SendMsg
    SendBtn -.->|On click| SendMsg
    
    TypingIndicator -.->|Show during| State5
    
    style State1 fill:#ffebee
    style State2 fill:#fff3e0
    style State3 fill:#e8f5e9
    style State4 fill:#e8f5e9
    style State5 fill:#fff9c4
    style State6 fill:#f3e5f5
    
    style UserMsg fill:#bbdefb,color:#000
    style BotMsg fill:#e0e0e0,color:#000
    style AdminMsg fill:#ffe0b2,color:#000
    style SystemMsg fill:#f5f5f5,color:#666
```

---

## 🎯 Widget States Explanation

### State 1: CLOSED 🔴
- Chat icon floating in bottom-right corner
- Badge showing unread message count
- Tooltip: "Chat with us" on hover
- **Click action**: Open to State 2

### State 2: OPENING 🟢
- Fade-in animation
- Loading spinner shown
- Brief duration (100-300ms)
- **Transition**: Goes to State 3

### State 3: OPEN - NEW CHAT 🟠
- Header: Greeting + chatbot intro
- Body: Quick Hints suggestions
- Footer: Message input box
- **User action**: 
  - Click suggestion → Send message
  - Skip suggestions → Type message
  - Both → Go to State 4

### State 4: OPEN - CHATTING 🟠
- Header: Close (X) button
- Body: Message history (scrollable)
- Footer: Message input + send button
- **User action**:
  - Type & send → Show message
  - Waiting for reply → Go to State 5 if no auto-reply

### State 5: WAITING FOR ADMIN 🟡
- Typing indicator animation (bouncing dots)
- Text: "Admin is typing..."
- Input box disabled (grayed out)
- **Timeout after 30s**: Go to State 6

### State 6: TIMEOUT ⚫
- Show default message: "Sorry, admin is busy now..."
- Show FAQ suggestions
- Allow user to:
  - Select FAQ → Back to State 4
  - Close chat
  - Continue waiting (polling)

---

## 📱 Widget Layout Details

```
┌─────────────────────────────────────┐
│ 🔤 Mint Cosmetics Support          ✕│  ← Header
├─────────────────────────────────────┤
│                                     │
│ 👋 Hi! How can we help you?         │  ← Message body
│                                     │  (scrollable)
│ 💬 [💅 See our FAQs] [?❓ Shipping]  │
│    [🛍️ Orders] [📦 Returns]          │
│                                     │
│                                     │
│ ┌─────────────────────────────────┐ │
│ │ Type your message...            │ │  ← Input footer
│ └─────────────────────────────────┘ │
│                              📤      │
└─────────────────────────────────────┘
  Width: ~350px
  Height: ~500px
  Position: Bottom-right
  Z-index: 9999
```

---

## 💬 Message Bubble Styling

### User Message
```
┌──────────────────────────┐
│ I want to return my order│
└──────────────────────────┘
           14:32
```
- Align: RIGHT
- BG Color: #2196F3 (Blue)
- Text Color: White
- Border radius: 12px
- Time: Below message

### Bot Message
```
   🤖 BOT
┌──────────────────────────┐
│ Sure! Let me help you... │
└──────────────────────────┘
```
- Align: LEFT
- BG Color: #E0E0E0 (Gray)
- Text Color: #333
- Show label: "🤖 BOT"

### Admin Message
```
  👨‍💼 ADMIN
┌──────────────────────────┐
│ Thanks for contacting us!│
└──────────────────────────┘
```
- Align: LEFT
- BG Color: #FFB74D (Orange)
- Avatar icon: Show
- Show label: "👨‍💼 ADMIN"

---

## ⚙️ React/Vue Component Structure

```jsx
<ChatWidget>
  ├── ChatIcon (State 1)
  │   └── Badge (unread count)
  │
  └── ChatBox (State 2-6)
      ├── Header
      │   ├── Title
      │   └── Close button
      │
      ├── MessageArea (scrollable)
      │   ├── SystemMessage
      │   ├── UserMessage (bubbles)
      │   ├── BotMessage (bubbles)
      │   ├── AdminMessage (bubbles)
      │   └── TypingIndicator
      │
      ├── QuickSuggestions (State 3 only)
      │   └── SuggestionButtons[]
      │
      └── InputFooter
          ├── Input field
          └── Send button
```

---

## 🎨 CSS Color Palette

| Element | Color Code | Hex | Usage |
|---------|-----------|-----|-------|
| User Message | Primary | #2196F3 | User bubbles |
| Bot Message | Secondary | #E0E0E0 | Bot bubbles |
| Admin Message | Warning | #FFB74D | Admin bubbles |
| System Message | Subtle | #F5F5F5 | System info |
| Button Hover | Accent | #1976D2 | Interactive |
| Error | Error | #F44336 | Errors/timeouts |
| Success | Success | #4CAF50 | Success states |

