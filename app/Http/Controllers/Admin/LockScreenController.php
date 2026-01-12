<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LockScreenController extends Controller
{

    /**
     * Display the lock screen page.
     */
    public function lock()
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        session(['admin_locked' => true]);

        return view('admin.auth.lock-screen');
    }

    /**
     *
     * Unlock process.
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->password)) {
            session()->forget('admin_locked');
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back!');
        }

        throw ValidationException::withMessages([
            'password' => 'The password is incorrect.',
        ]);
    }

}
