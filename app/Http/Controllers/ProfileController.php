<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
     Show profile page
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /*
     Update profile information
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // ONLY SAFE FIELDS UPDATE

        $user->fill($request->validated());

        // NEVER allow staff_id to be changed (force lock)
        $user->staff_id = $user->getOriginal('staff_id');

        // Reset email verification if email changes
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }


        if ($request->hasFile('photo')) {

            // delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // store new photo
            $path = $request->file('photo')->store('profile-photos', 'public');

            $user->photo = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete account
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // delete profile photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
