<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>

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
            max-width: 1050px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        h1 { color: #6336a5; }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #7042b8;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .edit-btn { background: #198754; }
        .delete-btn { background: #dc3545; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 13px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #7042b8;
            color: white;
        }

        .success, .error {
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .success {
            background: #d9f7e5;
            color: #176b37;
        }

        .error {
            background: #ffe1e1;
            color: #a00000;
        }

        .empty {
            text-align: center;
            padding: 25px;
            color: #777;
        }

        .actions {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        @media(max-width: 650px) {
            body { padding: 10px; }
            .container { padding: 15px; overflow-x: auto; }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <div>
            <h1>Category Management</h1>
            <p>Manage your store product categories.</p>
        </div>

        <div>
            <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
            <a href="{{ route('categories.create') }}" class="btn">+ Add Category</a>
        </div>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? 'No description' }}</td>
                    <td>{{ $category->products_count }}</td>

                    <td>
                        <div class="actions">

                            <a href="{{ route('categories.edit', $category->id) }}"
                               class="btn edit-btn">
                                Edit
                            </a>

                            <form action="{{ route('categories.destroy', $category->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this category?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn delete-btn">
                                    Delete
                                </button>

                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        No categories found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $categories->links() }}
    </div>

</div>

</body>
</html>
