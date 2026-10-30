<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales History - Inventory System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            margin: 0;
            padding: 30px;
            color: #29243a;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        h1 {
            color: #6336a5;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #7042b8;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background: #542b91;
        }

        .success {
            background: #d9f7e5;
            color: #176b37;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

        .view-link {
            color: #6336a5;
            font-weight: bold;
            text-decoration: none;
        }

        .empty {
            text-align: center;
            padding: 25px;
            color: #777;
        }

        @media (max-width: 700px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <div>
            <h1>Sales History</h1>
            <p>View all recorded store transactions.</p>
        </div>

        <div>
            <a href="{{ route('dashboard') }}" class="btn">Dashboard</a>
            <a href="{{ route('sales.create') }}" class="btn">+ New Sale</a>
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Sale Number</th>
                <th>Cashier</th>
                <th>Total Amount</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->sale_number }}</td>
                    <td>{{ $sale->user->name ?? 'Unknown' }}</td>
                    <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                    <td>{{ $sale->sold_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale->id) }}"
                           class="view-link">
                            View Receipt
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        No sales recorded yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $sales->links() }}
    </div>

</div>

</body>
</html>
