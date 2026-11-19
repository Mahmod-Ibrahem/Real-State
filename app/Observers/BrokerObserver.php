<?php

namespace App\Observers;

use App\Models\Broker;
use Illuminate\Support\Facades\Cache;

class BrokerObserver
{
    /**
     * Handle the Broker "created" event.
     */
    public function created(Broker $broker): void
    {
        Cache::forget('brokers');
    }

    /**
     * Handle the Broker "updated" event.
     */
    public function updated(Broker $broker): void
    {
        Cache::forget('brokers');
    }

    /**
     * Handle the Broker "deleted" event.
     */
    public function deleted(Broker $broker): void
    {
        Cache::forget('brokers');

    }

    /**
     * Handle the Broker "restored" event.
     */
    public function restored(Broker $broker): void
    {
        Cache::forget('brokers');

    }

    /**
     * Handle the Broker "force deleted" event.
     */
    public function forceDeleted(Broker $broker): void
    {
        Cache::forget('brokers');
    }
}
