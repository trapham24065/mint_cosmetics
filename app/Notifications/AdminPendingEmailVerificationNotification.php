<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPendingEmailVerificationNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $pendingEmail,
        protected string $token,
        protected int $hoursValid = 24
    ) {
    }

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
     * Override the recipient so this notification is delivered to the NEW pending email.
     */
    public function routeNotificationForMail(object $notifiable): string
    {
        return $this->pendingEmail;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('admin.email-change.confirm', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('Xác nhận thay đổi địa chỉ email quản trị')
            ->greeting('Xin chào ' . ($notifiable->name ?? '') . ',')
            ->line('Chúng tôi nhận được yêu cầu thay đổi địa chỉ email tài khoản quản trị sang: ' . $this->pendingEmail)
            ->line('Vui lòng nhấn nút bên dưới để xác nhận. Liên kết có hiệu lực trong ' . $this->hoursValid . ' giờ.')
            ->action('Xác nhận đổi email', $url)
            ->line('Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này — địa chỉ email cũ của bạn sẽ được giữ nguyên.');
    }
}
