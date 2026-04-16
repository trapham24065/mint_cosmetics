<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * Display a listing of the users.
     */
    public function index(): View
    {
        $title = 'Quản lý tài khoản';
        $users = User::latest()->paginate(15);

        return view('admin.management.users.index', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $title = 'Tạo tài khoản mới';
        $roles = ['admin', 'sale', 'warehouse'];

        return view('admin.management.users.create', compact('roles', 'title'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,sale,warehouse'],
            'status'   => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'],
            'status'            => $validated['status'] ?? 1,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tài khoản đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $title = 'Chỉnh sửa tài khoản: ' . $user->name;
        $roles = ['admin', 'sale', 'warehouse'];

        return view('admin.management.users.edit', compact('user', 'roles', 'title'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,sale,warehouse'],
            'status'   => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'role'   => $validated['role'],
            'status' => $validated['status'] ?? 0,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Tài khoản đã được cập nhật thành công!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Tài khoản đã được xóa thành công!');
    }

    /**
     * Provide data for the Grid.js table via AJAX.
     */
    public function getDataForGrid(): JsonResponse
    {
        $users = User::latest()->get();

        $data = $users->map(function ($user) {
            return [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role ?? 'admin',
                'status'     => $user->status ? 'active' : 'inactive',
                'created_at' => $user->created_at->format('d/m/Y'),
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Handle bulk actions for users.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action'   => ['required', 'string', 'in:change_status'],
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'value'    => ['required'],
        ]);

        try {
            // Prevent changing yourself
            $currentUserId = auth()->id();
            if (in_array($currentUserId, $validated['user_ids'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không thể thay đổi trạng thái của chính mình!',
                ], 422);
            }

            $count = User::whereIn('id', $validated['user_ids'])
                ->update(['status' => $validated['value']]);

            return response()->json([
                'success' => true,
                'message' => "{$count} người dùng đã được cập nhật thành công.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi.',
            ], 500);
        }
    }
}
