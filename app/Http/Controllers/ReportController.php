<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display aggregate inventory reports.
     * Implements Slide 28 SQL aggregate query.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('stock_movements as sm', 'p.id', '=', 'sm.product_id')
            ->select(
                'p.id',
                'p.sku',
                'p.name as product_name',
                'c.name as category_name',
                'p.price',
                'p.stock',
                'p.minimum_stock',
                DB::raw("COALESCE(SUM(CASE WHEN sm.type = 'IN' THEN sm.quantity ELSE 0 END), 0) as total_in"),
                DB::raw("COALESCE(SUM(CASE WHEN sm.type = 'OUT' THEN sm.quantity ELSE 0 END), 0) as total_out"),
                DB::raw("(p.stock * p.price) as asset_value")
            )
            ->groupBy('p.id', 'p.sku', 'p.name', 'c.name', 'p.price', 'p.stock', 'p.minimum_stock');

        if ($request->filled('category_id')) {
            $query->where('p.category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('p.name', 'like', "%{$search}%")
                  ->orWhere('p.sku', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('c.name')->orderBy('p.name')->get();

        // Summary KPI
        $summary = [
            'total_models' => $reports->count(),
            'total_stock_units' => $reports->sum('stock'),
            'total_in_units' => $reports->sum('total_in'),
            'total_out_units' => $reports->sum('total_out'),
            'total_valuation' => $reports->sum('asset_value'),
        ];

        return view('reports.index', compact('reports', 'categories', 'summary'));
    }

    /**
     * Print-friendly view of the report.
     */
    public function print(Request $request)
    {
        $query = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->leftJoin('stock_movements as sm', 'p.id', '=', 'sm.product_id')
            ->select(
                'p.id',
                'p.sku',
                'p.name as product_name',
                'c.name as category_name',
                'p.price',
                'p.stock',
                'p.minimum_stock',
                DB::raw("COALESCE(SUM(CASE WHEN sm.type = 'IN' THEN sm.quantity ELSE 0 END), 0) as total_in"),
                DB::raw("COALESCE(SUM(CASE WHEN sm.type = 'OUT' THEN sm.quantity ELSE 0 END), 0) as total_out"),
                DB::raw("(p.stock * p.price) as asset_value")
            )
            ->groupBy('p.id', 'p.sku', 'p.name', 'c.name', 'p.price', 'p.stock', 'p.minimum_stock');

        if ($request->filled('category_id')) {
            $query->where('p.category_id', $request->category_id);
        }

        $reports = $query->orderBy('c.name')->orderBy('p.name')->get();

        $summary = [
            'total_models' => $reports->count(),
            'total_stock_units' => $reports->sum('stock'),
            'total_in_units' => $reports->sum('total_in'),
            'total_out_units' => $reports->sum('total_out'),
            'total_valuation' => $reports->sum('asset_value'),
        ];

        return view('reports.print', compact('reports', 'summary'));
    }
}
