<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientInquiryRequest;
use App\Models\Property;
use App\Models\PropertyLead;
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
//        Mail::to('mahmodaborakika2@gmail.com')->send(new \App\Mail\ClientInquiryMail($property, $clientData));
        return redirect()->back();
    }
}
