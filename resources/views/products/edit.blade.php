
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Kasir Warmindo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 40px 20px;
            color: #222;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
        }

        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button, .back {
            display: inline-block;
            margin-top: 24px;
            padding: 12px 18px;
            border: none;
            border-radius: 6px;
            background: #1677ff;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
        }

        .back {
            background: #666;
            margin-left: 8px;
        }

        .error {
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Produk</h1>
        <p>Ubah data produk Warmindo.</p>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name">Nama Produk</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $product->name) }}"
                required
            >

            <label for="sku">Kode Produk (SKU)</label>
            <input
                type="text"
                id="sku"
                name="sku"
                value="{{ old('sku', $product->sku) }}"
                required
            >

            <label for="price">Harga Jual (Rp)</label>
            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                step="1"
                required
            >

            <label for="stock">Stok</label>
            <input
                type="number"
                id="stock"
                name="stock"
                value="{{ old('stock', $product->stock) }}"
                min="0"
                step="1"
                required
            >

            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('products.index') }}" class="back">Kembali</a>
        </form>
    </div>
</body>
</html>
