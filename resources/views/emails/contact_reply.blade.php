<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Phản hồi từ {{ $siteName }}</title>
</head>

<body style="font-family:Arial,Helvetica,sans-serif;color:#222;">
    <div style="max-width:680px;margin:0 auto;padding:20px;">
        <h2 style="margin-top:0">Cảm ơn bạn đã liên hệ với chúng tôi</h2>

        <p>Chào {{ $contactMessage->first_name }},</p>

        <p>Chúng tôi đã nhận được tin nhắn của bạn gửi lúc <strong>{{ $contactMessage->created_at->format('d/m/Y H:i') }}</strong>.</p>

        <p><strong>Đây là phản hồi của chúng tôi:</strong></p>

        <div style="border:1px solid #efefef;background:#fafafa;padding:12px;border-radius:6px;">
            {!! nl2br(e($replyMessage)) !!}
        </div>

        <p style="margin-top:18px">Nếu bạn có thêm câu hỏi, vui lòng trả lời lại email này hoặc liên hệ với chúng tôi qua:</p>
        <ul>
            <li>Email: {{ setting('contact_email', 'contact@example.com') }}</li>
            <li>Điện thoại: {{ setting('contact_phone', '') }}</li>
        </ul>

        <p>Trân trọng,<br>{{ $siteName }}</p>
    </div>
</body>

</html>