<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'month' => 'nullable|date_format:Y-m',
        ]);

        // Selected date and month
        $selectedDate = $validated['date'] ?? now()->toDateString();
        $selectedMonth = $validated['month'] ?? now()->format('Y-m');

        $monthStart = Carbon::createFromFormat('Y-m', $selectedMonth)
            ->startOfMonth();

        $monthEnd = $monthStart->copy()->endOfMonth();

        // Daily sales
        $dailySales = Sale::with(['user', 'saleItems.product'])
            ->whereDate('sold_at', $selectedDate)
            ->latest('sold_at')
            ->get();

        $dailyTotal = $dailySales->sum('total_amount');

        // Monthly sales
        $monthlySales = Sale::with('user')
            ->whereBetween('sold_at', [$monthStart, $monthEnd])
            ->latest('sold_at')
            ->get();

        $monthlyTotal = $monthlySales->sum('total_amount');

        // Best-selling products
        $topProducts = SaleItem::query()
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
            ->whereHas('sale', function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('sold_at', [$monthStart, $monthEnd]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        // Inventory report
        $products = Product::with('category')
            ->orderBy('name')
            ->get();

        // Low-stock products
        $lowStockProducts = Product::whereColumn(
            'stock_quantity',
            '<=',
            'low_stock_threshold'
        )->with('category')
         ->orderBy('stock_quantity')
         ->get();

        return view('reports.index', compact(
            'selectedDate',
            'selectedMonth',
            'dailySales',
            'dailyTotal',
            'monthlySales',
            'monthlyTotal',
            'topProducts',
            'products',
            'lowStockProducts'
        ));
    }
}
