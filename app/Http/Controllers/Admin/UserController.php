<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /** Daftar pengguna (donatur dan penggalang dana). */
    public function index(Request $request): View
    {
        $role = $request->query('role');
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        // Admin tunggal tidak dimasukkan dalam pengelolaan akun biasa
        $query = User::where('role', '!=', 'admin')->latest('created_at');

        if ($role && in_array($role, ['donatur', 'penggalang'], true)) {
            $query->where('role', $role);
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', (bool) $status);
        }

        if ($search !== '') {
            $query->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('organization_name', 'like', "%{$search}%");
            });
        }

        return view('admin.users.index', [
            'users'   => $query->paginate(12)->withQueryString(),
            'role'    => $role,
            'status'  => $status,
            'search'  => $search,
            'counts'  => [
                'all'        => User::where('role', '!=', 'admin')->count(),
                'donatur'    => User::where('role', 'donatur')->count(),
                'penggalang' => User::where('role', 'penggalang')->count(),
                'inactive'   => User::where('role', '!=', 'admin')->where('is_active', false)->count(),
            ],
        ]);
    }

    /** Form edit user. */
    public function edit(User $user): View
    {
        if ($user->role === 'admin') {
            abort(403, 'Akun admin tidak dapat diubah dari menu kelola user.');
        }

        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /** Update data user. */
    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:100'],
            'email'             => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id_user, 'id_user')],
            'phone'             => ['nullable', 'string', 'max:20'],
            'organization_name' => ['nullable', 'string', 'max:150'],
            'is_active'         => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    /** Aktifkan / Nonaktifkan akun pengguna. */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Akun \"{$user->name}\" berhasil {$statusText}.");
    }

    /** Hapus pengguna (soft delete). */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$user->name}\" berhasil dihapus.");
    }
}
