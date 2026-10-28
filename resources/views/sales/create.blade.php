
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi Penjualan
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
                    <strong>Transaksi gagal!</strong>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('sales.store') }}"
                  method="POST"
                  id="formTransaksi">
                @csrf

                <!-- BAGIAN 1: PILIH PRODUK -->
                <div class="bg-white shadow-sm rounded-xl p-6 mb-6">
                    <h3 class="text-xl font-bold mb-5">
                        1. Pilih Produk
                    </h3>

                    @forelse ($products as $product)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4
                                    items-center border-b border-gray-200 py-5">

                            <div class="md:col-span-2">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ $product->name }}
                                </h4>

                                <p class="text-gray-600 mt-1">
                                    Harga:
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </p>

                                <p class="text-gray-500">
                                    Stok: {{ $product->stock }}
                                </p>
                            </div>

                            <div>
                                <label for="qty_{{ $product->id }}"
                                       class="block font-medium text-gray-700 mb-2">
                                    Jumlah
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
                                    class="qty-input w-full rounded-lg border-gray-300 shadow-sm"
                                >

                                <input
                                    type="hidden"
                                    name="items[{{ $loop->index }}][product_id]"
                                    value="{{ $product->id }}"
                                >
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg bg-yellow-50 p-4 text-yellow-800">
                            Belum ada produk aktif. Tambahkan produk terlebih dahulu.
                        </div>
                    @endforelse
                </div>

                <!-- BAGIAN 2: PEMBAYARAN -->
                <div class="bg-white shadow-sm rounded-xl p-6 mb-6">
                    <h3 class="text-xl font-bold mb-6">
                        2. Pembayaran
                    </h3>

                    <!-- TOTAL -->
                    <div class="mb-6">
                        <p class="text-gray-600">Total Belanja</p>
                        <h2 id="totalBelanja"
                            class="text-3xl font-bold text-indigo-600">
                            Rp0
                        </h2>

                        <input type="hidden"
                               name="total"
                               id="totalInput"
                               value="0">
                    </div>

                    <!-- METODE PEMBAYARAN -->
                    <div class="mb-5">
                        <label for="payment_method"
                               class="block font-semibold mb-2">
                            Metode Pembayaran
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm p-3">

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

                    <!-- UANG DIBAYAR -->
                    <div class="mb-5">
                        <label for="paid_amount"
                               class="block font-semibold mb-2">
                            Uang Dibayar (Rp)
                        </label>

                        <input
                            type="number"
                            name="paid_amount"
                            id="paid_amount"
                            value="{{ old('paid_amount', 0) }}"
                            min="0"
                            step="1"
                            required
                            class="w-full rounded-lg border-gray-300 shadow-sm p-3"
                        >

                        <p class="text-sm text-gray-500 mt-2">
                            Untuk QRIS atau transfer, masukkan nominal yang sudah dibayarkan.
                        </p>
                    </div>

                    <!-- KEMBALIAN -->
                    <div class="mb-8">
                        <p class="text-gray-600">Kembalian</p>
                        <h3 id="kembalian"
                            class="text-2xl font-bold text-green-600">
                            Rp0
                        </h3>
                    </div>

                    <!-- TOMBOL BAYAR -->
                    <button
                        type="submit"
                        id="btnBayar"
                        style="display:block;width:100%;padding:16px;
                               background-color:#16a34a;color:#ffffff;
                               border:none;border-radius:10px;
                               font-size:18px;font-weight:bold;
                               cursor:pointer;margin-bottom:12px;">
                        BAYAR / SIMPAN TRANSAKSI
                    </button>

                    <!-- TOMBOL BATAL -->
                    <a
                        href="{{ route('sales.index') }}"
                        style="display:block;width:100%;padding:14px;
                               background-color:#ffffff;color:#374151;
                               border:1px solid #d1d5db;border-radius:10px;
                               text-align:center;text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    <!-- PERHITUNGAN TRANSAKSI -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.qty-input');
            const paidInput = document.getElementById('paid_amount');
            const totalElement = document.getElementById('totalBelanja');
            const totalInput = document.getElementById('totalInput');
            const changeElement = document.getElementById('kembalian');
            const form = document.getElementById('formTransaksi');
            const button = document.getElementById('btnBayar');

            function rupiah(value) {
                return 'Rp' + Math.round(value).toLocaleString('id-ID');
            }

            function hitungTotal() {
                let total = 0;

                inputs.forEach(function (input) {
                    const qty = Math.max(
                        0,
                        parseInt(input.value, 10) || 0
                    );

                    const price = Number(input.dataset.price) || 0;
                    total += qty * price;
                });

                totalElement.textContent = rupiah(total);
                totalInput.value = total;

                const dibayar = Math.max(
                    0,
                    Number(paidInput.value) || 0
                );

                changeElement.textContent = rupiah(
                    Math.max(0, dibayar - total)
                );
            }

            inputs.forEach(function (input) {
                input.addEventListener('input', hitungTotal);
            });

            paidInput.addEventListener('input', hitungTotal);

            form.addEventListener('submit', function (event) {
                let total = 0;
                let adaProduk = false;
                let valid = true;

                inputs.forEach(function (input) {
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
                    alert('Jumlah barang tidak valid atau melebihi stok.');
                    return;
                }

                if (!adaProduk) {
                    event.preventDefault();
                    alert('Pilih minimal satu produk terlebih dahulu.');
                    return;
                }

                const dibayar = Number(paidInput.value);

                if (
                    !Number.isFinite(dibayar) ||
                    dibayar < total
                ) {
                    event.preventDefault();
                    alert('Uang dibayar belum mencukupi total belanja.');
                    return;
                }

                button.disabled = true;
                button.textContent = 'MENYIMPAN TRANSAKSI...';
            });

            hitungTotal();
        });
    </script>
</x-app-layout>
