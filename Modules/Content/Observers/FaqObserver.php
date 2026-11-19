<?php

namespace Modules\Content\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Content\Models\Faq;

class FaqObserver
{
    public function saved(Faq $faq): void
    {
        Cache::forget('faqs');
    }

    public function deleted(Faq $faq): void
    {
        Cache::forget('faqs');
    }
}
