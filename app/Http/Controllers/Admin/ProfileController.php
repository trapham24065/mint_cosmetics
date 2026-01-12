<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index(): View
    {
        $admin = Auth::user();
        $title = "Admin Profile";
        return view('admin.profile.index', compact('admin', 'title'));
    }

    /**
     * Update your basic information (Name, Email, Avatar).
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,'.$admin->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $admin->avatar = $avatarPath;
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

}
