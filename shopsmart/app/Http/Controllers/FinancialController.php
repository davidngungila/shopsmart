<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        // Total Income (Sales)
        $totalIncome = Sale::where('status', 'completed')->sum('total');
        
        // Total Expenses
        $totalExpenses = Expense::sum('amount');
        
        // Total Purchases
        $totalPurchases = Purchase::sum('total');
        
        // Net Profit
        $netProfit = $totalIncome - $totalExpenses - $totalPurchases;
        
        // Recent Transactions
        $recentTransactions = Transaction::latest()->limit(10)->get();
        
        // Monthly Income (last 6 months)
        $monthlyIncome = Sale::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('financial.index', compact(
            'totalIncome',
            'totalExpenses',
            'totalPurchases',
            'netProfit',
            'recentTransactions',
            'monthlyIncome'
        ));
    }

    public function income()
    {
        $income = \App\Models\Sale::where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(30);
        return view('financial.income', compact('income'));
    }

    public function profitLoss()
    {
        $sales = \App\Models\Sale::where('status', 'completed')->sum('total');
        $purchases = \App\Models\Purchase::sum('total');
        $expenses = \App\Models\Expense::sum('amount');
        $profit = $sales - $purchases - $expenses;
        
        return view('financial.profit-loss', compact('sales', 'purchases', 'expenses', 'profit'));
    }
}
