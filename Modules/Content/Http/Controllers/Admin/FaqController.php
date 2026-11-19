<?php

namespace Modules\Content\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Content\Http\Requests\FaqRequest;
use Modules\Content\Http\Resources\FaqResource;
use Modules\Content\Models\Faq;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class FaqController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Faqs/Index', [
            'faqs' => FaqResource::collection(Faq::paginate(10)->withQueryString()),
        ]);
    }

    public function store(FaqRequest $request)
    {
        Faq::create($request->validated());

        return back();
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return back();
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back();
    }
}
