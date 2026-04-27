<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminEmailChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $oldEmail,
        protected string $newEmail
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Cảnh báo: Có yêu cầu thay đổi email tài khoản quản trị')
            ->line('Chúng tôi nhận được yêu cầu thay đổi địa chỉ email cho tài khoản quản trị của bạn.')
            ->line('Email hiện tại: ' . $this->oldEmail)
            ->line('Email mới (chờ xác nhận): ' . $this->newEmail)
            ->line('Yêu cầu này chỉ có hiệu lực sau khi được xác nhận tại địa chỉ email mới. Nếu bạn không thực hiện yêu cầu này, vui lòng đăng nhập và huỷ yêu cầu, đồng thời đổi mật khẩu tài khoản ngay lập tức.');
    }

    /**
     * Override the recipient so this notification is delivered to the OLD email.
     */
    public function routeNotificationForMail(object $notifiable): string
    {
        return $this->oldEmail;
    }
}
