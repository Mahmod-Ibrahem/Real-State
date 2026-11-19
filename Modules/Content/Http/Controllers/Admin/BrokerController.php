<?php

namespace Modules\Content\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Content\Http\Requests\BrokerRequest;
use Modules\Content\Http\Resources\BrokerResource;
use Modules\Content\Models\Broker;
use App\Traits\ImagesUtility;
use Inertia\Inertia;

/** Created and edited in a modal on the list page (ADMIN_UI.md §5). */
class BrokerController extends Controller
{
    use ImagesUtility;

    public function index()
    {
        return Inertia::render('Admin/Brokers/Index', [
            'brokers' => BrokerResource::collection(Broker::all()),
        ]);
    }

    public function store(BrokerRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['image'] = $this->storeImage($validatedData['image'], 'broker');
        Broker::create($validatedData);

        return back();
    }

    public function update(BrokerRequest $request, Broker $broker)
    {
        $validatedData = $request->validated();

        if ($validatedData['image'] ?? false) {
            $validatedData['image'] = $this->storeImage($validatedData['image'], 'broker');
        } else {
            unset($validatedData['image']);
        }

        $broker->update($validatedData);

        return back();
    }

    public function destroy(Broker $broker)
    {
        $broker->delete();

        return back();
    }
}
