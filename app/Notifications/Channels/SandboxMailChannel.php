<?php

declare(strict_types=1);

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

final class SandboxMailChannel
{
    /**
     * @param  mixed  $notifiable
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toMail')) {
            return;
        }

        $message = $notification->toMail($notifiable);

        Log::channel('single')->info('demo.mail.sandbox', [
            'to' => $notifiable->routeNotificationForMail($notification),
            'subject' => $message->subject ?? null,
            'lines' => $message->introLines ?? [],
        ]);
    }
}
