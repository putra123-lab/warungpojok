
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bahan Baku - Warmindo</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 40px;
            color: #222;
        }

        .container {
            max-width: 650px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        .item {
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .label {
            color: #666;
            margin-bottom: 6px;
        }

        .back {
            display: inline-block;
            margin-top: 24px;
            padding: 11px 15px;
            background: #666;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .warning {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Detail Bahan Baku</h1>
        <p>Informasi persediaan bahan Warmindo.</p>

        <div class="item">
            <div class="label">Nama Bahan</div>
            <strong>{{ $ingredient->name }}</strong>
        </div>

        <div class="item">
            <div class="label">Satuan</div>
            <strong>{{ $ingredient->unit }}</strong>
        </div>

        <div class="item">
            <div class="label">Stok Saat Ini</div>
            <strong>{{ $ingredient->stock }} {{ $ingredient->unit }}</strong>
        </div>

        <div class="item">
            <div class="label">Stok Minimum</div>
            <strong>{{ $ingredient->minimum_stock }} {{ $ingredient->unit }}</strong>
        </div>

        <div class="item">
            <div class="label">Harga Modal per Satuan</div>
            <strong>Rp {{ number_format((float) $ingredient->cost_per_unit, 0, ',', '.') }}</strong>
        </div>

        <div class="item">
            <div class="label">Status Stok</div>
            @if ($ingredient->stock <= $ingredient->minimum_stock)
                <strong class="warning">Stok menipis, perlu restok</strong>
            @else
                <strong>Aman</strong>
            @endif
        </div>

        <a class="back" href="{{ route('ingredients.index') }}">
            Kembali ke Daftar Bahan
        </a>
    </div>
</body>
</html>
