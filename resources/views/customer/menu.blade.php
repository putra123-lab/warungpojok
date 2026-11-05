
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu WARUNG POJOK</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f6f7f9;
            color: #222;
            font-family: Arial, sans-serif;
        }

        .header {
            background: #171717;
            color: white;
            padding: 25px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #fb923c;
            letter-spacing: 2px;
        }

        .header p {
            margin: 8px 0 0;
            color: #ddd;
        }

        .container {
            width: min(700px, 94%);
            margin: 22px auto;
        }

        .panel {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 18px;
            box-shadow: 0 3px 15px #00000008;
        }

        .product {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }

        .product:last-child {
            border-bottom: 0;
        }

        .product h3 {
            margin: 0 0 7px;
            font-size: 16px;
        }

        .price {
            color: #ea580c;
            font-weight: bold;
        }

        .quantity {
            width: 75px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
        }

        textarea {
            min-height: 85px;
            resize: vertical;
        }

        .field {
            margin-bottom: 17px;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .submit {
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 10px;
            background: #f97316;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        .submit:hover {
            background: #ea580c;
        }

        .alert {
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 25px;
        }

        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            padding: 20px;
        }
    </style>
</head>

<body>

<header class="header">
    <h1>WARUNG POJOK</h1>
    <p>Pesan makanan langsung dari meja kamu</p>
</header>

<main class="container">

    <div class="panel">
        <h2 style="margin-top:0">
            Meja {{ $table->table_number }}
        </h2>

        <p style="color:#777;margin-bottom:0">
            {{ $table->name ?: 'Silakan pilih menu favorit kamu.' }}
        </p>
    </div>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <strong>Pesanan belum berhasil dikirim:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('customer.orders.store', ['token' => $table->qr_token]) }}"
          id="orderForm">

        @csrf

        <div class="panel">
            <h2 style="margin-top:0">Daftar Menu</h2>

            @forelse($products as $product)

                <div class="product">

                    <div>
                        <h3>{{ $product->name }}</h3>

                        <div class="price">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <small style="color:#888">
                            Stok tersedia: {{ $product->stock }}
                        </small>
                    </div>

                    <input
                        class="quantity"
                        type="number"
                        name="items[{{ $product->id }}]"
                        value="{{ old('items.'.$product->id, 0) }}"
                        min="0"
                        max="{{ $product->stock }}"
                        data-price="{{ $product->price }}"
                        aria-label="Jumlah {{ $product->name }}"
                        {{ $product->stock < 1 ? 'disabled' : '' }}
                    >

                </div>

            @empty

                <div class="empty">
                    Belum ada produk yang tersedia.
                </div>

            @endforelse
        </div>

        <div class="panel">
            <h2 style="margin-top:0">Informasi Pemesan</h2>

            <div class="field">
                <label for="customer_name">
                    Nama Pemesan *
                </label>

                <input
                    type="text"
                    id="customer_name"
                    name="customer_name"
                    value="{{ old('customer_name') }}"
                    placeholder="Masukkan nama kamu"
                    maxlength="100"
                    required
                >
            </div>

            <div class="field">
                <label for="notes">
                    Catatan Pesanan (Opsional)
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    maxlength="1000"
                    placeholder="Contoh: tidak pedas, es sedikit..."
                >{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="panel">

            <div class="total">
                <span>Total Pesanan</span>
                <span id="totalDisplay">Rp 0</span>
            </div>

            <button type="submit" class="submit">
                Kirim Pesanan
            </button>

        </div>

    </form>

</main>

<footer class="footer">
    © {{ date('Y') }} WARUNG POJOK
</footer>

<script>
    const quantityInputs = document.querySelectorAll('.quantity');
    const totalDisplay = document.getElementById('totalDisplay');
    const orderForm = document.getElementById('orderForm');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    function calculateTotal() {
        let total = 0;

        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;

            total += quantity * price;
        });

        totalDisplay.textContent = formatRupiah(total);
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    orderForm.addEventListener('submit', function(event) {
        let hasItem = false;

        quantityInputs.forEach(input => {
            if (parseInt(input.value) > 0) {
                hasItem = true;
            }
        });

        if (!hasItem) {
            event.preventDefault();
            alert('Pilih minimal satu menu terlebih dahulu.');
        }
    });

    calculateTotal();
</script>

</body>
</html>
