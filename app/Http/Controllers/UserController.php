<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of system users (Admin only).
     */
    public function index()
    {
        $users = User::with('profile')
            ->orderBy('id')
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,staff'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string'],
        ], [
            'username.required' => 'Nama pengguna (username) wajib diisi.',
            'username.unique' => 'Username ini sudah terdaftar.',
            'full_name.required' => 'Nama lengkap pengguna wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'role.required' => 'Peran (role) pengguna wajib dipilih.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => strtolower($validated['username']),
                'name' => $validated['full_name'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('users.index')
            ->with('success', "Pengguna '{$validated['username']}' berhasil didaftarkan ke sistem.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('profile');

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,staff'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string'],
        ], [
            'username.required' => 'Nama pengguna (username) wajib diisi.',
            'username.unique' => 'Username ini sudah terdaftar.',
            'full_name.required' => 'Nama lengkap pengguna wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'role.required' => 'Peran (role) pengguna wajib dipilih.',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $userData = [
                'username' => strtolower($validated['username']),
                'name' => $validated['full_name'],
                'email' => $validated['email'] ?? null,
                'role' => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]
            );
        });

        return redirect()->route('users.index')
            ->with('success', "Data pengguna '{$user->username}' berhasil diperbarui.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang masuk!');
        }

        $username = $user->username;
        $user->profile()?->delete();
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna '{$username}' berhasil dihapus.");
    }
}
