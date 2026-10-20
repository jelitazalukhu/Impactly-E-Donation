<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Form profil pengguna. */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /** Simpan perubahan biodata profil. */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'phone'             => ['nullable', 'string', 'max:20'],
            'organization_name' => ['nullable', 'string', 'max:150'],
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email ini sudah terdaftar oleh pengguna lain.',
        ]);

        if ($user->role !== 'penggalang') {
            unset($validated['organization_name']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /** Ubah password pengguna. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password baru tidak sama.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password Anda berhasil diubah.');
    }
}
