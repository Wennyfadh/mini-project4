<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the inventory system dashboard.
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalInventoryValue = Product::selectRaw('SUM(stock * price) as val')->value('val') ?? 0;
        
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')
            ->where('stock', '>', 0)
            ->count();

        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        $totalIn = StockMovement::where('type', 'IN')->sum('quantity');
        $totalOut = StockMovement::where('type', 'OUT')->sum('quantity');

        // Recent stock movements (6 latest)
        $recentMovements = StockMovement::with(['product.category', 'user.profile'])
            ->latest()
            ->take(6)
            ->get();

        // Products that need attention (stock <= minimum_stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Chart Data: Stock distribution by category
        $categoryStats = Category::withCount('products')
            ->withSum('products as total_stock', 'stock')
            ->get()
            ->map(function ($cat) {
                return [
                    'name' => $cat->name,
                    'count' => $cat->products_count,
                    'stock' => (int) ($cat->total_stock ?? 0),
                ];
            });

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalInventoryValue',
            'lowStockCount',
            'outOfStockCount',
            'totalIn',
            'totalOut',
            'recentMovements',
            'lowStockProducts',
            'categoryStats'
        ));
    }
}
