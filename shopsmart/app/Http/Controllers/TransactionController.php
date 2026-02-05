<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'category' => 'required|in:sale,purchase,salary,rent,utility,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank,mobile_money',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $validated['transaction_number'] = 'TXN-' . strtoupper(Str::random(8));
        $validated['user_id'] = auth()->id() ?? 1;
        
        Transaction::create($validated);
        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense,transfer',
            'category' => 'required|in:sale,purchase,salary,rent,utility,other',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank,mobile_money',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
