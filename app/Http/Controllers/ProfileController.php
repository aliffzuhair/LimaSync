<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update the user's profile details.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        // ✅ Log activity
        ActivityLogger::update(
            'Profile',
            $user->id,
            $user->full_name . ' updated their profile details'
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'profile_picture.image' => 'The file must be a valid image.',
            'profile_picture.mimes' => 'Only JPG and PNG files are allowed.',
            'profile_picture.max' => 'The image must not be larger than 2MB.',
        ]);

        $user = Auth::user();

        // Delete old picture
        if ($user->profile_picture) {
            $oldPath = storage_path('app/public/' . $user->profile_picture);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        // Save new picture
        $file = $request->file('profile_picture');
        $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        // ✅ Make sure the profiles folder exists
        $profilesDir = storage_path('app/public/profiles');
        if (!file_exists($profilesDir)) {
            mkdir($profilesDir, 0755, true);
        }

        // Move the uploaded file
        $file->move($profilesDir, $filename);

        // Save relative path
        $user->update([
            'profile_picture' => 'profiles/' . $filename,
        ]);

        // Log activity
        ActivityLogger::update(
            'Profile',
            $user->id,
            $user->full_name . ' updated their profile picture'
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profile picture updated successfully!');
    }

    /**
     * Remove the user's profile picture.
     */
    public function removePicture()
    {
        $user = Auth::user();

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->update(['profile_picture' => null]);

        ActivityLogger::update(
            'Profile',
            $user->id,
            $user->full_name . ' removed their profile picture'
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profile picture removed successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // ✅ Log activity
        ActivityLogger::update(
            'Profile',
            $user->id,
            $user->full_name . ' changed their password'
        );

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }
}