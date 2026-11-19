<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Facades\Cache;

class BrokerService
{
    public function all()
    {
        return Cache::rememberForever('brokers', fn() => Broker::all());
    }
}
