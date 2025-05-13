<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
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
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed', // <-- 'confirmed' handles match check
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->with( 'error', 'User with this email does not exist.');
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if (!empty($request->password )) {
                $user->password = bcrypt($request->password );
            }
            if ($request->hasFile('image') && $request->file('image')->isValid()) {

                if (!empty($user->image) && File::exists(public_path($user->image))) {
                    File::delete(public_path($user->image));
                }
                $thumbnail = $request->file('image');
                $thumbnailName = 'user-profile_' . time() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move(public_path('/uploads/user-profile/'), $thumbnailName);
                $user->image = '/uploads/user-profile/' . $thumbnailName;
            }
            $user->save();

            return back()->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again');
        }
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
}
