<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrokerRequest;
use App\Http\Resources\BrokerResource;
use App\Models\Broker;
use App\Traits\ImagesUtility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrokerController extends Controller
{
    use ImagesUtility;

    public function index()
    {
        $brokers = Broker::all();
        return Inertia::render('Admin/Brokers/Index', [
            'brokers' => BrokerResource::collection($brokers)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Brokers/Create');
    }

    public function store(BrokerRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['image'] = $this->storeImage($validatedData['image'], 'property');
        Broker::create($validatedData);
        return redirect()->route('broker.index');
    }

    public function edit(Broker $broker)
    {
        return Inertia::render('Admin/Brokers/Edit', [
            'broker' => BrokerResource::make($broker)
        ]);
    }

    public function update(BrokerRequest $request, Broker $broker)
    {
        $validatedData = $request->validated();
        if (isset($validatedData['image'])) {
            $relativePath = $this->getRelativePath($broker->image);
            $validatedData['image'] = $this->storeImage($validatedData['image'], 'broker'); //path data
        } else {
            $validatedData['image'] = $broker->image;
        }
        $broker->update($validatedData);
        return redirect()->route('broker.index');
    }

    public function destroy(Broker $broker)
    {
        $broker->delete();
        return redirect()->route('broker.index');
    }
}
