<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 30px;
            background: #f4f5f9;
            font-family: Arial, sans-serif;
            color: #25213d;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #00000010;
        }

        label {
            display: block;
            margin-top: 17px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            display: inline-block;
            margin-top: 22px;
            padding: 12px 18px;
            background: #6d50c7;
            color: white;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel { background: #666; }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 25px;
            border-radius: 7px;
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Product</h1>
    <p>Update the information for {{ $product->name }}.</p>

    @if ($errors->any())
        <div class="errors">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST">

        @csrf
        @method('PUT')

        <label>Product Code</label>
        <input
            type="text"
            name="product_code"
            value="{{ old('product_code', $product->product_code) }}"
            required
        >

        <label>Product Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $product->name) }}"
            required
        >

        <label>Category</label>
        <select name="category_id" required>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Description</label>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>

        <label>Price (₱)</label>
        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="{{ old('price', $product->price) }}"
            required
        >

        <label>Stock Quantity</label>
        <input
            type="number"
            name="stock_quantity"
            min="0"
            value="{{ old('stock_quantity', $product->stock_quantity) }}"
            required
        >

        <label>Low Stock Threshold</label>
        <input
            type="number"
            name="low_stock_threshold"
            min="0"
            value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
            required
        >

        <button type="submit" class="btn">Update Product</button>

        <a href="{{ route('products.index') }}" class="btn cancel">
            Cancel
        </a>

    </form>

</div>

</body>
</html>
