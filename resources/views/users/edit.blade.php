
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Pengguna
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow">

                @if ($errors->any())
                    <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="mb-1 block font-medium">Nama Lengkap</label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="mb-1 block font-medium">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="mb-1 block font-medium">Role</label>
                        <select name="role" required
                                class="w-full rounded-md border-gray-300 shadow-sm">
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                            <option value="karyawan" {{ old('role', $user->role) === 'karyawan' ? 'selected' : '' }}>
                                Karyawan
                            </option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="mb-1 block font-medium">Password Baru (Opsional)</label>
                        <input type="password" name="password"
                               minlength="8"
                               autocomplete="new-password"
                               class="w-full rounded-md border-gray-300 shadow-sm">
                        <p class="mt-1 text-sm text-gray-500">
                            Kosongkan jika tidak ingin mengubah password.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('users.index') }}"
                           class="rounded-md border px-4 py-2 text-gray-700">
                            Batal
                        </a>

                        <button type="submit"
                                class="rounded-md bg-blue-600 px-4 py-2 text-white">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
