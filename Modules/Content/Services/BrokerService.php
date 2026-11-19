<?php

namespace Modules\Content\Services;

use Modules\Content\Models\Broker;
use Illuminate\Support\Facades\Cache;

class BrokerService
{
    public function all()
    {
        return Cache::rememberForever('brokers', fn() => Broker::all());
    }
}
