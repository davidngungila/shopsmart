<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationReportController extends Controller
{
    public function overview()
    {
        $totalQuotations = Quotation::count();
        $pendingQuotations = Quotation::where('status', 'pending')->count();
        $approvedQuotations = Quotation::where('status', 'approved')->count();
        $rejectedQuotations = Quotation::where('status', 'rejected')->count();
        $convertedQuotations = Quotation::where('status', 'converted')->count();
        $expiredQuotations = Quotation::where('status', '!=', 'converted')
            ->where('expiry_date', '<', now())
            ->count();

        $totalValue = Quotation::sum('total');
        $convertedValue = Quotation::where('status', 'converted')->sum('total');
        $pendingValue = Quotation::where('status', 'pending')->sum('total');

        $conversionRate = $totalQuotations > 0 ? ($convertedQuotations / $totalQuotations) * 100 : 0;

        // Monthly statistics
        $monthlyStats = Quotation::select(
            DB::raw('DATE_FORMAT(quotation_date, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(total) as total')
        )
        ->where('quotation_date', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top customers by quotation value
        $topCustomers = Quotation::select('customers.name', DB::raw('COUNT(quotations.id) as count'), DB::raw('SUM(quotations.total) as total'))
            ->join('customers', 'quotations.customer_id', '=', 'customers.id')
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('quotations.reports.overview', compact(
            'totalQuotations',
            'pendingQuotations',
            'approvedQuotations',
            'rejectedQuotations',
            'convertedQuotations',
            'expiredQuotations',
            'totalValue',
            'convertedValue',
            'pendingValue',
            'conversionRate',
            'monthlyStats',
            'topCustomers'
        ));
    }
}
