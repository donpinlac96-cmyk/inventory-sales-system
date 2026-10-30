<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    // Display sales history
    public function index()
    {
        $sales = Sale::with('user')
            ->latest('sold_at')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    // Show the new sale form
    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    // Record a sale and deduct stock
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|integer|distinct|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        $sale = DB::transaction(function () use ($validated) {

            $saleNumber = 'SALE-' . now()->format('YmdHis')
                . '-' . strtoupper(Str::random(4));

            $sale = Sale::create([
                'user_id' => Auth::id(),
                'sale_number' => $saleNumber,
                'total_amount' => 0,
                'sold_at' => now(),
            ]);

            $total = 0;

            foreach ($validated['product_id'] as $index => $productId) {

                $quantity = (int) $validated['quantity'][$index];

                $product = Product::whereKey($productId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => "Not enough stock for {$product->name}. Available stock: {$product->stock_quantity}.",
                    ]);
                }

                $unitPrice = (float) $product->price;
                $subtotal = $unitPrice * $quantity;

                $sale->saleItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock_quantity', $quantity);

                $total += $subtotal;
            }

            $sale->update([
                'total_amount' => $total,
            ]);

            return $sale;
        });

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded successfully! Sale Number: ' . $sale->sale_number);
    }

    // Display a sale and its items
    public function show(Sale $sale)
    {
        $sale->load(['user', 'saleItems.product']);

        return view('sales.show', compact('sale'));
    }
}
