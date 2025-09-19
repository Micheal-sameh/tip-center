<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }

    public function login(loginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $request->session()->regenerate();

            return redirect()->intended(route('sessions.index'))->with('success', "Welcome back, $user->name!");
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => __('auth.failed')]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('loginPage');
    }
}
