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
        'remove_photo' => [
            'nullable', 
            'boolean'
            ],
    ]);

    $oldPhoto = $user->profile_photo; //path of photo currently associated with the user
    $newPhoto = null;

    if ($request->boolean('remove_photo')) {
    $validated['profile_photo'] = null;
    }

    elseif ($request->hasFile('photo')) {//checks if user has uploaded a photo
        $newPhoto = $request->file('photo')
            ->store('profile-photos', 'public');

        if (!$newPhoto) {//checks whether upload was succesful
            return back()->withErrors([
                'photo' => 'Unable to upload profile photo.',
            ]);
        }

        $validated['profile_photo'] = $newPhoto;
    }

    unset($validated['photo'], $validated['remove_photo']);

    $user->fill($validated);

    if (!$user->isDirty()) {//checks whether any chnages were made
        return back()->with(
            'success',
            'No changes were made to your profile.'
        );
    }

    $user->save();

    //delete the old photo only after saving the new profile.
    if ($oldPhoto && ($newPhoto || $request->boolean('remove_photo'))) {
    Storage::disk('public')->delete($oldPhoto);
    }

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