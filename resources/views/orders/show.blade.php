
<x-app-layout>
    <div class="wp-app">
        <div class="wp-main">
            <div class="wp-page-header">
                <div>
                    <h1>Detail Pesanan QR</h1>
                    <p>Periksa pesanan pelanggan sebelum diproses.</p>
                </div>

                <a href="{{ route('orders.index') }}"
                   class="wp-back-button">
                    Kembali ke Pesanan
                </a>
            </div>

            @if (session('success'))
                <div class="wp-alert success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="wp-alert error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="wp-detail-grid">
                <section class="wp-detail-card">
                    <h2>Informasi Pesanan</h2>

                    <div class="wp-info-row">
                        <span>Nomor Pesanan</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>

                    <div class="wp-info-row">
                        <span>Nama Pelanggan</span>
                        <strong>{{ $order->customer_name }}</strong>
                    </div>

                    <div class="wp-info-row">
                        <span>Nomor Meja</span>
                        <strong>{{ $order->diningTable->table_number ?? '-' }}</strong>
                    </div>

                    <div class="wp-info-row">
                        <span>Waktu Pesanan</span>
                        <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                    </div>

                    <div class="wp-info-row">
                        <span>Status</span>
                        <strong>{{ ucfirst($order->status) }}</strong>
                    </div>

                    @if ($order->notes)
                        <div class="wp-notes">
                            <span>Catatan Pelanggan</span>
                            <p>{{ $order->notes }}</p>
                        </div>
                    @endif
                </section>

                <section class="wp-detail-card">
                    <h2>Daftar Menu</h2>

                    <div class="wp-table-wrapper">
                        <table class="wp-items-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            Rp {{ number_format((float) $item->price, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="3">Total Pesanan</th>
                                    <th>
                                        Rp {{ number_format((float) $order->items->sum('subtotal'), 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($order->sale)
                        <div class="wp-payment-info">
                            <strong>Pesanan sudah dibayar.</strong>
                            <p>Nomor transaksi: {{ $order->sale->invoice_number }}</p>
                        </div>
                    @endif

                    @if ($order->status === 'menunggu' && !$order->sale_id)
                        <form method="POST"
                              action="{{ route('orders.update-status', $order) }}"
                              onsubmit="return confirm('Mulai proses pesanan ini?')"
                              class="wp-process-form">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="status" value="diproses">

                            <button type="submit" class="wp-process-button">
                                Mulai Proses Pesanan
                            </button>
                        </form>
                    @elseif ($order->status === 'diproses' && !$order->sale_id)
                        <div class="wp-processing-info">
                            Pesanan sedang diproses. Pembayaran dapat dilanjutkan
                            melalui tahap integrasi kasir berikutnya.
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <style>
        .wp-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 20px;
            margin-top: 24px;
        }

        .wp-detail-card {
            background: #fff;
            border: 1px solid #e8e9ed;
            border-radius: 14px;
            padding: 22px;
            min-width: 0;
        }

        .wp-detail-card h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .wp-info-row {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .wp-info-row span, .wp-notes span {
            color: #777;
            font-size: 12px;
        }

        .wp-info-row strong {
            font-size: 14px;
        }

        .wp-notes {
            margin-top: 17px;
            padding: 13px;
            border-radius: 9px;
            background: #f8fafc;
        }

        .wp-notes p {
            margin-top: 7px;
            font-size: 14px;
        }

        .wp-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .wp-items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .wp-items-table th, .wp-items-table td {
            padding: 12px 9px;
            text-align: left;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
        }

        .wp-items-table thead {
            background: #f8fafc;
        }

        .wp-items-table tfoot th {
            background: #f8fafc;
            font-weight: 700;
        }

        .wp-process-form {
            margin-top: 22px;
        }

        .wp-process-button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 9px;
            background: #166534;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .wp-process-button:hover {
            background: #14532d;
        }

        .wp-processing-info, .wp-payment-info {
            margin-top: 20px;
            padding: 14px;
            border-radius: 9px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 13px;
        }

        .wp-payment-info {
            background: #dcfce7;
            color: #166534;
        }

        .wp-back-button {
            display: inline-block;
            padding: 9px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            color: #333;
            text-decoration: none;
            font-size: 13px;
        }

        .wp-alert {
            padding: 12px 16px;
            border-radius: 9px;
            margin-top: 15px;
        }

        .wp-alert.success {
            background: #dcfce7;
            color: #166534;
        }

        .wp-alert.error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 800px) {
            .wp-detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>
