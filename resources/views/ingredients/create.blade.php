
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bahan Baku - Warmindo</title>

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

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button, .back {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        button {
            background: #2563eb;
            color: white;
        }

        .back {
            background: #ddd;
            color: #222;
        }

        .error {
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tambah Bahan Baku</h1>
        <p>Masukkan data bahan yang digunakan di Warmindo.</p>

        @if ($errors->any())
            <div class="error">
                <p>Periksa kembali data berikut:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ingredients.store') }}" method="POST">
            @csrf

            <label for="name">Nama Bahan</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Mi instan"
                required
            >

            <label for="unit">Satuan</label>
            <select id="unit" name="unit" required>
                <option value="">-- Pilih satuan --</option>
                @foreach (['pcs', 'butir', 'gram', 'kg', 'ml', 'liter'] as $unit)
                    <option value="{{ $unit }}"
                        @selected(old('unit') === $unit)>
                        {{ $unit }}
                    </option>
                @endforeach
            </select>

            <label for="stock">Stok Saat Ini</label>
            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock', 0) }}"
                min="0"
                step="0.01"
                required
            >

            <label for="minimum_stock">Stok Minimum</label>
            <input
                type="number"
                id="minimum_stock"
                name="minimum_stock"
                value="{{ old('minimum_stock', 5) }}"
                min="0"
                step="0.01"
                required
            >

            <label for="cost_per_unit">Harga Modal per Satuan (Rp)</label>
            <input
                type="number"
                id="cost_per_unit"
                name="cost_per_unit"
                value="{{ old('cost_per_unit', 0) }}"
                min="0"
                step="0.01"
                required
            >

            <button type="submit">Simpan Bahan</button>
            <a class="back" href="{{ route('ingredients.index') }}">
                Kembali
            </a>
        </form>
    </div>
</body>
</html>
