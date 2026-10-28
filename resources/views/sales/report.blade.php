```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Penjualan - WARUNG POJOK</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --orange: #f97316;
            --orange-dark: #ea580c;
            --black: #171717;
            --cream: #fff7ed;
            --muted: #737373;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--black);
            background: #fafafa;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        .topbar {
            background: var(--black);
            color: white;
            border-bottom: 4px solid var(--orange);
        }

        .topbar-inner {
            max-width: 1440px;
            margin: auto;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background: var(--orange);
            font-size: 18px;
            font-weight: 900;
        }

        .page {
            max-width: 1440px;
            margin: 0 auto;
            padding: 32px 28px 48px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 28px;
        }

        .eyebrow {
            color: var(--orange-dark);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-title {
            margin: 4px 0;
            font-size: clamp(26px, 3vw, 34px);
            font-weight: 900;
            line-height: 1.25;
        }

        .page-description {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 17px;
            border: 0;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-orange {
            background: var(--orange);
            color: white;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
        }

        .btn-black {
            background: var(--black);
            color: white;
        }

        .btn-black:hover {
            background: #404040;
        }

        .btn-light {
            background: #f5f5f5;
            color: #262626;
            border: 1px solid #e5e5e5;
        }

        .btn-light:hover {
            background: #e5e5e5;
        }

        .panel {
            margin-bottom: 24px;
            overflow: hidden;
            border: 1px solid #f0e7df;
            border-radius: 18px;
            background: white;
            box-shadow: 0 5px 18px rgb(0 0 0 / 4%);
        }

        .panel-header {
            padding: 22px 24px;
            border-bottom: 1px solid #f0e7df;
        }

        .panel-title {
            margin: 0;
            font-size: 19px;
            font-weight: 800;
        }

        .panel-description {
            margin: 5px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .filter-body {
            padding: 24px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            align-items: end;
            gap: 16px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .field input {
            display: block;
            width: 100%;
            min-height: 46px;
            padding: 10px 12px;
            border: 1px solid #d4d4d4;
            border-radius: 10px;
            background: white;
            font: inherit;
        }

        .field input:focus {
            outline: 2px solid #fed7aa;
            border-color: var(--orange);
        }

        .period-text {
            margin: 18px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }

        .summary-card {
            position: relative;
            min-width: 0;
            padding: 22px;
            overflow: hidden;
            border: 1px solid #f0e7df;
            border-radius: 17px;
            background: white;
            box-shadow: 0 5px 18px rgb(0 0 0 / 3%);
        }

        .summary-card::before {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--orange);
            content: "";
        }

        .summary-label {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        .summary-value {
            margin: 12px 0 4px;
            overflow-wrap: anywhere;
            font-size: clamp(21px, 2.2vw, 28px);
            font-weight: 900;
            line-height: 1.3;
        }

        .summary-note {
            margin: 8px 0 0;
            color: #a3a3a3;
            font-size: 12px;
        }

        .orange-text {
            color: var(--orange-dark);
        }

        .green-text {
            color: #15803d;
        }

        .purple-text {
            color: #7e22ce;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table thead {
            background: #fff1e6;
        }

        .data-table th {
            padding: 15px 20px;
            color: #525252;
            font-size: 12px;
            font-weight: 800;
            text-align: left;
            white-space: nowrap;
        }

        .data-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f5f5f5;
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: #fffaf5;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .invoice {
            color: #262626;
            font-weight: 700;
            white-space: nowrap;
        }

        .payment-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            background: #f5f5f5;
            color: #404040;
            font-size: 11px;
            font-weight: 800;
        }

        .empty-state {
            padding: 36px 20px !important;
            color: var(--muted);
            text-align: center;
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: #ffedd5;
            color: #c2410c;
            font-weight: 900;
        }

        .report-footer {
            padding-top: 12px;
            color: #a3a3a3;
            text-align: center;
            font-size: 12px;
        }

        @media (max-width: 1000px) {
            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .topbar-inner {
                padding: 14px 16px;
            }

            .page {
                padding: 24px 14px 32px;
            }

            .filter-grid,
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .panel-header,
            .filter-body {
                padding: 18px;
            }

            .summary-card {
                padding: 20px;
            }

            .button-group {
                width: 100%;
            }

            .button-group .btn {
                flex: 1;
            }

            .data-table th,
            .data-table td {
                padding: 12px 14px;
            }
        }

        @media print {
            body {
                background: white !important;
            }

            .topbar,
            .no-print,
            .filter-panel {
                display: none !important;
            }

            .page {
                max-width: none;
                padding: 0;
            }

            .panel,
            .summary-card {
                box-shadow: none !important;
                break-inside: avoid;
            }

            .summary-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
            }

            .summary-card {
                padding: 12px;
            }

            .data-table {
                font-size: 10px;
            }

            .data-table th,
            .data-table td {
                padding: 8px;
            }

            @page {
                size: landscape;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    <!-- NAVIGASI -->
    <header class="topbar no-print">
        <div class="topbar-inner">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-logo">WP</span>
                <span>WARUNG POJOK</span>
            </a>

            <a href="{{ route('dashboard') }}"
               class="btn btn-orange">
                ← Dashboard
            </a>
        </div>
    </header>

    <main class="page">

        <!-- HEADER HALAMAN -->
        <section class="page-header">
            <div>
                <div class="eyebrow">WARUNG POJOK POS</div>
                <h1 class="page-title">Laporan Penjualan</h1>
                <p class="page-description">
                    Pantau omzet, transaksi, dan produk terlaris warung.
                </p>
            </div>

            <div class="button-group no-print">
                <a href="{{ route('sales.index') }}" class="btn btn-black">
                    Riwayat Penjualan
                </a>

                <button type="button"
                        onclick="window.print()"
                        class="btn btn-orange">
                    Cetak Laporan
                </button>
            </div>
        </section>

        <!-- FILTER PERIODE -->
        <section class="panel filter-panel">
            <div class="panel-header">
                <h2 class="panel-title">Filter Periode Laporan</h2>
                <p class="panel-description">
                    Pilih tanggal untuk melihat laporan penjualan.
                </p>
            </div>

            <div class="filter-body">
                <form action="{{ route('sales.report') }}"
                      method="GET"
                      class="filter-grid">

                    <div class="field">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date"
                               id="start_date"
                               name="start_date"
                               value="{{ $startDate }}"
                               required>
                    </div>

                    <div class="field">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date"
                               id="end_date"
                               name="end_date"
                               value="{{ $endDate }}"
                               min="{{ $startDate }}"
                               required>
                    </div>

                    <button type="submit" class="btn btn-orange">
                        Tampilkan Laporan
                    </button>

                    <a href="{{ route('sales.report') }}"
                       class="btn btn-light">
                        Hari Ini
                    </a>
                </form>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <p class="period-text">
                    Periode laporan:
                    <strong>
                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        sampai
                        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </strong>
                </p>
            </div>
        </section>

        <!-- RINGKASAN -->
        <section class="summary-grid">
            <article class="summary-card">
                <p class="summary-label">Total Omzet</p>
                <h2 class="summary-value green-text">
                    Rp {{ number_format((float) $totalOmzet, 0, ',', '.') }}
                </h2>
                <p class="summary-note">Total pendapatan periode terpilih</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Jumlah Transaksi</p>
                <h2 class="summary-value orange-text">
                    {{ number_format($jumlahTransaksi, 0, ',', '.') }}
                </h2>
                <p class="summary-note">Transaksi tercatat</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Produk Terjual</p>
                <h2 class="summary-value purple-text">
                    {{ number_format($totalProdukTerjual, 0, ',', '.') }}
                </h2>
                <p class="summary-note">Total unit produk terjual</p>
            </article>

            <article class="summary-card">
                <p class="summary-label">Rata-rata per Transaksi</p>
                <h2 class="summary-value orange-text">
                    Rp {{ number_format(
                        $jumlahTransaksi > 0
                            ? $totalOmzet / $jumlahTransaksi
                            : 0,
                        0,
                        ',',
                        '.'
                    ) }}
                </h2>
                <p class="summary-note">Rata-rata nilai transaksi</p>
            </article>
        </section>

        <!-- PRODUK TERLARIS -->
        <section class="panel">
            <div class="panel-header">
                <h2 class="panel-title">🏆 5 Produk Terlaris</h2>
                <p class="panel-description">
                    Berdasarkan jumlah unit yang terjual selama periode terpilih.
                </p>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Peringkat</th>
                            <th>Nama Produk</th>
                            <th class="text-right">Jumlah Terjual</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($produkTerlaris as $produk)
                            <tr>
                                <td>
                                    <span class="rank-badge">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $produk['nama'] }}</strong>
                                </td>

                                <td class="text-right">
                                    <strong>
                                        {{ number_format($produk['jumlah'], 0, ',', '.') }}
                                        unit
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">
                                    Belum ada produk terjual pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- DETAIL TRANSAKSI -->
        <section class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Detail Transaksi</h2>
                <p class="panel-description">
                    Daftar transaksi selama periode laporan yang dipilih.
                </p>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Invoice</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-right">Total</th>
                            <th class="text-center no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="invoice">
                                        {{ $sale->invoice_number ?? 'INV-' . $sale->id }}
                                    </span>
                                </td>

                                <td style="white-space: nowrap;">
                                    {{ $sale->created_at
                                        ? \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $sale->user->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="payment-badge">
                                        {{ strtoupper($sale->payment_method ?? '-') }}
                                    </span>
                                </td>

                                <td class="text-right" style="white-space: nowrap;">
                                    <strong>
                                        Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td class="text-center no-print">
                                    <div class="button-group" style="justify-content: center;">
                                        <a href="{{ route('sales.show', $sale->id) }}"
                                           class="btn btn-black"
                                           style="min-height: 34px; padding: 7px 11px; font-size: 12px;">
                                            Detail
                                        </a>

                                        <a href="{{ route('sales.receipt', $sale->id) }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="btn btn-orange"
                                           style="min-height: 34px; padding: 7px 11px; font-size: 12px;">
                                            Struk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    Belum ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($sales->isNotEmpty())
                        <tfoot>
                            <tr style="background: #fff7ed;">
                                <td colspan="5" class="text-right">
                                    <strong>TOTAL OMZET</strong>
                                </td>

                                <td class="text-right" style="white-space: nowrap;">
                                    <strong class="green-text">
                                        Rp {{ number_format((float) $totalOmzet, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td class="no-print"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </section>

        <footer class="report-footer">
            © {{ date('Y') }} WARUNG POJOK · Sistem Kasir dan Manajemen Penjualan
        </footer>

    </main>

    <script>
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        if (startDate && endDate) {
            startDate.addEventListener('change', function () {
                endDate.min = this.value;

                if (endDate.value && endDate.value < this.value) {
                    endDate.value = this.value;
                }
            });
        }
    </script>

</body>
</html>
```