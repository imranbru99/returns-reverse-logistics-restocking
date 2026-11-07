<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class ContactSubmissionReceived extends Notification
{
    use Queueable;

    public function __construct(public readonly ContactSubmission $submission) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return config('returns.demo_mode')
            ? ['sandbox-mail']
            : ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contact: '.$this->submission->subject)
            ->line('From: '.$this->submission->name.' <'.$this->submission->email.'>')
            ->line($this->submission->message);
    }
}
