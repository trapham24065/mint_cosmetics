Figma sample import (Mint Cosmetics)

Files trong thư mục này:
- `COMPREHENSIVE_STYLE_GUIDE.html` — **Full UI Style Guide** (4 phần chính: Design Foundations, Components, Layout, Interaction) — **<-- MỞ FILE NÀY ĐỦ!**
- `index.html` — Style guide cơ bản (Colors, Typography, Summary)
- `components.html` — Collection UI components (buttons, forms, table, navbar, cards)
- `logo.svg` — Brand logo
- `GUIDE_HTML_TO_FIGMA.md` — Step-by-step hướng dẫn import vào Figma

**→ See detailed guide: [GUIDE_HTML_TO_FIGMA.md](GUIDE_HTML_TO_FIGMA.md)**

Quick summary:
1. Install plugins "html.to.design" and "Figma Tokens" in Figma.
2. Copy HTML from `components.html` → paste into html.to.design plugin → **Convert**.
3. Plugin generates Figma layers automatically.
4. Import `figma-tokens.json` using Figma Tokens plugin to get colors/typography.
5. Refine layers, create components, customize as needed.

Notes & alternatives:
- If you prefer direct manual creation in Figma, you can instead create colors, text styles, and components in Figma and paste values from `design/figma-tokens.json`.
- Some HTML-to-Figma plugins have limits; if the conversion isn't perfect, use the generated layers as a starting point and refine in Figma.

If you want, tôi sẽ:
- Tạo nhiều component HTML hơn (buttons, inputs, cards, icons) để plugin chuyển thành components sẵn dùng.
 - Tạo nhiều component HTML hơn (buttons, inputs, cards, icons) để plugin chuyển thành components sẵn dùng.
- Hoặc: xuất một file .sketch/.fig (requires Figma API / account) nếu bạn muốn tôi thử tạo trực tiếp trong Figma (cần token và quyền truy cập).

Bạn muốn tôi tiếp theo làm gì?