
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Transaksi dan Struk
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 rounded-lg p-4 mb-5">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6" id="receipt">
                <div class="text-center border-b pb-4 mb-4">
                    <h1 class="text-2xl font-bold">WARMINDO</h1>
                    <p class="text-sm text-gray-500">Struk Pembelian</p>
                </div>

                <div class="text-sm space-y-1 mb-5">
                    <p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
                    <p><strong>Tanggal:</strong> {{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Kasir:</strong> {{ $sale->user->name }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2 text-left">Produk</th>
                                <th class="py-2 text-right">Qty</th>
                                <th class="py-2 text-right">Harga</th>
                                <th class="py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $item)
                                <tr class="border-b">
                                    <td class="py-2">{{ $item->product_name }}</td>
                                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                                    <td class="py-2 text-right">
                                        {{ number_format((float) $item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-right">
                                        {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 border-t pt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total</span>
                        <strong>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Pembayaran</span>
                        <span>Rp {{ number_format((float) $sale->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kembalian</span>
                        <strong>Rp {{ number_format((float) $sale->change_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Metode</span>
                        <span>{{ strtoupper($sale->payment_method) }}</span>
                    </div>
                </div>

                <p class="text-center text-sm mt-6">
                    Terima kasih sudah berbelanja!
                </p>
            </div>

            <div class="flex flex-wrap gap-3 mt-5 print:hidden">
                <button onclick="window.print()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Cetak Struk
                </button>

                <a href="{{ route('sales.create') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Transaksi Baru
                </a>

                <a href="{{ route('sales.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Riwayat
                </a>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #receipt, #receipt * {
                visibility: visible;
            }

            #receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }

            nav, header, .print\:hidden {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
