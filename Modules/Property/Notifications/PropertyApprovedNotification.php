<?php

namespace Modules\Property\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Property\Models\Property;

final class PropertyApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Property $property)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your property “:name” was approved', ['name' => $this->property->name]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line($this->message())
            ->action(__('Go to my properties'), route('owner.properties.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'property_id' => $this->property->id,
            'message' => $this->message(),
            'url' => route('owner.properties.index'),
        ];
    }

    private function message(): string
    {
        return __('“:name” was approved. You can publish it now.', ['name' => $this->property->name]);
    }
}
