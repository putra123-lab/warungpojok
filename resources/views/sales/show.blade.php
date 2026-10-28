
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Warmindo</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100">

    <div class="mx-auto max-w-5xl px-4 py-8">

        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Detail Transaksi
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi lengkap transaksi Warmindo
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('sales.index') }}"
                   class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">
                    Kembali
                </a>

                <a href="{{ route('sales.receipt', $sale->id) }}"
                   target="_blank"
                   rel="noopener"
                   class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                    🧾 Cetak Struk
                </a>
            </div>
        </div>

        <!-- Informasi transaksi -->
        <div class="mb-6 rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-slate-800">
                Informasi Transaksi
            </h2>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Nomor Invoice</p>
                    <p class="font-semibold text-slate-800">
                        {{ $sale->invoice_number ?? 'INV-' . $sale->id }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Tanggal Transaksi</p>
                    <p class="font-semibold text-slate-800">
                        {{ $sale->created_at
                            ? \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i')
                            : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Kasir</p>
                    <p class="font-semibold text-slate-800">
                        {{ $sale->user->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-slate-500">Metode Pembayaran</p>
                    <p class="font-semibold text-slate-800">
                        {{ strtoupper($sale->payment_method ?? '-') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Daftar produk -->
        <div class="mb-6 overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="border-b border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800">
                    Produk yang Dibeli
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-3">No.</th>
                            <th class="px-6 py-3">Nama Produk</th>
                            <th class="px-6 py-3 text-right">Harga</th>
                            <th class="px-6 py-3 text-center">Jumlah</th>
                            <th class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($sale->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4 font-medium text-slate-800">
                                    {{ $item->product_name
                                        ?? $item->product->name
                                        ?? 'Produk' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    Rp {{ number_format((float) $item->price, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="px-6 py-4 text-right font-semibold">
                                    Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-8 text-center text-slate-500">
                                    Belum ada detail produk untuk transaksi ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ringkasan pembayaran -->
        <div class="ml-auto max-w-md rounded-xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-slate-800">
                Ringkasan Pembayaran
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-slate-500">Total Pembayaran</span>
                    <span class="font-bold text-slate-800">
                        Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-slate-500">Uang Dibayar</span>
                    <span class="font-semibold text-slate-800">
                        Rp {{ number_format((float) $sale->paid_amount, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between gap-4 border-t border-slate-200 pt-3 text-sm">
                    <span class="text-slate-500">Kembalian</span>
                    <span class="font-bold text-green-700">
                        Rp {{ number_format((float) $sale->change_amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <a href="{{ route('sales.receipt', $sale->id) }}"
               target="_blank"
               rel="noopener"
               class="mt-6 block w-full rounded-lg bg-green-600 px-4 py-3 text-center font-semibold text-white hover:bg-green-700">
                🧾 Cetak Struk Pembayaran
            </a>
        </div>

    </div>

</body>
</html>
