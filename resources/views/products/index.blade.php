<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f5f9;
            color: #25213d;
        }

        header {
            background: #25213d;
            color: white;
            padding: 22px 35px;
        }

        main { padding: 30px; }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            display: inline-block;
            padding: 11px 17px;
            border: none;
            border-radius: 7px;
            background: #6d50c7;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover { background: #5439a6; }

        .search {
            display: flex;
            gap: 8px;
            margin: 20px 0;
        }

        input {
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 7px;
            width: 280px;
        }

        .section {
            background: white;
            padding: 22px;
            border-radius: 12px;
            overflow-x: auto;
            box-shadow: 0 3px 12px #00000010;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th { background: #f7f5fc; }

        .low {
            color: #c2410c;
            font-weight: bold;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 15px;
        }

        .actions {
            display: flex;
            gap: 7px;
            align-items: center;
        }

        .edit {
            background: #2563eb;
        }

        .delete {
            background: #dc2626;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            margin: 3px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #25213d;
        }

        .pagination .active span {
            background: #6d50c7;
            color: white;
        }
    </style>
</head>

<body>

<header>
    <h1>Inventory Management System</h1>
    <p>Product Management</p>
</header>

<main>

    <div class="topbar">
        <div>
            <h2>Product List</h2>
            <p>Manage your store inventory and products.</p>
        </div>

        <div>
            <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
            <a href="{{ route('products.create') }}" class="btn">+ Add Product</a>
        </div>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('products.index') }}" method="GET" class="search">
        <input
            type="text"
            name="search"
            placeholder="Search product name or code..."
            value="{{ $search }}"
        >

        <button type="submit" class="btn">Search</button>

        <a href="{{ route('products.index') }}" class="btn">Reset</a>
    </form>

    <div class="section">

        <table>
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>

                        <td>
                            @if ($product->stock_quantity <= $product->low_stock_threshold)
                                <span class="low">Low Stock</span>
                            @else
                                <span>In Stock</span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a
                                    href="{{ route('products.edit', $product->id) }}"
                                    class="btn edit"
                                >Edit</a>

                                <form
                                    action="{{ route('products.destroy', $product->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $products->links() }}
        </div>

    </div>

</main>

</body>
</html>
