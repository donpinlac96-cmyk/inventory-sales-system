<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Sale - Inventory Management System</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            padding: 30px;
            margin: 0;
            color: #29243a;
        }

        .container {
            max-width: 950px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        h1 { color: #6336a5; }

        .btn {
            display: inline-block;
            padding: 11px 17px;
            background: #7042b8;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .remove-btn { background: #dc3545; }

        .product-row {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 15px;
            align-items: end;
            padding: 18px;
            background: #f8f6fc;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        select, input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .stock-info {
            font-size: 12px;
            color: #666;
            margin-top: 7px;
        }

        .total-box {
            background: #f3edff;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .total {
            font-size: 27px;
            font-weight: bold;
            color: #6336a5;
        }

        .error-box {
            background: #ffe1e1;
            color: #a00000;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        @media (max-width: 650px) {
            body { padding: 10px; }
            .container { padding: 18px; }
            .product-row { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Record New Sale</h1>
    <p>Select products and enter the quantities purchased by the customer.</p>

    <p>
        <a href="{{ route('sales.index') }}" class="btn">Back to Sales</a>
        <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
    </p>

    @if($errors->any())
        <div class="error-box">
            <strong>Please fix these errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($products->isEmpty())

        <p>No products with available stock were found.</p>

    @else

        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">

            @csrf

            <h3>Products to Sell</h3>

            <div id="productRows"></div>

            <button type="button" class="btn" id="addRow">
                + Add Another Product
            </button>

            <div class="total-box">
                <div>Total Sale Amount</div>
                <div class="total">
                    ₱<span id="totalAmount">0.00</span>
                </div>
            </div>

            <button type="submit" class="btn">
                Complete Sale
            </button>

        </form>

    @endif

</div>

@if($products->isNotEmpty())
<script>
    // Laravel safely converts the product collection into JavaScript.
    const products = {{ Illuminate\Support\Js::from($products->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->product_code,
            'price' => (float) $product->price,
            'stock' => (int) $product->stock_quantity
        ];
    })->values()) }};

    const productRows = document.getElementById('productRows');
    const totalAmount = document.getElementById('totalAmount');

    function updateTotal() {
        let total = 0;

        document.querySelectorAll('.product-row').forEach(function(row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            const product = products.find(function(item) {
                return String(item.id) === select.value;
            });

            const quantity = parseInt(quantityInput.value) || 0;

            if (product && quantity > 0) {
                total += product.price * quantity;
            }
        });

        totalAmount.textContent = total.toFixed(2);
    }

    function addProductRow() {
        const row = document.createElement('div');
        row.className = 'product-row';

        const productArea = document.createElement('div');
        const productLabel = document.createElement('label');
        productLabel.textContent = 'Product';

        const select = document.createElement('select');
        select.name = 'product_id[]';
        select.className = 'product-select';
        select.required = true;

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Product';
        select.appendChild(defaultOption);

        products.forEach(function(product) {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent =
                product.code + ' - ' + product.name +
                ' (₱' + product.price.toFixed(2) +
                ', Stock: ' + product.stock + ')';

            select.appendChild(option);
        });

        const stockInfo = document.createElement('div');
        stockInfo.className = 'stock-info';
        stockInfo.textContent = 'Select a product to see its stock.';

        productArea.appendChild(productLabel);
        productArea.appendChild(select);
        productArea.appendChild(stockInfo);

        const quantityArea = document.createElement('div');
        const quantityLabel = document.createElement('label');
        quantityLabel.textContent = 'Quantity';

        const quantityInput = document.createElement('input');
        quantityInput.type = 'number';
        quantityInput.name = 'quantity[]';
        quantityInput.className = 'quantity-input';
        quantityInput.min = '1';
        quantityInput.value = '1';
        quantityInput.required = true;

        quantityArea.appendChild(quantityLabel);
        quantityArea.appendChild(quantityInput);

        const buttonArea = document.createElement('div');
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn remove-btn';
        removeButton.textContent = 'Remove';

        buttonArea.appendChild(removeButton);

        row.appendChild(productArea);
        row.appendChild(quantityArea);
        row.appendChild(buttonArea);

        productRows.appendChild(row);

        select.addEventListener('change', function() {
            const product = products.find(function(item) {
                return String(item.id) === select.value;
            });

            if (product) {
                stockInfo.textContent =
                    'Price: ₱' + product.price.toFixed(2) +
                    ' | Available Stock: ' + product.stock;

                quantityInput.max = product.stock;
            } else {
                stockInfo.textContent = 'Select a product to see its stock.';
                quantityInput.removeAttribute('max');
            }

            updateTotal();
        });

        quantityInput.addEventListener('input', updateTotal);

        removeButton.addEventListener('click', function() {
            row.remove();
            updateTotal();
        });
    }

    document.getElementById('addRow').addEventListener('click', addProductRow);

    document.getElementById('saleForm').addEventListener('submit', function(event) {
        const rows = document.querySelectorAll('.product-row');
        const selectedProducts = [];
        let invalid = false;

        rows.forEach(function(row) {
            const select = row.querySelector('.product-select');
            const quantityInput = row.querySelector('.quantity-input');

            const product = products.find(function(item) {
                return String(item.id) === select.value;
            });

            const quantity = Number(quantityInput.value);

            if (
                !product ||
                !Number.isInteger(quantity) ||
                quantity < 1 ||
                quantity > product.stock ||
                selectedProducts.includes(select.value)
            ) {
                invalid = true;
            }

            selectedProducts.push(select.value);
        });

        if (rows.length === 0 || invalid) {
            event.preventDefault();
            alert('Please select different products and enter valid quantities within available stock.');
        }
    });

    addProductRow();
</script>
@endif

</body>
</html>
