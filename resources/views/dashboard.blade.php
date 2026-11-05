
<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">
                WARUNG POJOK
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Sistem Informasi Kasir
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-white py-8">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">

            {{-- INFORMASI PENGGUNA --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 sm:text-base">
                        Selamat beraktivitas di sistem kasir WARUNG POJOK.
                    </p>
                </div>

                <div class="w-fit rounded-full bg-orange-100 px-4 py-2 text-sm font-semibold text-orange-700">
                    {{ ucfirst(Auth::user()->role ?? 'karyawan') }}
                </div>
            </div>

            {{-- BANNER FOTO WARUNG --}}
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                {{-- AREA FOTO --}}
                <div class="relative h-[450px] bg-white sm:h-[550px] lg:h-[700px]">

                    <img
                        src="{{ asset('images/warung-pojok.jpeg') }}"
                        alt="Bangunan WARUNG POJOK"
                        class="absolute inset-0 h-full w-full object-contain object-center"
                        style="background-color: #ffffff;"
                        onerror="this.style.display='none'; document.getElementById('foto-fallback').style.display='flex';"
                    >

                    {{-- FOTO CADANGAN --}}
                    <div
                        id="foto-fallback"
                        style="display: none;"
                        class="absolute inset-0 flex-col items-center justify-center bg-white p-6 text-center text-gray-900"
                    >
                        <div class="text-5xl">🏪</div>
                        <p class="mt-4 text-xl font-bold">
                            WARUNG POJOK
                        </p>
                        <p class="mt-2 text-sm text-gray-500">
                            Periksa file public/images/warung-pojok.jpeg
                        </p>
                    </div>

                    {{-- OVERLAY FOTO --}}
                    <div
                        class="pointer-events-none absolute inset-0"
                        style="background: linear-gradient(to top, rgba(0,0,0,0.65), rgba(0,0,0,0.08) 55%, transparent);"
                    ></div>

                    {{-- JUDUL BANNER --}}
                    <div class="absolute bottom-0 left-0 right-0 p-5 sm:p-8 lg:p-10">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-300 sm:text-sm">
                            Selamat Datang
                        </p>

                        <h2 class="mt-2 text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
                            WARUNG POJOK
                        </h2>

                        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white sm:text-base">
                            Sistem Informasi Kasir untuk membantu pengelolaan
                            transaksi dan persediaan warung dengan lebih mudah.
                        </p>
                    </div>
                </div>

                {{-- INFORMASI DI BAWAH FOTO --}}
                <div class="grid gap-6 p-5 sm:p-8 md:grid-cols-2 md:items-center">

                    <div>
                        <h3 class="text-xl font-bold text-gray-900 sm:text-2xl">
                            Kelola Warung Lebih Mudah
                        </h3>

                        <p class="mt-3 text-sm leading-7 text-gray-600 sm:text-base">
                            Gunakan sistem kasir untuk mencatat transaksi,
                            memeriksa riwayat penjualan, dan mengelola data
                            produk maupun persediaan bahan baku.
                        </p>
                    </div>

                    <div class="md:flex md:justify-end">
                        <a
                            href="{{ route('sales.create') }}"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-orange-500 px-6 py-3 font-bold text-white shadow-sm transition hover:bg-orange-600 sm:w-auto"
                        >
                            <span class="mr-2">＋</span>
                            Transaksi Baru
                        </a>
                    </div>

                </div>
            </section>

            {{-- MENU UTAMA --}}
            <section>
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">
                        Menu Utama
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Pilih menu sesuai aktivitas yang ingin dilakukan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                    {{-- KASIR --}}
                    <a
                        href="{{ route('sales.create') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-2xl">
                            🛒
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                            Kasir
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Buat transaksi dan proses pembayaran pelanggan.
                        </p>

                        <p class="mt-4 text-sm font-semibold text-orange-600">
                            Buka kasir →
                        </p>
                    </a>

                    {{-- RIWAYAT TRANSAKSI --}}
                    <a
                        href="{{ route('sales.index') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                            🧾
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                            Transaksi
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Lihat daftar dan riwayat transaksi penjualan.
                        </p>

                        <p class="mt-4 text-sm font-semibold text-orange-600">
                            Lihat transaksi →
                        </p>
                    </a>

                    {{-- LAPORAN PENJUALAN --}}
                    <a
                        href="{{ route('sales.report') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                            📊
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                            Laporan Penjualan
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Lihat laporan dan ringkasan penjualan warung.
                        </p>

                        <p class="mt-4 text-sm font-semibold text-orange-600">
                            Buka laporan →
                        </p>
                    </a>

                    {{-- STATISTIK --}}
                    <a
                        href="{{ route('statistics.index') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                            📈
                        </div>

                        <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                            Statistik
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Pantau ringkasan dan perkembangan penjualan.
                        </p>

                        <p class="mt-4 text-sm font-semibold text-orange-600">
                            Lihat statistik →
                        </p>
                    </a>

                </div>
            </section>

            {{-- MENU ADMIN --}}
            @if (Auth::user()->role === 'admin')
                <section>
                    <div class="mb-5">
                        <h2 class="text-xl font-bold text-gray-900 sm:text-2xl">
                            Pengelolaan Sistem
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Menu pengelolaan khusus administrator.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                        {{-- PRODUK --}}
                        <a
                            href="{{ route('products.index') }}"
                            class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                                📦
                            </div>

                            <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                                Produk
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Kelola produk, harga, dan stok barang.
                            </p>
                        </a>

                        {{-- BAHAN BAKU --}}
                        <a
                            href="{{ route('ingredients.index') }}"
                            class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                                🥬
                            </div>

                            <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                                Bahan Baku
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Kelola persediaan bahan baku warung.
                            </p>
                        </a>

                        {{-- PENGGUNA --}}
                        <a
                            href="{{ route('users.index') }}"
                            class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-2xl">
                                👥
                            </div>

                            <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-orange-600">
                                Pengguna
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Kelola akun administrator dan karyawan.
                            </p>
                        </a>

                    </div>
                </section>
            @endif

            {{-- FOOTER --}}
            <footer class="border-t border-gray-200 py-5 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} WARUNG POJOK
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Sistem Informasi Kasir
                </p>
            </footer>

        </div>
    </div>
</x-app-layout>
