<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            color: #29233a;
        }

        .container {
            max-width: 1150px;
            margin: auto;
        }

        .header {
            background: #5b21b6;
            color: white;
            padding: 25px;
            border-radius: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
        }

        .logout-button {
            background: white;
            color: #5b21b6;
            border: none;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
        }

        .role {
            display: inline-block;
            background: #ede9fe;
            color: #5b21b6;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-top: 12px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .stat-card h3 {
            color: #777;
            font-size: 15px;
            margin-top: 0;
        }

        .stat-card h2 {
            color: #5b21b6;
            font-size: 30px;
            margin-bottom: 0;
        }

        .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .section h2 {
            margin-top: 0;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .action-button {
            display: inline-block;
            text-decoration: none;
            background: #5b21b6;
            color: white;
            padding: 13px 20px;
            border-radius: 8px;
            font-weight: bold;
        }

        .action-button:hover {
            background: #4c1d95;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background: #f5f3ff;
            color: #5b21b6;
        }

        .empty {
            color: #777;
            padding: 15px 0;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .header {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Inventory Management System</h1>

            <p>Welcome, {{ Auth::user()->name }}!</p>

            <span class="role">
                {{ strtoupper(Auth::user()->role) }} ACCOUNT
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">
                Logout
            </button>
        </form>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <!-- DASHBOARD STATISTICS -->
    <div class="stats">

        <div class="stat-card">
            <h3>Total Products</h3>
            <h2>{{ $totalProducts }}</h2>
        </div>

        <div class="stat-card">
            <h3>Total Stock Quantity</h3>
            <h2>{{ $totalStock }}</h2>
        </div>

        <div class="stat-card">
            <h3>Low Stock Products</h3>
            <h2>{{ $lowStockProducts }}</h2>
        </div>

    </div>

    <!-- SYSTEM MANAGEMENT -->
    <div class="section">
        <h2>System Management</h2>

        <div class="buttons">

            <a href="{{ route('products.index') }}" class="action-button">
                Manage Products
            </a>

            <a href="{{ route('categories.index') }}" class="action-button">
                Manage Categories
            </a>

            <a href="{{ route('sales.index') }}" class="action-button">
                Sales History
            </a>

            <a href="{{ route('sales.create') }}" class="action-button">
                Record New Sale
            </a>

           <a href="{{ route('reports.index') }}" class="action-button">
                Reports
            </a>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="action-button">
                    Manage Employees
                </a>
            @endif

        </div>
    </div>

    <!-- ROLE INFORMATION -->
    @if(Auth::user()->role === 'admin')

        <div class="section">
            <h2>Administrator Access</h2>
            <p>You have full access to manage inventory, categories, employees, and sales.</p>
        </div>

    @else

        <div class="section">
            <h2>Cashier Access</h2>
            <p>You can view inventory, record sales, and view sales history.</p>
            <p>Product, category, and employee management are restricted to administrators.</p>
        </div>

    @endif

    <!-- RECENT SALES -->
    <div class="section">
        <h2>Recent Sales</h2>

        @if($recentSales->count() > 0)

            <table>
                <thead>
                    <tr>
                        <th>Sale Number</th>
                        <th>Cashier</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentSales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>

                            <td>{{ $sale->user->name ?? 'Unknown' }}</td>

                            <td>₱{{ number_format($sale->total_amount, 2) }}</td>

                            <td>{{ $sale->sold_at?->format('M d, Y h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else

            <p class="empty">No sales recorded yet.</p>

        @endif

    </div>

</div>

</body>
</html>
