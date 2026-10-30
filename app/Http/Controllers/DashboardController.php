<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $totalStock = Product::sum('stock_quantity');

        $lowStockProducts = Product::whereColumn(
            'stock_quantity',
            '<=',
            'low_stock_threshold'
        )->count();

        $recentSales = Sale::with('user')
            ->latest('sold_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
