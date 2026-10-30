<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            padding: 30px;
            color: #29243a;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.06);
        }

        h1 { color: #6336a5; }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-family: Arial, sans-serif;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 11px 17px;
            background: #7042b8;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .error {
            color: #c62828;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Add New Category</h1>

    <p>Create a new product category for your store.</p>

    <a href="{{ route('categories.index') }}" class="btn">
        Back to Categories
    </a>

    <form action="{{ route('categories.store') }}" method="POST">

        @csrf

        <label>Category Name</label>

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            placeholder="Example: School Supplies"
            required
        >

        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label>Description</label>

        <textarea
            name="description"
            placeholder="Enter category description..."
        >{{ old('description') }}</textarea>

        @error('description')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn">
            Save Category
        </button>

    </form>

</div>

</body>
</html>
