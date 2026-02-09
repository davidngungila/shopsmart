<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')->latest()->paginate(20);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,bank,mobile_money',
            'expense_date' => 'required|date',
        ]);

        $validated['expense_number'] = 'EXP-' . strtoupper(Str::random(8));
        $validated['user_id'] = auth()->id() ?? 1;
        
        Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'payment_method' => 'required|in:cash,card,bank,mobile_money',
            'expense_date' => 'required|date',
        ]);

        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function pdf(Request $request)
    {
        $expenses = Expense::with('user')->latest()->get();
        $totalAmount = $expenses->sum('amount');
        $categoryBreakdown = Expense::selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        $pdf = Pdf::loadView('expenses.pdf.index', compact('expenses', 'totalAmount', 'categoryBreakdown'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('expenses-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
