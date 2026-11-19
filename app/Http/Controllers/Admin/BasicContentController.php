<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BasicContentRequest;
use App\Http\Resources\BasicContentResource;
use Inertia\Inertia;

class BasicContentController extends Controller
{
    public function index()
    {
        $basicContent=\App\Models\BasicContent::all();
        return Inertia::render('Admin/BasicContents/Index', [
            'contents' => BasicContentResource::collection($basicContent)
        ]);
    }

    public function edit($BasicContent)
    {
        $basicContent=\App\Models\BasicContent::findOrFail($BasicContent);
        return Inertia::render('Admin/BasicContents/Edit', [
            'basicContent' => new BasicContentResource($basicContent)
        ]);
    }

    public function update($BasicContent, BasicContentRequest $request)
    {
        $validatedData=$request->validated();
        $basicContent=\App\Models\BasicContent::findOrFail($BasicContent);
        $basicContent->update($validatedData);
        return redirect()->route('content.index')->with('success', 'Basic Content updated successfully.');
    }
}
