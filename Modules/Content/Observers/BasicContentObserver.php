<?php

namespace Modules\Content\Observers;

use Modules\Content\Models\BasicContent;
use Illuminate\Support\Facades\Cache;

class BasicContentObserver
{
    /**
     * Handle the BasicContent "created" event.
     */
    public function created(BasicContent $basicContent): void
    {
        Cache::forget('home_video');
    }

    /**
     * Handle the BasicContent "updated" event.
     */
    public function updated(BasicContent $basicContent): void
    {
        Cache::forget('home_video');
    }

    /**
     * Handle the BasicContent "deleted" event.
     */
    public function deleted(BasicContent $basicContent): void
    {
        Cache::forget('home_video');
    }

    /**
     * Handle the BasicContent "restored" event.
     */
    public function restored(BasicContent $basicContent): void
    {
        Cache::forget('home_video');
    }

    /**
     * Handle the BasicContent "force deleted" event.
     */
    public function forceDeleted(BasicContent $basicContent): void
    {
        Cache::forget('home_video');
    }
}
