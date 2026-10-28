<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Daftar pengguna.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Form tambah pengguna.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'karyawan'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Form edit pengguna.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Simpan perubahan pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'karyawan'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Jangan izinkan admin mengubah perannya sendiri.
        if (
            $user->id === auth()->id()
            && $validated['role'] !== $user->role
        ) {
            throw ValidationException::withMessages([
                'role' => 'Kamu tidak bisa mengubah peran akun sendiri.',
            ]);
        }

        // Jangan sampai admin terakhir kehilangan peran admin.
        if (
            $user->role === 'admin'
            && $validated['role'] !== 'admin'
            && User::where('role', 'admin')->count() <= 1
        ) {
            throw ValidationException::withMessages([
                'role' => 'Peran admin terakhir tidak bisa diubah.',
            ]);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Password hanya diganti jika kolom diisi.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akun sendiri.
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Kamu tidak bisa menghapus akun sendiri.');
        }

        // Lindungi agar selalu ada minimal satu admin.
        if (
            $user->role === 'admin'
            && User::where('role', 'admin')->count() <= 1
        ) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Admin terakhir tidak bisa dihapus.');
        }

        // Pertahankan riwayat transaksi yang terkait dengan pengguna.
        if (Sale::where('user_id', $user->id)->exists()) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Pengguna memiliki riwayat transaksi dan tidak bisa dihapus.'
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
