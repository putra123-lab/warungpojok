
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Data Produk - WARUNG POJOK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange: #f97316;
            --orange-dark: #ea580c;
            --orange-light: #fff7ed;
            --black: #171717;
            --text: #292524;
            --muted: #78716c;
            --border: #e7e5e4;
            --background: #f5f5f4;
            --white: #ffffff;
            --green: #15803d;
            --red: #dc2626;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        a {
            text-decoration: none;
        }

        .topbar {
            background: var(--black);
            color: white;
            padding: 18px 5%;
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
            color: white;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--orange);
            color: white;
            display: grid;
            place-items: center;
            font-size: 18px;
        }

        .top-label {
            color: #fed7aa;
            font-size: 12px;
            margin-top: 4px;
            letter-spacing: 1.5px;
        }

        .page {
            width: min(1200px, 92%);
            margin: 32px auto;
        }

        .breadcrumb {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .breadcrumb a {
            color: var(--orange-dark);
            font-weight: 700;
        }

        .hero {
            background: linear-gradient(120deg, #171717, #292524);
            color: white;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "WP";
            position: absolute;
            right: 30px;
            top: 0;
            font-size: 110px;
            line-height: 1.5;
            font-weight: 800;
            color: rgba(255,255,255,.05);
            pointer-events: none;
        }

        .eyebrow {
            color: #fdba74;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            margin: 10px 0;
            font-size: clamp(25px, 4vw, 34px);
            letter-spacing: -1px;
        }

        .hero p {
            color: #d6d3d1;
            margin: 0;
            line-height: 1.7;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 17px;
            border: 0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .button:hover {
            transform: translateY(-1px);
            filter: brightness(.95);
        }

        .button-dark {
            background: var(--black);
            color: white;
        }

        .button-orange {
            background: var(--orange);
            color: white;
        }

        .button-detail {
            background: #292524;
            color: white;
        }

        .button-edit {
            background: #ffedd5;
            color: #9a3412;
        }

        .button-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .alert {
            padding: 14px 17px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            color: #166534;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            padding: 20px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .summary-value {
            font-size: 25px;
            font-weight: 800;
            margin-top: 9px;
            color: var(--black);
        }

        .table-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(23,23,23,.04);
        }

        .table-heading {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .table-heading h2 {
            margin: 0;
            font-size: 18px;
            color: var(--black);
        }

        .table-heading p {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        thead {
            background: var(--orange-light);
        }

        th {
            padding: 15px 18px;
            color: #9a3412;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            text-align: left;
        }

        td {
            padding: 17px 18px;
            border-bottom: 1px solid #f0eeeb;
            font-size: 13px;
        }

        tbody tr:hover {
            background: #fffaf5;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .product-name {
            font-weight: 700;
            color: var(--black);
        }

        .sku {
            display: inline-block;
            background: #f5f5f4;
            color: #57534e;
            padding: 5px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .price {
            font-weight: 800;
            color: var(--black);
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 800;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .actions .button {
            padding: 9px 11px;
            font-size: 12px;
        }

        .actions form {
            margin: 0;
        }

        .empty {
            padding: 48px 20px;
            text-align: center;
            color: var(--muted);
        }

        .empty-icon {
            font-size: 36px;
        }

        .empty strong {
            display: block;
            margin: 12px 0 5px;
            color: var(--black);
        }

        .pagination {
            padding: 18px 22px;
            border-top: 1px solid var(--border);
        }

        .footer {
            text-align: center;
            padding: 28px 10px;
            font-size: 12px;
            color: var(--muted);
        }

        @media (max-width: 650px) {
            .topbar {
                padding: 15px 4%;
            }

            .page {
                width: 94%;
                margin: 20px auto;
            }

            .hero {
                padding: 23px;
            }

            .summary {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .summary-card {
                padding: 16px;
            }

            .toolbar {
                align-items: stretch;
            }

            .toolbar .button {
                flex: 1;
            }

            .table-heading {
                padding: 18px;
            }
        }

        @media print {
            .topbar,
            .breadcrumb,
            .toolbar,
            .actions,
            .footer {
                display: none !important;
            }

            body {
                background: white;
            }

            .page {
                width: 100%;
                margin: 0;
            }

            .hero {
                background: white;
                color: black;
                padding: 10px 0;
            }

            .hero p,
            .eyebrow {
                color: black;
            }
        }
    </style>
</head>

<body>
    <header class="topbar">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-logo">WP</span>
            <span>
                WARUNG POJOK
                <div class="top-label">SISTEM KASIR</div>
            </span>
        </a>

        <a href="{{ route('dashboard') }}" class="button button-orange">
            ← Dashboard
        </a>
    </header>

    <main class="page">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            &nbsp; / &nbsp; Produk
        </div>

        <section class="hero">
            <div class="eyebrow">Manajemen Menu</div>
            <h1>Data Produk</h1>
            <p>Kelola menu, harga jual, dan persediaan produk WARUNG POJOK.</p>
        </section>

        @if (session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $productCount = $products->count();
            $activeCount = $products->filter(function ($product) {
                return (bool) $product->is_active;
            })->count();
            $stockCount = $products->sum(function ($product) {
                return (float) ($product->stock ?? 0);
            });
        @endphp

        <section class="summary">
            <div class="summary-card">
                <div class="summary-label">Produk Ditampilkan</div>
                <div class="summary-value">{{ $productCount }}</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Produk Aktif</div>
                <div class="summary-value">{{ $activeCount }}</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Total Stok Ditampilkan</div>
                <div class="summary-value">{{ number_format($stockCount, 0, ',', '.') }}</div>
            </div>
        </section>

        <div class="toolbar">
            <a href="{{ route('dashboard') }}" class="button button-dark">
                ← Kembali ke Dashboard
            </a>

            <a href="{{ route('products.create') }}" class="button button-orange">
                + Tambah Produk
            </a>
        </div>

        <section class="table-card">
            <div class="table-heading">
                <div>
                    <h2>Daftar Produk WARUNG POJOK</h2>
                    <p>Informasi produk dan tindakan pengelolaan.</p>
                </div>
                <span class="status status-active">
                    {{ $productCount }} produk
                </span>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Produk</th>
                            <th>SKU</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    {{ method_exists($products, 'firstItem') ? $products->firstItem() + $loop->index : $loop->iteration }}
                                </td>

                                <td class="product-name">
                                    {{ $product->name }}
                                </td>

                                <td>
                                    <span class="sku">{{ $product->sku ?? '-' }}</span>
                                </td>

                                <td class="price">
                                    Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ number_format((float) ($product->stock ?? 0), 0, ',', '.') }}
                                </td>

                                <td>
                                    @if ($product->is_active)
                                        <span class="status status-active">Aktif</span>
                                    @else
                                        <span class="status status-inactive">Nonaktif</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="actions">
                                        <a class="button button-detail"
                                           href="{{ route('products.show', $product) }}">
                                            Detail
                                        </a>

                                        <a class="button button-edit"
                                           href="{{ route('products.edit', $product) }}">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="button button-delete" type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty">
                                        <div class="empty-icon">📦</div>
                                        <strong>Belum ada produk</strong>
                                        <p>Tambahkan produk untuk mulai mengelola menu warung.</p>
                                        <a href="{{ route('products.create') }}"
                                           class="button button-orange">
                                            + Tambah Produk
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($products, 'links'))
                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @endif
        </section>

        <footer class="footer">
            © {{ date('Y') }} WARUNG POJOK · Sistem Manajemen Kasir
        </footer>
    </main>
</body>
</html>
