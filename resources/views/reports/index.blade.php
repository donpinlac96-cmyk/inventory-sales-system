<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales and Inventory Reports</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #f5f3ff;
            font-family: Arial, sans-serif;
            color: #27213b;
        }

        .container {
            max-width: 1250px;
            margin: auto;
        }

        .header {
            background: #5b21b6;
            color: white;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
        }

        .header a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
        }

        .filters, .section {
            background: white;
            padding: 22px;
            border-radius: 12px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .filters form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            padding: 10px;
            border: 1px solid #d1c7e8;
            border-radius: 7px;
        }

        button, .button {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 7px;
            background: #6d28d9;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .print-button {
            background: #15803d;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        .stat-card h3 {
            color: #756b87;
            font-size: 14px;
            margin-top: 0;
        }

        .stat-card p {
            color: #5b21b6;
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 0;
        }

        h2 {
            color: #5b21b6;
            margin-top: 0;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee8f7;
        }

        th {
            background: #f3e8ff;
            color: #4c1d95;
        }

        .low-stock {
            color: #dc2626;
            font-weight: bold;
        }

        .normal-stock {
            color: #15803d;
            font-weight: bold;
        }

        .empty {
            color: #777;
            text-align: center;
            padding: 20px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .section, .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
                break-inside: avoid;
            }

            .header {
                background: white;
                color: black;
                border: 1px solid #ddd;
            }

            .header a {
                display: none;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <h1>Sales and Inventory Reports</h1>
        <p>View your store's sales performance and inventory status.</p>

        <div class="no-print">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('sales.index') }}">Sales History</a>
        </div>
    </div>

    <div class="filters no-print">
        <h2>Report Filters</h2>

        <form method="GET" action="{{ route('reports.index') }}">
            <div>
                <label for="date">Daily Sales Date</label>
                <input type="date" id="date" name="date"
                       value="{{ $selectedDate }}">
            </div>

            <div>
                <label for="month">Monthly Sales</label>
                <input type="month" id="month" name="month"
                       value="{{ $selectedMonth }}">
            </div>

            <button type="submit">Apply Filters</button>

            <a href="{{ route('reports.index') }}" class="button">
                Reset
            </a>

            <button type="button" class="print-button"
                    onclick="window.print()">
                Print Report
            </button>
        </form>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>Daily Sales</h3>
            <p>₱{{ number_format($dailyTotal, 2) }}</p>
            <small>{{ $dailySales->count() }} transactions</small>
        </div>

        <div class="stat-card">
            <h3>Monthly Sales</h3>
            <p>₱{{ number_format($monthlyTotal, 2) }}</p>
            <small>{{ $monthlySales->count() }} transactions</small>
        </div>

        <div class="stat-card">
            <h3>Total Products</h3>
            <p>{{ $products->count() }}</p>
            <small>Products in inventory</small>
        </div>

        <div class="stat-card">
            <h3>Low-Stock Products</h3>
            <p>{{ $lowStockProducts->count() }}</p>
            <small>Products needing restock</small>
        </div>
    </div>

    <div class="section">
        <h2>Daily Sales Report</h2>
        <p>Selected date: {{ $selectedDate }}</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sale Number</th>
                        <th>Cashier</th>
                        <th>Date and Time</th>
                        <th>Total Amount</th>
                        <th class="no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailySales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>
                            <td>{{ $sale->user->name ?? 'Unknown' }}</td>
                            <td>{{ $sale->sold_at }}</td>
                            <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="no-print">
                                <a href="{{ route('sales.show', $sale) }}">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                No sales found for this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Monthly Sales Report</h2>
        <p>Selected month: {{ $selectedMonth }}</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Sale Number</th>
                        <th>Cashier</th>
                        <th>Date and Time</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlySales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>
                            <td>{{ $sale->user->name ?? 'Unknown' }}</td>
                            <td>{{ $sale->sold_at }}</td>
                            <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">
                                No sales found for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Best-Selling Products</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity Sold</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                            <td>{{ $item->total_quantity }}</td>
                            <td>₱{{ number_format($item->total_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty">
                                No product sales available yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Inventory Report</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Low-Stock Threshold</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>{{ $product->low_stock_threshold }}</td>
                            <td>
                                @if($product->stock_quantity <= $product->low_stock_threshold)
                                    <span class="low-stock">Low Stock</span>
                                @else
                                    <span class="normal-stock">In Stock</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Low-Stock Alerts</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Current Stock</th>
                        <th>Required Threshold</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="low-stock">{{ $product->stock_quantity }}</td>
                            <td>{{ $product->low_stock_threshold }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">
                                No low-stock products. Inventory is healthy!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
