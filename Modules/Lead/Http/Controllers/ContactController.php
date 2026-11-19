<?php

namespace Modules\Lead\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lead\Http\Requests\ContactRequest;
use Modules\Lead\Models\ContactLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/ContactUs', [
            'metaTitle' => 'Contact Qeima | Get in Touch with Egypt’s Trusted Property Platform',
            'metaDescription' => "Have questions or need support? Contact Qeima today. Our team is here to help with property listings, buying, selling, or
              renting in Egypt. Reach us via phone, email, or our contact form. We're happy to assist you!",
        ]);
    }

    public function send(ContactRequest $request)
    {
        $validatedData = $request->validated();
        ContactLead::create($validatedData);
//        Mail::to('mahmodaborakika2@gmail . com')->send(new \Modules\Lead\Mail\ContactMail($validatedData));
        return redirect()->back();
    }
}
