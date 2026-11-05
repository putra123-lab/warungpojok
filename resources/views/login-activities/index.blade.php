
<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-orange-600">
                WARUNG POJOK
            </p>
            <h2 class="mt-1 text-2xl font-bold text-neutral-900">
                Riwayat Login & Logout
            </h2>
            <p class="mt-1 text-sm text-neutral-500">
                Rekap aktivitas login dan logout akun karyawan.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-orange-50/50 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Ringkasan --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-500">Total Sesi Login</p>
                    <p class="mt-2 text-3xl font-bold text-neutral-900">
                        {{ $totalLogin }}
                    </p>
                </div>

                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-500">Belum Logout</p>
                    <p class="mt-2 text-3xl font-bold text-orange-600">
                        {{ $totalAktif }}
                    </p>
                </div>

                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-500">Sesi Selesai</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $totalSelesai }}
                    </p>
                </div>

            </div>

            {{-- Filter --}}
            <div class="mb-6 rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                <form method="GET"
                      action="{{ route('login-activities.index') }}"
                      class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                    <div>
                        <label class="mb-2 block text-sm font-bold text-neutral-700">
                            Tanggal Login
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            value="{{ request('tanggal') }}"
                            class="w-full rounded-xl border-neutral-200 focus:border-orange-500 focus:ring-orange-500"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-neutral-700">
                            Nama Karyawan
                        </label>
                        <input
                            type="text"
                            name="nama"
                            value="{{ request('nama') }}"
                            placeholder="Cari nama karyawan..."
                            class="w-full rounded-xl border-neutral-200 focus:border-orange-500 focus:ring-orange-500"
                        >
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600">
                            Cari Data
                        </button>

                        <a href="{{ route('login-activities.index') }}"
                           class="rounded-xl border border-neutral-200 px-5 py-3 text-sm font-bold text-neutral-700 hover:bg-neutral-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">

                <div class="border-b border-orange-100 px-6 py-5">
                    <h3 class="text-lg font-bold text-neutral-900">
                        Daftar Aktivitas Karyawan
                    </h3>
                    <p class="mt-1 text-sm text-neutral-500">
                        Catatan waktu masuk dan keluar setiap akun.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">

                        <thead class="bg-neutral-900 text-white">
                            <tr>
                                <th class="px-5 py-4">No.</th>
                                <th class="px-5 py-4">Nama Karyawan</th>
                                <th class="px-5 py-4">Role</th>
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Jam Login</th>
                                <th class="px-5 py-4">Jam Logout</th>
                                <th class="px-5 py-4">Durasi</th>
                                <th class="px-5 py-4">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-orange-50">
                            @forelse ($activities as $activity)
                                <tr class="hover:bg-orange-50/70">

                                    <td class="px-5 py-4 text-neutral-500">
                                        {{ $activities->firstItem() + $loop->index }}
                                    </td>

                                    <td class="px-5 py-4 font-bold text-neutral-900">
                                        {{ $activity->user_name }}
                                    </td>

                                    <td class="px-5 py-4 text-neutral-600">
                                        {{ ucfirst($activity->user_role ?? '-') }}
                                    </td>

                                    <td class="px-5 py-4 text-neutral-600">
                                        {{ $activity->login_at?->format('d/m/Y') ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 font-semibold">
                                        {{ $activity->login_at?->format('H:i:s') ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 font-semibold">
                                        {{ $activity->logout_at?->format('H:i:s') ?? '-' }}
                                    </td>

                                    <td class="px-5 py-4 text-neutral-600">
                                        @if ($activity->logout_at && $activity->login_at)
                                            @php
                                                $seconds = $activity->login_at->diffInSeconds($activity->logout_at);
                                                $hours = floor($seconds / 3600);
                                                $minutes = floor(($seconds % 3600) / 60);
                                            @endphp
                                            {{ $hours }} jam {{ $minutes }} menit
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        @if ($activity->status === 'selesai')
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                                Selesai
                                            </span>
                                        @else
                                            <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-700">
                                                Belum Logout
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <p class="font-bold text-neutral-800">
                                            Belum ada riwayat login.
                                        </p>
                                        <p class="mt-1 text-sm text-neutral-500">
                                            Aktivitas login karyawan akan muncul di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="border-t border-orange-100 px-6 py-4">
                    {{ $activities->links() }}
                </div>

            </div>

            <footer class="mt-6 text-center text-xs text-neutral-400">
                © {{ date('Y') }} WARUNG POJOK
            </footer>

        </div>
    </div>
</x-app-layout>
