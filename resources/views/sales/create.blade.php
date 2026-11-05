
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">
                WARUNG POJOK
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kasir / Transaksi Penjualan
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-white py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <strong>Transaksi gagal!</strong>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('sales.store') }}"
                  method="POST"
                  id="formTransaksi">
                @csrf

                {{-- INFORMASI HALAMAN --}}
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">
                            Transaksi Kasir
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Pilih produk dan masukkan jumlah pesanan.
                        </p>
                    </div>

                    <span class="rounded-full bg-orange-100 px-4 py-2 text-sm font-bold text-orange-700">
                        🛒 Point of Sale
                    </span>
                </div>

                {{-- DAFTAR PRODUK --}}
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 p-5">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">
                                Daftar Produk
                            </h2>
                            <p class="text-sm text-gray-500">
                                Pilih produk yang ingin dibeli pelanggan.
                            </p>
                        </div>

                        <span class="rounded-lg bg-orange-50 px-4 py-2 text-sm font-bold text-orange-700">
                            {{ $products->count() }} Produk
                        </span>
                    </div>

                    @if ($products->count() > 0)

                        {{-- NAVIGASI SLIDE --}}
                        <div class="flex items-center justify-between gap-3 border-b border-gray-100 bg-gray-50 px-5 py-4">

                            <button type="button"
                                    id="prevSlide"
                                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40">
                                ← Sebelumnya
                            </button>

                            <div class="text-center">
                                <p id="pageInfo" class="text-sm font-bold text-gray-800">
                                    Slide 1
                                </p>
                                <p class="text-xs text-gray-500">
                                    Maksimal 10 produk per slide
                                </p>
                            </div>

                            <button type="button"
                                    id="nextSlide"
                                    class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-40">
                                Berikutnya →
                            </button>
                        </div>

                        {{-- GRID PRODUK --}}
                        <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                            @foreach ($products as $product)
                                <div class="product-card rounded-xl border border-gray-200 bg-white p-5 transition hover:border-orange-300 hover:shadow-md"
                                     data-product-index="{{ $loop->index }}">

                                    <div class="mb-4 flex items-start justify-between">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-xl">
                                            🍜
                                        </div>

                                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                            Aktif
                                        </span>
                                    </div>

                                    <h3 class="text-base font-bold text-gray-900">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="mt-1 text-xs text-gray-400">
                                        SKU: {{ $product->sku ?? '-' }}
                                    </p>

                                    <p class="mt-4 text-lg font-extrabold text-orange-600">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Stok tersedia: {{ $product->stock }}
                                    </p>

                                    <div class="mt-4 border-t border-gray-100 pt-4">
                                        <label for="qty_{{ $product->id }}"
                                               class="mb-2 block text-sm font-semibold text-gray-700">
                                            Jumlah Pesanan
                                        </label>

                                        <input
                                            type="number"
                                            id="qty_{{ $product->id }}"
                                            name="items[{{ $loop->index }}][quantity]"
                                            value="{{ old('items.'.$loop->index.'.quantity', 0) }}"
                                            min="0"
                                            max="{{ $product->stock }}"
                                            step="1"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            class="qty-input w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}
                                        >

                                        <input
                                            type="hidden"
                                            name="items[{{ $loop->index }}][product_id]"
                                            value="{{ $product->id }}"
                                        >
                                    </div>

                                    <p class="mt-3 text-xs font-medium text-gray-400">
                                        Subtotal:
                                        <span class="subtotal text-gray-700">
                                            Rp0
                                        </span>
                                    </p>
                                </div>
                            @endforeach

                        </div>

                        {{-- NAVIGASI BAWAH --}}
                        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-5 py-4">
                            <span id="bottomPageInfo"
                                  class="text-sm text-gray-500">
                                Slide 1
                            </span>

                            <div class="flex gap-2">
                                <button type="button"
                                        id="prevSlideBottom"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700">
                                    ←
                                </button>

                                <button type="button"
                                        id="nextSlideBottom"
                                        class="rounded-lg bg-orange-500 px-3 py-2 text-sm font-semibold text-white">
                                    →
                                </button>
                            </div>
                        </div>

                    @else
                        <div class="p-10 text-center">
                            <div class="text-5xl">📦</div>
                            <h3 class="mt-4 font-bold text-gray-800">
                                Belum Ada Produk
                            </h3>
                            <p class="mt-2 text-sm text-gray-500">
                                Tambahkan produk aktif melalui menu Produk terlebih dahulu.
                            </p>
                        </div>
                    @endif
                </section>

                {{-- PEMBAYARAN --}}
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                    <h2 class="mb-6 text-xl font-extrabold text-gray-900">
                        Pembayaran
                    </h2>

                    <div class="mb-6 rounded-xl bg-orange-50 p-5">
                        <p class="text-sm font-medium text-gray-600">
                            Total Belanja
                        </p>

                        <h2 id="totalBelanja"
                            class="mt-2 text-3xl font-extrabold text-orange-600">
                            Rp0
                        </h2>

                        <input type="hidden"
                               name="total"
                               id="totalInput"
                               value="0">
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>
                            <label for="payment_method"
                                   class="mb-2 block text-sm font-bold text-gray-700">
                                Metode Pembayaran
                            </label>

                            <select name="payment_method"
                                    id="payment_method"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">
                                <option value="tunai"
                                    {{ old('payment_method', 'tunai') === 'tunai' ? 'selected' : '' }}>
                                    Tunai
                                </option>

                                <option value="qris"
                                    {{ old('payment_method') === 'qris' ? 'selected' : '' }}>
                                    QRIS
                                </option>

                                <option value="transfer"
                                    {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>
                                    Transfer
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="paid_amount"
                                   class="mb-2 block text-sm font-bold text-gray-700">
                                Uang Dibayar (Rp)
                            </label>

                            <input type="number"
                                   name="paid_amount"
                                   id="paid_amount"
                                   value="{{ old('paid_amount', 0) }}"
                                   min="0"
                                   step="1"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500">

                            <p class="mt-2 text-xs text-gray-500">
                                Masukkan nominal yang dibayarkan pelanggan.
                            </p>
                        </div>

                    </div>

                    <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5">
                        <p class="text-sm font-medium text-gray-600">
                            Kembalian
                        </p>

                        <h3 id="kembalian"
                            class="mt-1 text-2xl font-extrabold text-green-600">
                            Rp0
                        </h3>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <button type="submit"
                                id="btnBayar"
                                class="rounded-xl bg-orange-500 px-6 py-4 font-bold text-white shadow-sm transition hover:bg-orange-600 disabled:opacity-50">
                            BAYAR / SIMPAN TRANSAKSI
                        </button>

                        <a href="{{ route('sales.index') }}"
                           class="rounded-xl border border-gray-300 bg-white px-6 py-4 text-center font-bold text-gray-700 transition hover:bg-gray-50">
                            Batal
                        </a>

                    </div>

                </section>

            </form>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const cards = Array.from(
                document.querySelectorAll('.product-card')
            );

            const inputs = document.querySelectorAll('.qty-input');

            const paidInput = document.getElementById('paid_amount');
            const totalElement = document.getElementById('totalBelanja');
            const totalInput = document.getElementById('totalInput');
            const changeElement = document.getElementById('kembalian');
            const form = document.getElementById('formTransaksi');
            const button = document.getElementById('btnBayar');

            const perSlide = 10;
            let currentSlide = 0;

            const totalSlides = Math.ceil(cards.length / perSlide);

            function rupiah(value) {
                return 'Rp' + Math.round(value).toLocaleString('id-ID');
            }

            // SISTEM SLIDE PRODUK
            function showSlide() {
                const start = currentSlide * perSlide;
                const end = start + perSlide;

                cards.forEach(function(card, index) {
                    card.style.display =
                        (index >= start && index < end) ? 'block' : 'none';
                });

                const info = `Slide ${currentSlide + 1} dari ${totalSlides}`;

                document.getElementById('pageInfo').textContent = info;
                document.getElementById('bottomPageInfo').textContent = info;

                const prevButtons = [
                    document.getElementById('prevSlide'),
                    document.getElementById('prevSlideBottom')
                ];

                const nextButtons = [
                    document.getElementById('nextSlide'),
                    document.getElementById('nextSlideBottom')
                ];

                prevButtons.forEach(btn => {
                    btn.disabled = currentSlide === 0;
                });

                nextButtons.forEach(btn => {
                    btn.disabled = currentSlide >= totalSlides - 1;
                });
            }

            function nextSlide() {
                if (currentSlide < totalSlides - 1) {
                    currentSlide++;
                    showSlide();
                }
            }

            function prevSlide() {
                if (currentSlide > 0) {
                    currentSlide--;
                    showSlide();
                }
            }

            document.getElementById('nextSlide')?.addEventListener('click', nextSlide);
            document.getElementById('nextSlideBottom')?.addEventListener('click', nextSlide);

            document.getElementById('prevSlide')?.addEventListener('click', prevSlide);
            document.getElementById('prevSlideBottom')?.addEventListener('click', prevSlide);

            // HITUNG TOTAL BELANJA
            function hitungTotal() {
                let total = 0;

                inputs.forEach(function(input) {
                    const qty = Math.max(0, parseInt(input.value, 10) || 0);
                    const price = Number(input.dataset.price) || 0;

                    const subtotal = qty * price;
                    total += subtotal;

                    const card = input.closest('.product-card');
                    const subtotalElement = card.querySelector('.subtotal');

                    subtotalElement.textContent = rupiah(subtotal);
                });

                totalElement.textContent = rupiah(total);
                totalInput.value = total;

                const dibayar = Math.max(0, Number(paidInput.value) || 0);

                changeElement.textContent = rupiah(
                    Math.max(0, dibayar - total)
                );
            }

            inputs.forEach(function(input) {
                input.addEventListener('input', hitungTotal);
            });

            paidInput.addEventListener('input', hitungTotal);

            // VALIDASI SEBELUM TRANSAKSI
            form.addEventListener('submit', function(event) {
                let total = 0;
                let adaProduk = false;
                let valid = true;

                inputs.forEach(function(input) {
                    const qty = Number(input.value) || 0;
                    const stock = Number(input.dataset.stock) || 0;
                    const price = Number(input.dataset.price) || 0;

                    if (
                        !Number.isInteger(qty) ||
                        qty < 0 ||
                        qty > stock
                    ) {
                        valid = false;
                    }

                    if (qty > 0) {
                        adaProduk = true;
                    }

                    total += qty * price;
                });

                if (!valid) {
                    event.preventDefault();
                    alert('Jumlah produk tidak valid atau melebihi stok.');
                    return;
                }

                if (!adaProduk) {
                    event.preventDefault();
                    alert('Pilih minimal satu produk terlebih dahulu.');
                    return;
                }

                const dibayar = Number(paidInput.value);

                if (!Number.isFinite(dibayar) || dibayar < total) {
                    event.preventDefault();
                    alert('Uang pembayaran belum mencukupi.');
                    return;
                }

                button.disabled = true;
                button.textContent = 'MENYIMPAN TRANSAKSI...';
            });

            showSlide();
            hitungTotal();

        });
    </script>
</x-app-layout>
