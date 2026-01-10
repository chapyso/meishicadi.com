<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SimpleSignupRequest;

class SignupController extends Controller
{
    /**
     * Show the signup form
     */
    public function show()
    {
        return view('signup-form');
    }

    /**
     * Handle signup form submission
     */
    public function submit(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'cards_required' => 'required|string|max:50',
            'industry' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Please enter your full name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'company.required' => 'Please enter your company name.',
            'cards_required.required' => 'Please select the number of cards required.',
            'terms.required' => 'You must agree to the terms and conditions.',
            'terms.accepted' => 'You must agree to the terms and conditions.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare email data
            $emailData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'company' => $request->company,
                'cards_required' => $request->cards_required,
                'industry' => $request->industry,
                'message' => $request->message,
                'submitted_at' => now()->format('Y-m-d H:i:s'),
            ];

            // Send email to info@chapysocial.com
            Mail::to('info@chapysocial.com')
                ->send(new SimpleSignupRequest($emailData));

            return redirect()->back()
                ->with('success', 'Thank you for your signup request! We will contact you soon.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['email' => 'Sorry, there was an error sending your request. Please try again later.'])
                ->withInput();
        }
    }
}
