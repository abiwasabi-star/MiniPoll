<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Display the registration page.
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle a new user registration.
     */
    public function store(Request $request)
    {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    $user = User::create($validated);

    event(new Registered($user));

    Auth::login($user);

    $request->session()->regenerate();

    return redirect()->route('verification.notice');
    }
}
