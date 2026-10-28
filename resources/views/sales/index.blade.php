
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-600">
                    WARUNG POJOK
                </p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-neutral-900">
                    Riwayat Penjualan
                </h2>
                <p class="mt-1 text-sm text-neutral-500">
                    Kelola dan pantau seluruh transaksi penjualan.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm font-bold text-neutral-700 no-underline shadow-sm transition hover:border-orange-300 hover:text-orange-600">
                    ← Dashboard
                </a>

                <a href="{{ route('sales.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-bold text-white no-underline shadow-sm transition hover:bg-orange-600">
                    <span class="text-lg">+</span>
                    Transaksi Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-orange-50/50 py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Ringkasan --}}
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-500">Total Transaksi</p>
                    <p class="mt-2 text-3xl font-extrabold text-neutral-900">
                        {{ $sales->count() }}
                    </p>
                    <p class="mt-1 text-xs text-neutral-400">
                        Transaksi pada halaman ini
                    </p>
                </div>

                <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-500">Total Penjualan</p>
                    <p class="mt-2 text-2xl font-extrabold text-orange-600">
                        Rp {{ number_format(
                            $sales->sum(fn ($sale) => (float) ($sale->total ?? 0)),
                            0, ',', '.'
                        ) }}
                    </p>
                    <p class="mt-1 text-xs text-neutral-400">
                        Nilai transaksi pada halaman ini
                    </p>
                </div>

                <div class="rounded-2xl border border-orange-100 bg-neutral-900 p-5 shadow-sm">
                    <p class="text-sm font-medium text-neutral-300">Sistem Kasir</p>
                    <p class="mt-2 text-2xl font-extrabold text-white">
                        WARUNG POJOK
                    </p>
                    <p class="mt-1 text-xs text-orange-300">
                        Transaksi tercatat dalam sistem
                    </p>
                </div>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tabel riwayat --}}
            <div class="overflow-hidden rounded-2xl border border-orange-100 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-orange-100 px-6 py-5">
                    <div>
                        <h3 class="text-lg font-extrabold text-neutral-900">
                            Daftar Transaksi
                        </h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            Riwayat transaksi dan bukti pembayaran.
                        </p>
                    </div>

                    <span class="rounded-full bg-orange-100 px-3 py-1.5 text-xs font-bold text-orange-700">
                        {{ $sales->count() }} transaksi
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-neutral-900 text-white">
                            <tr>
                                <th class="px-6 py-4 font-bold">No.</th>
                                <th class="px-6 py-4 font-bold">Nomor Invoice</th>
                                <th class="px-6 py-4 font-bold">Tanggal</th>
                                <th class="px-6 py-4 text-right font-bold">Total</th>
                                <th class="px-6 py-4 font-bold">Pembayaran</th>
                                <th class="px-6 py-4 text-center font-bold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-orange-50">
                            @forelse ($sales as $sale)
                                <tr class="transition hover:bg-orange-50/70">
                                    <td class="whitespace-nowrap px-6 py-5 text-neutral-500">
                                        {{ $sales instanceof \Illuminate\Pagination\AbstractPaginator
                                            ? $sales->firstItem() + $loop->index
                                            : $loop->iteration }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <span class="font-bold text-neutral-900">
                                            {{ $sale->invoice_number ?? ('TRX-' . $sale->id) }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-neutral-600">
                                        {{ $sale->created_at
                                            ? \Illuminate\Support\Carbon::parse($sale->created_at)->format('d/m/Y H:i')
                                            : '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-right font-extrabold text-neutral-900">
                                        Rp {{ number_format((float) ($sale->total ?? 0), 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-700">
                                            {{ strtoupper($sale->payment_method ?? '-') }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('sales.show', $sale) }}"
                                               class="inline-flex items-center rounded-lg bg-neutral-900 px-3 py-2 text-xs font-bold text-white no-underline transition hover:bg-neutral-700">
                                                Detail
                                            </a>

                                            <a href="{{ route('sales.receipt', $sale) }}"
                                               class="inline-flex items-center rounded-lg bg-orange-500 px-3 py-2 text-xs font-bold text-white no-underline transition hover:bg-orange-600">
                                                Cetak Struk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="text-4xl">🧾</div>
                                        <p class="mt-3 font-bold text-neutral-800">
                                            Belum ada transaksi.
                                        </p>
                                        <p class="mt-1 text-sm text-neutral-500">
                                            Transaksi baru akan muncul di halaman ini.
                                        </p>
                                        <a href="{{ route('sales.create') }}"
                                           class="mt-4 inline-flex rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-bold text-white no-underline hover:bg-orange-600">
                                            + Buat Transaksi
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($sales, 'links'))
                    <div class="border-t border-orange-100 px-6 py-4">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>

            <footer class="mt-6 text-center text-xs text-neutral-400">
                © {{ date('Y') }} WARUNG POJOK · Sistem Kasir dan Manajemen Penjualan
            </footer>
        </div>
    </div>
</x-app-layout>
