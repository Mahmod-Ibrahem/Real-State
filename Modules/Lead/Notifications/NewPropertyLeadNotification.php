<?php

namespace Modules\Lead\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Lead\Enums\LeadSource;
use Modules\Lead\Models\PropertyLead;

final class NewPropertyLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly PropertyLead $lead, private readonly string $url)
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
            ->subject(__('New lead for ":property"', ['property' => $this->lead->property->name]))
            ->greeting(__('Hello :name,', ['name' => $notifiable->name]))
            ->line($this->message())
            ->line(__('Phone: :phone', ['phone' => $this->lead->phone]))
            ->when($this->lead->email, fn (MailMessage $mail) => $mail->line(__('Email: :email', ['email' => $this->lead->email])))
            ->when($this->lead->message, fn (MailMessage $mail) => $mail->line('“' . $this->lead->message . '”'))
            ->action(__('Open leads'), $this->url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'property_lead_id' => $this->lead->id,
            'message' => $this->message(),
            'url' => $this->url,
        ];
    }

    private function message(): string
    {
        $replace = ['name' => $this->lead->full_name, 'property' => $this->lead->property->name];

        return match ($this->lead->source) {
            LeadSource::Form => __('New enquiry from :name about ":property".', $replace),
            LeadSource::Call => __(':name wants to call about ":property".', $replace),
            LeadSource::WhatsApp => __(':name wants to chat on WhatsApp about ":property".', $replace),
        };
    }
}
