
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi Kasir Warmindo
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-5 flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Kembali ke Dashboard
                </a>

                <a href="{{ route('sales.index') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Riwayat Transaksi
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 rounded-lg p-4 mb-5">
                    <p class="font-semibold mb-2">Transaksi belum berhasil:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sales.store') }}" method="POST"
                  id="saleForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold mb-4">Pilih Produk</h3>

                        @if ($products->isEmpty())
                            <p class="text-gray-600">
                                Belum ada produk aktif. Minta admin menambahkan produk terlebih dahulu.
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-3 text-left">Produk</th>
                                            <th class="p-3 text-right">Harga</th>
                                            <th class="p-3 text-right">Stok</th>
                                            <th class="p-3 text-center">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr class="border-b">
                                                <td class="p-3">
                                                    {{ $product->name }}
                                                </td>
                                                <td class="p-3 text-right">
                                                    Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                                </td>
                                                <td class="p-3 text-right">
                                                    {{ $product->stock }}
                                                </td>
                                                <td class="p-3 text-center">
                                                    <input
                                                        type="number"
                                                        name="items[{{ $product->id }}][quantity]"
                                                        class="quantity-input w-20 rounded-lg border-gray-300 text-center"
                                                        min="0"
                                                        max="{{ $product->stock }}"
                                                        value="{{ old('items.'.$product->id.'.quantity', 0) }}"
                                                        data-id="{{ $product->id }}"
                                                        data-price="{{ $product->price }}"
                                                        data-name="{{ $product->name }}"
                                                        data-stock="{{ $product->stock }}"
                                                        aria-label="Jumlah {{ $product->name }}"
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="items[{{ $product->id }}][product_id]"
                                                        value="{{ $product->id }}"
                                                    >
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold mb-4">Pembayaran</h3>

                        <div class="mb-5">
                            <label class="block text-sm font-medium mb-2">
                                Total Belanja
                            </label>
                            <p id="totalDisplay"
                               class="text-2xl font-bold text-blue-700">
                                Rp 0
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="paid_amount"
                                   class="block text-sm font-medium mb-2">
                                Uang Dibayar (Rp)
                            </label>
                            <input
                                type="number"
                                name="paid_amount"
                                id="paid_amount"
                                class="w-full rounded-lg border-gray-300"
                                min="0"
                                step="1"
                                value="{{ old('paid_amount', 0) }}"
                                required
                            >
                        </div>

                        <div class="mb-5">
                            <label for="payment_method"
                                   class="block text-sm font-medium mb-2">
                                Metode Pembayaran
                            </label>
                            <select
                                name="payment_method"
                                id="payment_method"
                                class="w-full rounded-lg border-gray-300"
                                required
                            >
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <div class="bg-gray-100 rounded-lg p-4 mb-5">
                            <p class="text-sm text-gray-600">Perkiraan Kembalian</p>
                            <p id="changeDisplay"
                               class="text-xl font-bold text-green-700">
                                Rp 0
                            </p>
                        </div>

                        <button
                            type="submit"
                            id="submitButton"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg"
                        >
                            Proses Transaksi
                        </button>

                        <p class="text-xs text-gray-500 mt-3">
                            Pastikan produk dan jumlahnya benar sebelum memproses transaksi.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const paidInput = document.getElementById('paid_amount');
        const totalDisplay = document.getElementById('totalDisplay');
        const changeDisplay = document.getElementById('changeDisplay');
        const saleForm = document.getElementById('saleForm');
        const submitButton = document.getElementById('submitButton');

        const rupiah = (amount) =>
            'Rp ' + Math.max(0, amount).toLocaleString('id-ID');

        function calculateTotal() {
            let total = 0;

            quantityInputs.forEach((input) => {
                const quantity = Math.max(0, parseInt(input.value || 0));
                const price = Number(input.dataset.price);

                total += quantity * price;
            });

            const paid = Number(paidInput.value || 0);

            totalDisplay.textContent = rupiah(total);
            changeDisplay.textContent = rupiah(paid - total);

            return total;
        }

        quantityInputs.forEach((input) => {
            input.addEventListener('input', calculateTotal);
        });

        paidInput.addEventListener('input', calculateTotal);

        saleForm.addEventListener('submit', (event) => {
            const selected = [...quantityInputs].filter(
                input => Number(input.value) > 0
            );

            if (selected.length === 0) {
                event.preventDefault();
                alert('Pilih minimal satu produk terlebih dahulu.');
                return;
            }

            for (const input of selected) {
                const quantity = Number(input.value);
                const stock = Number(input.dataset.stock);

                if (!Number.isInteger(quantity) || quantity > stock) {
                    event.preventDefault();
                    alert('Jumlah ' + input.dataset.name +
                          ' tidak valid atau melebihi stok.');
                    return;
                }
            }

            if (Number(paidInput.value) < calculateTotal()) {
                event.preventDefault();
                alert('Uang pembayaran masih kurang.');
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Memproses...';
        });

        calculateTotal();
    </script>
</x-app-layout>
