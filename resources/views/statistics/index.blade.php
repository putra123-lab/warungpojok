
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-neutral-900">
                Statistik WARUNG POJOK
            </h2>
            <p class="mt-1 text-sm text-neutral-500">
                Ringkasan operasional dan penjualan hari ini.
            </p>
        </div>
    </x-slot>

    <div class="px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-6 rounded-2xl bg-neutral-900 p-6 text-white shadow-lg sm:p-8">
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-orange-400">
                WARUNG POJOK POS
            </p>

            <h1 class="mt-3 text-2xl font-extrabold sm:text-3xl">
                Statistik Hari Ini
            </h1>

            <p class="mt-2 text-sm text-neutral-300">
                Pantau penjualan, transaksi, produk, dan stok dalam satu halaman.
            </p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-bold text-neutral-900">
                Ringkasan Hari Ini
            </h3>
            <p class="mt-1 text-sm text-neutral-500">
                Informasi utama operasional warung.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-neutral-500">Penjualan Hari Ini</span>
                    <span class="rounded-lg bg-orange-50 px-3 py-2 text-xl">💰</span>
                </div>

                <p class="mt-5 text-2xl font-extrabold text-neutral-900">
                    Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}
                </p>

                <p class="mt-2 text-sm text-neutral-400">
                    Total nilai penjualan hari ini.
                </p>
            </div>

            <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-neutral-500">Transaksi Hari Ini</span>
                    <span class="rounded-lg bg-neutral-100 px-3 py-2 text-xl">🧾</span>
                </div>

                <p class="mt-5 text-2xl font-extrabold text-neutral-900">
                    {{ $transaksiHariIni }}
                </p>

                <p class="mt-2 text-sm text-neutral-400">
                    Jumlah transaksi yang tercatat.
                </p>
            </div>

            <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-neutral-500">Total Produk</span>
                    <span class="rounded-lg bg-orange-50 px-3 py-2 text-xl">📦</span>
                </div>

                <p class="mt-5 text-2xl font-extrabold text-neutral-900">
                    {{ $totalProduk }}
                </p>

                <p class="mt-2 text-sm text-neutral-400">
                    Produk yang terdaftar di sistem.
                </p>
            </div>

            <div class="rounded-2xl border border-orange-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm font-semibold text-neutral-500">Stok Menipis</span>
                    <span class="rounded-lg bg-red-50 px-3 py-2 text-xl">⚠️</span>
                </div>

                <p class="mt-5 text-2xl font-extrabold text-neutral-900">
                    {{ $stokMenipis }}
                </p>

                <p class="mt-2 text-sm text-neutral-400">
                    Produk dengan stok maksimal 5.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
