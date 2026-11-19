<?php

namespace Modules\Content\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Content\Models\Faq;

class FaqService
{
    /** The public FAQ list; FaqObserver forgets it whenever an FAQ changes. */
    public function all(): Collection
    {
        return Cache::rememberForever('faqs', fn () => Faq::orderBy('id')->get());
    }
}
