<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{

    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Lưu vào database để hiển thị trên web
        // Bạn có thể thêm 'mail' vào mảng này nếu muốn gửi cả email cho admin
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id'      => $this->order->id,
            'customer_name' => $this->order->first_name.' '.$this->order->last_name,
            'total_price'   => $this->order->total_price,
            'message'       => 'New order #'.$this->order->id.' has been placed.',
            'link'          => route('admin.orders.show', $this->order->id), // Link đến chi tiết đơn hàng
        ];
    }

}
