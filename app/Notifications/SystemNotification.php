<?php

namespace App\Notifications;

use App\Events\NewNotificationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public string $title;

    public string $message;

    public string $type;

    public string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $type = 'info', string $url = '#')
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     * Only database — broadcast is handled via NewNotificationEvent.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for the database.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // Fire the real-time broadcast event directly (bypasses queue)
        try {
            event(new NewNotificationEvent(
                $notifiable->id,
                $this->title,
                $this->message,
                $this->type,
                $this->url
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Notification broadcast failed: '.$e->getMessage());
        }

        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url,
        ];
    }
}
