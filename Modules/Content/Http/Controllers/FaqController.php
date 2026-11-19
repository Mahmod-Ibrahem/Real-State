<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Content\Http\Resources\Users\FaqResource;
use Modules\Content\Services\FaqService;

class FaqController extends Controller
{
    public function __construct(protected FaqService $faqService)
    {
    }

    public function __invoke(): Response
    {
        return Inertia::render('Users/Faqs', [
            'faqs' => FaqResource::collection($this->faqService->all()),
        ]);
    }
}
