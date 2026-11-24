<?php

namespace App\Notifications;

use App\Services\VerificationCodes;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class EmailVerificationCodeNotification extends Notification
{
    public function __construct(private readonly string $code)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your Qeima verification code: :code', ['code' => $this->code]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__('Enter this code on your profile to verify your email address:'))
            ->line("**{$this->code}**")
            ->line(__('The code expires in :minutes minutes. If you did not ask for it, you can ignore this email.', [
                'minutes' => VerificationCodes::TTL_SECONDS / 60,
            ]));
    }
}
