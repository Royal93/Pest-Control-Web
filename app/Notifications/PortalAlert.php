<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * One in-app notification for the bell on the admin portal or SP SALES.
 * Stored in the notifications table only (no email). The url is kept as a
 * path (e.g. /sales/leads/12/edit) so it works on any domain.
 */
class PortalAlert extends Notification
{
    public function __construct(
        public string $kind,
        public string $title,
        public string $body,
        public string $url,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => $this->kind,
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
        ];
    }
}
