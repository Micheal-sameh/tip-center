<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function about()
    {
        return view('landing.about');
    }

    public function demo()
    {
        // Redirect to the demo subdomain
        $demoDomain = 'demo.'.config('app.domain', 'tipcenter.test');

        return redirect('http://'.$demoDomain);
    }
}
