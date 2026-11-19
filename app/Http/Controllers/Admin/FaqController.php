<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::paginate(10);
        return Inertia::render('Admin/Faqs/Index', [
                'faqs' => FaqResource::collection($faqs)]
        );
    }

    public function create()
    {
        return Inertia::render('Admin/Faqs/Create');
    }

    public function store(FaqRequest $request)
    {
            $validatedData=$request->validated();
            Faq::create($validatedData);
            return redirect()->route('faq.index')->with('success', 'Faq created successfully');
    }

    public function edit(Faq $faq)
    {
        return Inertia::render('Admin/Faqs/Edit', ['faq' => new FaqResource($faq)]);
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $validatedData = $request->validated();
        $faq->update($validatedData);
        return redirect()->route('faq.index')->with('success', 'Faq updated successfully');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('faq.index')->with('success', 'Faq deleted successfully');
    }
}
