
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Stok Masuk & Kedaluwarsa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                WARUNG POJOK — Kelola stok produk dan bahan baku
            </p>
        </div>
    </x-slot>

    <div class="wp-stock-page">
        <div class="wp-stock-container">

            @if (session('success'))
                <div class="wp-alert wp-alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="wp-alert wp-alert-error">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="wp-stock-card">
                <div class="wp-card-heading">
                    <div class="wp-heading-icon">📦</div>
                    <div>
                        <h3>Tambah Stok Masuk</h3>
                        <p>Catat jumlah stok baru dan tanggal kedaluwarsa barang.</p>
                    </div>
                </div>

                <form action="{{ route('stock-batches.store') }}" method="POST">
                    @csrf

                    <div class="wp-form-grid">
                        <div class="wp-field">
                            <label for="type">Jenis stok</label>
                            <select id="type" name="type" required>
                                <option value="product"
                                    {{ old('type', 'product') === 'product' ? 'selected' : '' }}>
                                    Produk
                                </option>
                                <option value="ingredient"
                                    {{ old('type') === 'ingredient' ? 'selected' : '' }}>
                                    Bahan Baku
                                </option>
                            </select>
                        </div>

                        <div class="wp-field">
                            <label for="item_id">Nama barang</label>
                            <select id="item_id" name="item_id" required>
                                <option value="">-- Pilih barang --</option>

                                <optgroup label="Produk">
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-type="product"
                                            {{ old('type', 'product') === 'product' && old('item_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </optgroup>

                                <optgroup label="Bahan Baku">
                                    @foreach ($ingredients as $ingredient)
                                        <option
                                            value="{{ $ingredient->id }}"
                                            data-type="ingredient"
                                            {{ old('type') === 'ingredient' && old('item_id') == $ingredient->id ? 'selected' : '' }}>
                                            {{ $ingredient->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="wp-field">
                            <label for="quantity">Jumlah stok masuk</label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                min="0.01"
                                max="999999999"
                                step="0.01"
                                value="{{ old('quantity') }}"
                                placeholder="Masukkan jumlah"
                                required>
                        </div>

                        <div class="wp-field">
                            <label for="received_date">Tanggal stok masuk</label>
                            <input
                                type="date"
                                id="received_date"
                                name="received_date"
                                value="{{ old('received_date', date('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="wp-field">
                            <label for="expiry_date">Tanggal kedaluwarsa</label>
                            <input
                                type="date"
                                id="expiry_date"
                                name="expiry_date"
                                min="{{ old('received_date', date('Y-m-d')) }}"
                                value="{{ old('expiry_date') }}">
                            <small>Boleh dikosongkan jika tanggal belum diketahui.</small>
                        </div>
                    </div>

                    <div class="wp-form-footer">
                        <button type="submit" class="wp-btn-save">
                            <span>▣</span> Simpan Stok Masuk
                        </button>
                    </div>
                </form>
            </section>

            <section class="wp-stock-card">
                <div class="wp-card-heading">
                    <div class="wp-heading-icon">📦</div>
                    <div>
                        <h3>Batch Produk</h3>
                        <p>Daftar stok produk berdasarkan tanggal masuk dan kedaluwarsa.</p>
                    </div>
                </div>

                <div class="wp-table-wrap">
                    <table class="wp-stock-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Tanggal masuk</th>
                                <th>Kedaluwarsa</th>
                                <th class="text-right">Jumlah awal</th>
                                <th class="text-right">Sisa stok batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productBatches as $batch)
                                @php
                                    $expired = $batch->expiry_date &&
                                        $batch->expiry_date->lt(today());

                                    $expiringSoon = $batch->expiry_date &&
                                        !$expired &&
                                        $batch->expiry_date->lte(today()->addDays(30));
                                @endphp
                                <tr>
                                    <td class="wp-item-name">
                                        {{ $batch->product->name ?? 'Produk dihapus' }}
                                    </td>
                                    <td>{{ $batch->received_date?->format('d/m/Y') ?? '-' }}</td>
                                    <td>{{ $batch->expiry_date?->format('d/m/Y') ?? 'Belum diketahui' }}</td>
                                    <td class="text-right">
                                        {{ number_format((float) $batch->initial_quantity, 2, ',', '.') }}
                                    </td>
                                    <td class="text-right wp-quantity">
                                        {{ number_format((float) $batch->remaining_quantity, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($expired)
                                            <span class="wp-status status-danger">Kedaluwarsa</span>
                                        @elseif ($expiringSoon)
                                            <span class="wp-status status-warning">Segera kedaluwarsa</span>
                                        @elseif (!$batch->expiry_date)
                                            <span class="wp-status status-unknown">Belum diketahui</span>
                                        @else
                                            <span class="wp-status status-safe">Belum dekat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="wp-empty-cell">
                                        <div class="wp-empty-icon">📦</div>
                                        <strong>Belum ada batch produk</strong>
                                        <p>Data akan muncul setelah stok produk disimpan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="wp-stock-card">
                <div class="wp-card-heading">
                    <div class="wp-heading-icon">🧂</div>
                    <div>
                        <h3>Batch Bahan Baku</h3>
                        <p>Pantau persediaan bahan baku dan tanggal kedaluwarsanya.</p>
                    </div>
                </div>

                <div class="wp-table-wrap">
                    <table class="wp-stock-table">
                        <thead>
                            <tr>
                                <th>Bahan baku</th>
                                <th>Tanggal masuk</th>
                                <th>Kedaluwarsa</th>
                                <th class="text-right">Jumlah awal</th>
                                <th class="text-right">Sisa stok batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ingredientBatches as $batch)
                                @php
                                    $expired = $batch->expiry_date &&
                                        $batch->expiry_date->lt(today());

                                    $expiringSoon = $batch->expiry_date &&
                                        !$expired &&
                                        $batch->expiry_date->lte(today()->addDays(30));
                                @endphp
                                <tr>
                                    <td class="wp-item-name">
                                        {{ $batch->ingredient->name ?? 'Bahan baku dihapus' }}
                                    </td>
                                    <td>{{ $batch->received_date?->format('d/m/Y') ?? '-' }}</td>
                                    <td>{{ $batch->expiry_date?->format('d/m/Y') ?? 'Belum diketahui' }}</td>
                                    <td class="text-right">
                                        {{ number_format((float) $batch->initial_quantity, 2, ',', '.') }}
                                    </td>
                                    <td class="text-right wp-quantity">
                                        {{ number_format((float) $batch->remaining_quantity, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($expired)
                                            <span class="wp-status status-danger">Kedaluwarsa</span>
                                        @elseif ($expiringSoon)
                                            <span class="wp-status status-warning">Segera kedaluwarsa</span>
                                        @elseif (!$batch->expiry_date)
                                            <span class="wp-status status-unknown">Belum diketahui</span>
                                        @else
                                            <span class="wp-status status-safe">Belum dekat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="wp-empty-cell">
                                        <div class="wp-empty-icon">🧂</div>
                                        <strong>Belum ada batch bahan baku</strong>
                                        <p>Data akan muncul setelah stok bahan baku disimpan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>

    <style>
        /*
         * Semua selector dibatasi pada halaman stok agar
         * tidak mengubah tampilan halaman lain.
         */
        .wp-stock-page {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            min-height: 100vh;
            margin: 0;
            padding: 24px 28px 40px 24px;
            box-sizing: border-box;
            background: #eeece8;
            color: #283640;
            overflow-x: clip;
        }

        .wp-stock-page *,
        .wp-stock-page *::before,
        .wp-stock-page *::after {
            box-sizing: border-box;
        }

        .wp-stock-container {
            display: block;
            width: 100%;
            max-width: none;
            min-width: 0;
            margin: 0;
            padding: 0;
        }

        .wp-stock-card {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            margin: 0 0 22px;
            padding: 24px;
            background: #faf8f3;
            border: 1px solid #ded9d0;
            border-radius: 13px;
            box-shadow: 0 4px 12px rgba(49, 39, 29, .06);
        }

        .wp-card-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 0 0 23px;
        }

        .wp-heading-icon {
            display: flex;
            flex: 0 0 46px;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 11px;
            background: #ffead8;
            font-size: 22px;
        }

        .wp-card-heading h3 {
            margin: 0;
            color: #25313b;
            font-size: 19px;
            line-height: 1.4;
            font-weight: 700;
        }

        .wp-card-heading p {
            margin: 4px 0 0;
            color: #737b80;
            font-size: 13px;
            line-height: 1.5;
        }

        .wp-form-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 19px 16px;
            width: 100%;
            min-width: 0;
        }

        .wp-field {
            min-width: 0;
        }

        .wp-field label {
            display: block;
            margin-bottom: 8px;
            color: #394650;
            font-size: 13px;
            font-weight: 700;
        }

        .wp-field input,
        .wp-field select {
            display: block;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            height: 43px;
            margin: 0;
            padding: 0 12px;
            border: 1px solid #d5d3cc;
            border-radius: 8px;
            background: #f4f2ed;
            color: #283640;
            font-family: inherit;
            font-size: 14px;
        }

        .wp-field input:focus,
        .wp-field select:focus {
            outline: none;
            border-color: #e76b2e;
            box-shadow: 0 0 0 3px rgba(231, 107, 46, .13);
        }

        .wp-field small {
            display: block;
            margin-top: 7px;
            color: #7b8184;
            font-size: 12px;
            line-height: 1.5;
        }

        .wp-form-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .wp-btn-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 42px;
            max-width: 100%;
            padding: 0 19px;
            border: 0;
            border-radius: 8px;
            background: #176b3a;
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
        }

        .wp-btn-save:hover {
            background: #10572e;
        }

        .wp-table-wrap {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: auto;
            border: 1px solid #e0dcd4;
            border-radius: 9px;
        }

        .wp-stock-table {
            width: 100%;
            min-width: 760px;
            margin: 0;
            border-collapse: collapse;
            font-size: 13px;
        }

        .wp-stock-table th {
            padding: 13px 14px;
            background: #eae7e0;
            color: #4b555c;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            border-bottom: 1px solid #dcd7ce;
        }

        .wp-stock-table td {
            padding: 14px;
            border-bottom: 1px solid #e8e4dc;
            color: #5c6469;
            vertical-align: middle;
        }

        .wp-stock-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .wp-stock-table tbody tr:hover {
            background: #f4f0e8;
        }

        .wp-stock-table .wp-item-name,
        .wp-stock-table .wp-quantity {
            color: #293640;
            font-weight: 700;
        }

        .wp-stock-page .text-right {
            text-align: right !important;
        }

        .wp-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-danger {
            color: #a82f2f;
            background: #fbe4e1;
        }

        .status-warning {
            color: #8a5b08;
            background: #f9edc9;
        }

        .status-unknown {
            color: #62686d;
            background: #e9e7e2;
        }

        .status-safe {
            color: #216740;
            background: #deeee2;
        }

        .wp-empty-cell {
            padding: 36px 16px !important;
            text-align: center !important;
            background: #f8f6f1;
        }

        .wp-empty-icon {
            margin-bottom: 9px;
            font-size: 29px;
        }

        .wp-empty-cell strong {
            display: block;
            color: #515b61;
            font-size: 13px;
        }

        .wp-empty-cell p {
            margin: 5px 0 0;
            color: #858b8e;
            font-size: 12px;
        }

        .wp-alert {
            margin: 0 0 20px;
            padding: 15px 17px;
            border-radius: 9px;
            font-size: 14px;
        }

        .wp-alert-success {
            color: #1d6338;
            background: #e0efe3;
            border: 1px solid #c5dfcb;
        }

        .wp-alert-error {
            color: #962f2f;
            background: #f9e7e4;
            border: 1px solid #efcfca;
        }

        .wp-alert ul {
            margin: 8px 0 0;
            padding-left: 20px;
            list-style: disc;
        }

        .wp-alert li {
            margin-bottom: 4px;
        }

        @media (max-width: 1100px) {
            .wp-stock-page {
                padding-right: 20px;
                padding-left: 20px;
            }

            .wp-form-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .wp-stock-page {
                padding: 16px 14px 28px;
            }

            .wp-stock-card {
                padding: 17px 14px;
                margin-bottom: 16px;
            }

            .wp-card-heading {
                align-items: flex-start;
                gap: 11px;
            }

            .wp-heading-icon {
                width: 40px;
                height: 40px;
                flex-basis: 40px;
                font-size: 19px;
            }

            .wp-card-heading h3 {
                font-size: 17px;
            }

            .wp-form-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 17px;
            }

            .wp-form-footer {
                justify-content: stretch;
            }

            .wp-btn-save {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const itemSelect = document.getElementById('item_id');
            const expiryInput = document.getElementById('expiry_date');
            const receivedInput = document.getElementById('received_date');

            function filterItems() {
                const type = typeSelect.value;
                const options = itemSelect.querySelectorAll('option[data-type]');
                const groups = itemSelect.querySelectorAll('optgroup');

                options.forEach(function (option) {
                    option.hidden = option.dataset.type !== type;
                });

                groups.forEach(function (group) {
                    group.hidden = !Array.from(group.querySelectorAll('option'))
                        .some(option => !option.hidden);
                });

                const selected = itemSelect.options[itemSelect.selectedIndex];

                if (selected && selected.dataset.type && selected.dataset.type !== type) {
                    itemSelect.value = '';
                }
            }

            function updateExpiryMinimum() {
                expiryInput.min = receivedInput.value || '';
            }

            typeSelect.addEventListener('change', filterItems);
            receivedInput.addEventListener('change', updateExpiryMinimum);

            filterItems();
            updateExpiryMinimum();
        });
    </script>
</x-app-layout>
