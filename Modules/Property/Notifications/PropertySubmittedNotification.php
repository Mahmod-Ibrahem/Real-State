<?php

namespace Modules\Property\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Property\Models\Property;

final class PropertySubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Property $property)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New property to review: :name', ['name' => $this->property->name]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line(__(':owner submitted “:property” for review.', [
                'owner' => $this->property->owner->name,
                'property' => $this->property->name,
            ]))
            ->action(__('Review property'), route('admin.properties.edit', $this->property))
            ->line(__('It stays hidden from the website until it is approved.'));
    }
}
