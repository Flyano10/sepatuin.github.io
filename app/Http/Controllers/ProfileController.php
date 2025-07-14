<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();

        // Handle upload foto profil
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_photos', $filename);

            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                \Storage::delete('public/profile_photos/' . $user->profile_photo);
            }

            $user->profile_photo = $filename;
        }

        $user->fill(array_merge($data, [
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'birthdate' => $request->input('birthdate'),
            'gender' => $request->input('gender'),
        ]));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->profile_photo) {
            \Storage::delete('public/profile_photos/' . $user->profile_photo);
            $user->profile_photo = null;
            $user->save();
        }
        return Redirect::route('profile.edit')->with('status', 'profile-photo-deleted');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }
}
