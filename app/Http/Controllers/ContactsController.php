<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use MixCode\Contact;
use MixCode\Http\Requests\ContactsRequest;
use MixCode\Utils\UsingSEO;

class ContactsController extends Controller
{
    use UsingSEO;
    
    public function index()
    {
        tap(trans('site.contact_us'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => config('app.name') . ' ' . $seoTitle]);
        });

        return view('contact-us');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:191',
            'phone'     => 'required',
            'email'     => 'required|string|email',
            'message'   => 'required|max:500',
        ]);
        
        Contact::create($validated);
        //->notifyAdminsForNewMessage();
        
        notify('success', trans('main.thanks_for_your_message'));

        return back();
    }
}
