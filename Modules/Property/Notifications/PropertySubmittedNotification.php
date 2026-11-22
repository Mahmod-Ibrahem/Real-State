<?php

namespace Modules\Property\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Property\Models\Property;

final class PropertySubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Property $property)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function viaConnections(): array
    {
        return ['mail' => config('queue.default'), 'database' => 'sync'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New property to review: :name', ['name' => $this->property->name]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line($this->message())
            ->action(__('Review property'), $this->url())
            ->line(__('It stays hidden from the website until it is approved.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'property_id' => $this->property->id,
            'message' => $this->message(),
            'url' => $this->url(),
        ];
    }

    private function message(): string
    {
        return __(':owner submitted “:property” for review.', [
            'owner' => $this->property->owner->name,
            'property' => $this->property->name,
        ]);
    }

    private function url(): string
    {
        return route('admin.properties.edit', $this->property);
    }
}
