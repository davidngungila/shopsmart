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
            'reference_number' => 'nullable|string|max:100',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_reference' => 'nullable|string|max:100',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_contact' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'receipt' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240',
        ]);

        // Build description with additional details
        $descriptionParts = [];
        if ($request->filled('description')) {
            $descriptionParts[] = $request->description;
        }
        if ($request->filled('vendor_name')) {
            $descriptionParts[] = "Vendor: " . $request->vendor_name;
        }
        if ($request->filled('reference_number')) {
            $descriptionParts[] = "Reference: " . $request->reference_number;
        }
        if ($request->filled('location')) {
            $descriptionParts[] = "Location: " . $request->location;
        }
        if ($request->filled('tags')) {
            $descriptionParts[] = "Tags: " . $request->tags;
        }
        if ($request->filled('payment_reference')) {
            $descriptionParts[] = "Payment Ref: " . $request->payment_reference;
        }
        
        $validated['description'] = implode("\n", $descriptionParts);
        
        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = 'expense_' . time() . '_' . $file->getClientOriginalName();
            $file->storeAs('expenses/receipts', $fileName, 'public');
            $validated['receipt'] = 'expenses/receipts/' . $fileName;
        }

        // Calculate total amount including tax and discount
        $baseAmount = $validated['amount'];
        $taxAmount = $validated['tax_amount'] ?? 0;
        $discount = $validated['discount'] ?? 0;
        $validated['amount'] = $baseAmount + $taxAmount - $discount;

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
