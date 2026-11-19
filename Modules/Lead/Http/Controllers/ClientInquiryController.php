<?php

namespace Modules\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lead\Http\Requests\ClientInquiryRequest;
use Modules\Property\Models\Property;
use Modules\Lead\Models\PropertyLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ClientInquiryController extends Controller
{
    public function ClientInquiry(ClientInquiryRequest $request)
    {
        $clientData = $request->validated();
        $property = Property::query()->where('id', $clientData['propertyId'])->first();
        unset($clientData['propertyId']);
        PropertyLead::create([
            'property_id' => $property->id,
            'full_name' => $clientData['name'],
            'email' => $clientData['email'],
            'phone' => $clientData['phone'],
            'message' => $clientData['message'] ?? 'No message',
        ]);
//        Mail::to('mahmodaborakika2@gmail.com')->send(new \Modules\Lead\Mail\ClientInquiryMail($property, $clientData));
        return redirect()->back();
    }
}
