## Hướng dẫn import HTML vào Figma (html.to.design plugin)

### Bước 1: Mở plugin html.to.design trong Figma
1. Figma → **Resources** (icon định dạng) → **Plugins** 
2. Search "html.to.design"
3. Click **Run** (hoặc "Open")
4. Plugin panel sẽ hiện ở phía phải

### Bước 2: Copy HTML từ file components.html
1. Mở folder `design/figma-sample/` bằng VS Code (hoặc editor)
2. Mở file **components.html**
3. **Select All** (Ctrl+A)
4. **Copy** (Ctrl+C)

### Bước 3: Paste vào plugin
1. Quay lại Figma, panel html.to.design phía phải
2. Paste HTML vào text box (Ctrl+V)
3. Click **Convert** (hoặc button chính của plugin)
4. Chờ vài giây — plugin sẽ generate frames & layers trên canvas

### Bước 4: Xem kết quả
- Các button, forms, cards sẽ thành Figma layers/frames
- Có thể chỉnh màu, kích thước, font từ Figma panel phía phải
- Nếu layout không hoàn hảo, dùng làm reference rồi chỉnh tay

### Bước 5: Import Figma Tokens (màu + typography)
1. Figma → **Resources** → **Plugins** → Search "Figma Tokens" → **Run**
2. Plugin sẽ hiện, click **Import**
3. Dán nội dung file `design/figma-tokens.json`:
   ```json
   {
     "$schema": "https://schemas.figmatokens.com/2.0.0/tokens.json",
     "metadata": { ... },
     "colors": { ... },
     "typography": { ... }
   }
   ```
4. Click **Import** trong plugin
5. Màu & typography sẽ được add vào file Figma

### Bước 6 (Optional): Tạo Components trong Figma
1. Chọn layer (ví dụ nút Primary button)
2. Right-click → **Create component** (hoặc Cmd+K)
3. Rename component (ví dụ "Button/Primary")
4. Lặp lại cho các component khác (Secondary, Ghost, Forms, Cards, v.v.)
5. Có thể set variants (size: small/medium/large; state: default/hover/disabled)

### Lưu ý:
- Nếu plugin không parse đúng CSS, dùng kết quả Generated làm wireframe rồi tạo components thủ công nhanh hơn.
- Copy từng section HTML (buttons, forms, cards) riêng biệt nếu muốn control tốt hơn.
- Sau khi convert, có thể chia nhóm layers thành folders (Buttons, Forms, Cards) để clean hơn.

### File tham khảo:
- `design/figma-sample/index.html` — Style guide (màu, fonts, demo)  
- `design/figma-sample/components.html` — UI components (buttons, forms, navbar, table, cards)
- `design/figma-tokens.json` — Tokens (colors, typography)
- `design/figma-sample/logo.svg` — Logo

---

**Gặp vấn đề?**
- Plugin chậm / không convert → thử split HTML thành phần nhỏ (buttons riêng, forms riêng)
- CSS không apply đúng → plugin có giới hạn CSS; dùng style inline thay thế
- Cần help thêm → hỏi tôi
