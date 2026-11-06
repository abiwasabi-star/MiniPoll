<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
             $user = Auth::user(); //gets the currently authenticated user

        return Inertia::render('Profile/Index', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo,
                'role' => $user->is_admin ? 'ADMIN' : 'VOTER',
            ],
        ]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
            $user = Auth::user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string', 
                'min:2',
                'max:255',
                'regex:/^[\pL\pM\s.\'-]+$/u',
                ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('photo')) {
            $oldPhoto = $user->profile_photo;

            $validated['profile_photo'] = $request->file('photo')
                ->store('profile-photos', 'public');

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        unset($validated['photo']);

        $user->fill($validated);

        if (!$user->isDirty()) { //checks if the user model has any changes if not...
        return back()->with('success', 'No changes were made to your profile.');
       }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => [
            'required', 
            'string', 
            'min:8', 
            'confirmed',
            'different:current_password',],
    ]);

    /** @var User $user */
    $user = Auth::user();

    $user->update([
        'password' => $validated['password'],
    ]);

    return back()->with('success', 'Password changed successfully!');
}
}