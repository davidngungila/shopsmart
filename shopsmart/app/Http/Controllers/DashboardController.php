<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Total Sales (last 30 days)
            $totalSales = Sale::where('status', 'completed')
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('total') ?? 0;

            // Total Products
            $totalProducts = Product::count();
            $lowStockCount = Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')->count();

            // Total Customers
            $totalCustomers = Customer::count();
            $newCustomersToday = Customer::whereDate('created_at', today())->count();

            // Today's Orders
            $todayOrders = Sale::whereDate('created_at', today())->count();
            $pendingOrders = Sale::where('status', 'pending')->count();

            // Recent Sales
            $recentSales = Sale::with('customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // If tables don't exist yet or there's an error, return defaults
            $totalSales = 0;
            $totalProducts = 0;
            $lowStockCount = 0;
            $totalCustomers = 0;
            $newCustomersToday = 0;
            $todayOrders = 0;
            $pendingOrders = 0;
            $recentSales = collect();
        }

        return view('dashboard', compact(
            'totalSales',
            'totalProducts',
            'lowStockCount',
            'totalCustomers',
            'newCustomersToday',
            'todayOrders',
            'pendingOrders',
            'recentSales'
        ));
    }
}
