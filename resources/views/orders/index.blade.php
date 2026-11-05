
<x-app-layout>
    <div class="wp-app">
        <div class="wp-main">
            <div class="wp-page-header">
                <div>
                    <h1>Pesanan QR Meja</h1>
                    <p>Kelola pesanan pelanggan yang masuk melalui QR Code meja.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="wp-alert wp-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="wp-alert wp-alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="wp-order-summary">
                <div class="wp-order-card">
                    <span>Menunggu</span>
                    <strong>{{ $waitingCount }}</strong>
                </div>

                <div class="wp-order-card">
                    <span>Diproses</span>
                    <strong>{{ $processingCount }}</strong>
                </div>

                <div class="wp-order-card">
                    <span>Selesai</span>
                    <strong>{{ $completedCount }}</strong>
                </div>
            </div>

            <div class="wp-order-panel">
                <div class="wp-order-panel-header">
                    <h2>Daftar Pesanan Masuk</h2>
                    <span>{{ $orders->total() }} pesanan</span>
                </div>

                @forelse ($orders as $order)
                    <div class="wp-order-item">
                        <div class="wp-order-top">
                            <div>
                                <strong>{{ $order->order_number }}</strong>
                                <small>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>

                            @if ($order->status === 'menunggu')
                                <span class="wp-status waiting">Menunggu</span>
                            @elseif ($order->status === 'diproses')
                                <span class="wp-status processing">Diproses</span>
                            @else
                                <span class="wp-status completed">Selesai</span>
                            @endif
                        </div>

                        <div class="wp-order-customer">
                            <div>
                                <small>Nama pelanggan</small>
                                <strong>{{ $order->customer_name }}</strong>
                            </div>

                            <div>
                                <small>Nomor meja</small>
                                <strong>
                                    {{ $order->diningTable->table_number ?? '-' }}
                                </strong>
                            </div>

                            <div>
                                <small>Jumlah jenis menu</small>
                                <strong>{{ $order->items->count() }} menu</strong>
                            </div>
                        </div>

                        <div class="wp-order-bottom">
                            <span>
                                {{ $order->items->sum('quantity') }} item dipesan
                            </span>

                            <a href="{{ route('orders.show', $order) }}"
                               class="wp-order-button">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="wp-order-empty">
                        <div class="wp-order-empty-icon">🛒</div>
                        <h3>Belum Ada Pesanan</h3>
                        <p>Pesanan pelanggan melalui QR Code akan muncul di sini.</p>
                    </div>
                @endforelse

                <div class="wp-order-pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .wp-order-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin: 24px 0;
        }

        .wp-order-card, .wp-order-panel {
            background: #fff;
            border: 1px solid #e8e9ed;
            border-radius: 14px;
            padding: 20px;
        }

        .wp-order-card span, .wp-order-customer small {
            display: block;
            color: #777;
            font-size: 13px;
        }

        .wp-order-card strong {
            display: block;
            font-size: 28px;
            margin-top: 8px;
        }

        .wp-order-panel-header, .wp-order-top,
        .wp-order-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .wp-order-panel-header {
            margin-bottom: 18px;
        }

        .wp-order-panel-header h2 {
            font-size: 18px;
            font-weight: 700;
        }

        .wp-order-panel-header span,
        .wp-order-top small {
            color: #777;
            font-size: 12px;
        }

        .wp-order-item {
            border: 1px solid #e9e9ed;
            border-radius: 12px;
            padding: 17px;
            margin-bottom: 14px;
        }

        .wp-order-top strong {
            display: block;
            font-size: 15px;
        }

        .wp-order-top small {
            display: block;
            margin-top: 5px;
        }

        .wp-order-customer {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            padding: 17px 0;
        }

        .wp-order-customer strong {
            display: block;
            margin-top: 5px;
            font-size: 14px;
        }

        .wp-status {
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .wp-status.waiting {
            color: #a16207;
            background: #fef3c7;
        }

        .wp-status.processing {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .wp-status.completed {
            color: #15803d;
            background: #dcfce7;
        }

        .wp-order-bottom {
            border-top: 1px solid #eee;
            padding-top: 14px;
            font-size: 13px;
            color: #666;
        }

        .wp-order-button {
            display: inline-block;
            padding: 9px 15px;
            background: #166534;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .wp-order-empty {
            text-align: center;
            padding: 55px 15px;
        }

        .wp-order-empty-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        .wp-order-empty h3 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .wp-order-empty p {
            color: #777;
            font-size: 13px;
        }

        .wp-order-pagination {
            margin-top: 20px;
        }

        .wp-alert {
            padding: 12px 16px;
            border-radius: 9px;
            margin-top: 15px;
        }

        .wp-alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .wp-alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 650px) {
            .wp-order-summary {
                grid-template-columns: 1fr;
            }

            .wp-order-customer {
                grid-template-columns: 1fr 1fr;
            }

            .wp-order-top, .wp-order-bottom {
                align-items: flex-start;
            }
        }
    </style>
</x-app-layout>
