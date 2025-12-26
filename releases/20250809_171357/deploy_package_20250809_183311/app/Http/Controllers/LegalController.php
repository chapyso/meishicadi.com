<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Display the Terms of Service page
     */
    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    /**
     * Display the Privacy Policy page
     */
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    /**
     * Display the Cookie Policy page
     */
    public function cookiePolicy()
    {
        return view('legal.cookie-policy');
    }
}
