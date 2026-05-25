<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MetalSyncAlert extends Notification
{
    use Queueable;

    public function __construct(protected string $title, protected array $payload = [])
    {
    }

    public function via($notifiable)
    {
        return ['log'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'details' => $this->payload,
        ];
    }
}
