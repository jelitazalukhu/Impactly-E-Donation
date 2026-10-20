<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Tujuan setelah login atau registrasi, sesuai role. */
    private function homeFor(User $user): string
    {
        return match ($user->role) {
            'admin'      => route('admin.dashboard'),
            'penggalang' => route('penggalang.dashboard'),
            default      => route('home'),
        };
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Hanya akun aktif yang boleh masuk.
        $attempt = [
            'email'     => $data['email'],
            'password'  => $data['password'],
            'is_active' => 1,
        ];

        if (Auth::attempt($attempt, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($this->homeFor(Auth::user()));
        }

        // Beri pesan khusus bila password benar tetapi akun dinonaktifkan admin.
        $user = User::where('email', $data['email'])->first();
        if ($user && ! $user->is_active && Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi admin.'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:100', 'unique:users,email'],
            'phone'             => ['nullable', 'string', 'max:20'],
            // Admin TIDAK boleh mendaftar lewat form: hanya dua peran ini.
            'role'              => ['required', Rule::in(['donatur', 'penggalang'])],
            'organization_name' => ['nullable', 'string', 'max:150'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'role.required'      => 'Pilih peran Anda.',
            'role.in'            => 'Peran tidak valid.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'role'              => $data['role'],
            // Hanya penggalang yang menyimpan nama organisasi.
            'organization_name' => $data['role'] === 'penggalang' ? ($data['organization_name'] ?? null) : null,
            'password'          => Hash::make($data['password']),
            'is_active'         => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect($this->homeFor($user))->with('success', 'Registrasi berhasil. Selamat datang di E-Donation!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
