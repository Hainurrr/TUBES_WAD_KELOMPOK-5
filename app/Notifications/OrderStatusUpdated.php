<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id_order,
            'tenant_name' => $this->order->tenant->name,
            'status' => $this->order->status,
            'message' => 'Pesanan di ' . $this->order->tenant->name . ' status: ' . $this->order->status,
        ];
    }
}
