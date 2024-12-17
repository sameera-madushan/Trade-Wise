<?php

namespace Package\XAuth\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Package\XAuth\Models\User;
use Package\XAuth\Requests\ChangeProfilePictureRequest;
use Package\XAuth\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('XAuth/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'isUser' => $user->hasRole('USER')
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if ($request->user()->hasRole('USER')) {
            return Redirect::to('/user/my-profile');
        }

        return Redirect::to('/admin/my-profile');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Change user profile pic
     *
     * @param  ChangeProfilePictureRequest  $request
     * @return RedirectResponse
     */
    public function changeProfilePicture(ChangeProfilePictureRequest $request)
    {
        $user = User::find(auth()->id);
        $user->addMediaFromRequest('file')->toMediaCollection('profile_picture');

        return Redirect::to('/admin/my-profile');
    }
}
