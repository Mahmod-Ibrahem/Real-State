<?php

namespace Modules\Lead\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Lead\Models\PropertyLead;

final class PropertyLeadReceived
{
    use Dispatchable, SerializesModels;

    public function __construct(public PropertyLead $lead)
    {
    }
}
