
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sale Receipt - Inventory System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            padding: 30px;
            color: #29243a;
        }

        .receipt {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        h1 {
            color: #6336a5;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
        }

        .details {
            background: #f8f6fc;
            padding: 18px;
            border-radius: 8px;
            line-height: 1.9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #7042b8;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 25px;
            font-weight: bold;
            color: #6336a5;
            margin-top: 25px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            background: #7042b8;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #542b91;
        }

        .print-btn {
            background: #198754;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt {
                box-shadow: none;
                border-radius: 0;
            }

            .buttons {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="receipt">

    <h1>INVENTORY SALES RECEIPT</h1>

    <p class="subtitle">
        Inventory and Sales Management System
    </p>

    <div class="details">

        <strong>Sale Number:</strong>
        {{ $sale->sale_number }}

        <br>

        <strong>Cashier:</strong>
        {{ $sale->user->name ?? 'Unknown' }}

        <br>

        <strong>Date:</strong>
        {{ $sale->sold_at->format('F d, Y h:i A') }}

    </div>

    <h3>Purchased Products</h3>

    <table>

        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>

            @forelse($sale->saleItems as $item)

                <tr>
                    <td>
                        {{ $item->product->name ?? 'Deleted Product' }}
                    </td>

                    <td>
                        {{ $item->quantity }}
                    </td>

                    <td>
                        ₱{{ number_format($item->unit_price, 2) }}
                    </td>

                    <td>
                        ₱{{ number_format($item->subtotal, 2) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        No products found for this sale.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <div class="total">
        TOTAL: ₱{{ number_format($sale->total_amount, 2) }}
    </div>

    <p style="text-align:center; margin-top:30px;">
        Thank you for your purchase!
    </p>

    <div class="buttons">

        <a href="{{ route('sales.index') }}" class="btn">
            Back to Sales
        </a>

        <button onclick="window.print()" class="btn print-btn">
            Print Receipt
        </button>

    </div>

</div>

</body>
</html>
