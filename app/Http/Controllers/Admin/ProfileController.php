<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\AdminEmailChangedNotification;
use App\Notifications\AdminPendingEmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $requestedEmail = (string) $request->input('email');
        $emailChanged = $requestedEmail !== $admin->email;

        $rules = [
            'name'                  => 'required|string|max:255',
            'email'                 => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
                Rule::unique('users', 'pending_email')->ignore($admin->id),
            ],
            'avatar'                => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_current_avatar' => 'sometimes|boolean',
        ];

        if ($emailChanged) {
            $rules['current_password'] = 'required|current_password';
        }

        $validated = $request->validate($rules);

        if (!empty($validated['remove_current_avatar']) && $admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
            $admin->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $admin->avatar = $avatarPath;
        }

        $admin->name = $validated['name'];

        if ($emailChanged) {
            $oldEmail = $admin->email;
            $newEmail = $validated['email'];
            $token = Str::random(64);

            $admin->forceFill([
                'pending_email'         => $newEmail,
                'pending_email_token'   => $token,
                'pending_email_sent_at' => now(),
            ])->save();

            Notification::route('mail', $oldEmail)
                ->notify(new AdminEmailChangedNotification($oldEmail, $newEmail));

            $admin->notify(new AdminPendingEmailVerificationNotification(
                $newEmail,
                $token,
                EmailChangeController::HOURS_VALID
            ));

            return back()->with(
                'success',
                'Đã gửi liên kết xác nhận tới ' . $newEmail . '. Email tài khoản chỉ được cập nhật sau khi bạn xác nhận tại địa chỉ đó. Một cảnh báo cũng đã được gửi tới email hiện tại.'
            );
        }

        $admin->save();

        return back()->with('success', 'Hồ sơ đã được cập nhật thành công.');
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

        return back()->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
