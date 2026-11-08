<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Display the login page.
     */
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming login request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        if (Auth::user()->is_admin) {
        return redirect()->intended(route('admin.dashboard'));
        }//checks if the currently authenticated user is an admin and redirects them to the admin dashboard
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy the authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
