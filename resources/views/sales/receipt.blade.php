
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk WARUNG POJOK</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #f3f4f6;
            color: #222;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        .toolbar {
            max-width: 650px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .toolbar label { font-weight: bold; }

        .toolbar select, .toolbar a, .toolbar button {
            border: 0;
            border-radius: 8px;
            padding: 11px 15px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .toolbar select {
            background: white;
            border: 1px solid #ddd;
        }

        .print-button {
            background: #f97316;
            color: white;
            font-weight: bold;
        }

        .back-button {
            background: #222;
            color: white;
        }

        .receipt {
            width: 100%;
            max-width: 380px;
            margin: auto;
            padding: 28px 25px;
            background: white;
            border-top: 7px solid #f97316;
            border-radius: 10px;
            box-shadow: 0 8px 30px #00000012;
        }

        .brand {
            text-align: center;
            padding-bottom: 18px;
            border-bottom: 1px dashed #aaa;
        }

        .logo {
            display: inline-block;
            padding: 8px 12px;
            background: #f97316;
            color: white;
            font-weight: 900;
            font-size: 20px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .brand h1 {
            margin: 0 0 8px;
            font-size: 23px;
            letter-spacing: 2px;
        }

        .brand p {
            margin: 5px 0;
            color: #666;
            line-height: 1.5;
        }

        .info {
            padding: 15px 0;
            border-bottom: 1px dashed #aaa;
        }

        .line {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin: 9px 0;
        }

        .line span:first-child {
            color: #666;
            flex-shrink: 0;
        }

        .line span:last-child {
            text-align: right;
            overflow-wrap: anywhere;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 10px 0;
            vertical-align: top;
        }

        th:last-child, td:last-child { text-align: right; }

        .product-name {
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .muted {
            margin-top: 4px;
            color: #777;
            font-size: 12px;
        }

        .total {
            margin-top: 12px;
            padding: 15px 0;
            border-top: 2px solid #222;
            border-bottom: 1px dashed #aaa;
        }

        .total strong {
            font-size: 20px;
        }

        .footer {
            text-align: center;
            padding-top: 22px;
            line-height: 1.7;
        }

        .footer strong {
            display: block;
            color: #f97316;
            font-size: 16px;
        }

        .footer p {
            margin: 8px 0;
            color: #666;
        }

        @media (max-width: 450px) {
            body { padding: 15px 8px; }
            .receipt { padding: 22px 16px; }
        }

        @media print {
            @page { size: 80mm auto; margin: 4mm; }

            body {
                padding: 0;
                background: white;
                font-size: 12px;
            }

            .toolbar { display: none !important; }

            .receipt {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 8px 4px;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .brand h1 { font-size: 19px; }
            .total strong { font-size: 17px; }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <label for="paperSize">Ukuran kertas:</label>

        <select id="paperSize" onchange="changePaperSize()">
            <option value="80">Thermal 80 mm</option>
            <option value="58">Thermal 58 mm</option>
            <option value="a4">A4</option>
        </select>

        <button class="print-button" onclick="window.print()">
            Cetak Struk
        </button>

        <a class="back-button"
           href="{{ route('sales.show', $sale) }}">
            Kembali
        </a>
    </div>

    <div class="receipt" id="receipt">
        <div class="brand">
            <div class="logo">WP</div>
            <h1>WARUNG POJOK</h1>
            <p>Terima kasih sudah berbelanja!</p>
            <p>Selamat menikmati pesanan Anda.</p>
        </div>

        <div class="info">
            <div class="line">
                <span>No. Invoice</span>
                <span>{{ $sale->invoice_number ?? ('TRX-' . $sale->id) }}</span>
            </div>

            <div class="line">
                <span>Tanggal</span>
                <span>
                    {{ $sale->created_at
                        ? \Illuminate\Support\Carbon::parse($sale->created_at)->format('d/m/Y H:i')
                        : '-' }}
                </span>
            </div>

            <div class="line">
                <span>Kasir</span>
                <span>{{ $sale->user->name ?? '-' }}</span>
            </div>

            <div class="line">
                <span>Pembayaran</span>
                <span>{{ strtoupper($sale->payment_method ?? '-') }}</span>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">
                                {{ $item->product_name ?? $item->product->name ?? 'Produk' }}
                            </div>
                            <div class="muted">
                                Rp {{ number_format((float) ($item->price ?? 0), 0, ',', '.') }} / item
                            </div>
                        </td>
                        <td>{{ $item->quantity ?? 0 }}</td>
                        <td>
                            Rp {{ number_format((float) ($item->subtotal ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <div class="line">
                <strong>TOTAL</strong>
                <strong>
                    Rp {{ number_format((float) ($sale->total ?? 0), 0, ',', '.') }}
                </strong>
            </div>
        </div>

        <div class="info">
            <div class="line">
                <span>Uang bayar</span>
                <span>
                    Rp {{ number_format((float) ($sale->paid_amount ?? 0), 0, ',', '.') }}
                </span>
            </div>

            <div class="line">
                <span>Kembalian</span>
                <span>
                    Rp {{ number_format((float) ($sale->change_amount ?? 0), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="footer">
            <strong>Terima Kasih!</strong>
            <p>Kepuasan Anda adalah kebahagiaan kami.</p>
            <p>Simpan struk ini sebagai bukti pembayaran.</p>
            <p>— WARUNG POJOK —</p>
        </div>
    </div>

    <script>
        function changePaperSize() {
            const size = document.getElementById('paperSize').value;
            let style = document.getElementById('dynamic-print-style');

            if (!style) {
                style = document.createElement('style');
                style.id = 'dynamic-print-style';
                document.head.appendChild(style);
            }

            if (size === 'a4') {
                style.textContent =
                    '@media print { @page { size: A4; margin: 15mm; } ' +
                    '.receipt { max-width: 500px; margin: auto; } }';
            } else {
                style.textContent =
                    '@media print { @page { size: ' + size +
                    'mm auto; margin: 4mm; } ' +
                    '.receipt { max-width: none; width: 100%; } }';
            }
        }
    </script>
</body>
</html>
