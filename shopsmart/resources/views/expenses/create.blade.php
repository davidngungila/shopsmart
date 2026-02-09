@extends('layouts.app')

@section('title', 'Add Expense')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add New Expense</h1>
        <p class="text-gray-600 mt-1">Record a new business expense</p>
    </div>

    <form action="{{ route('expenses.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Expense Category *</label>
                <input 
                    type="text" 
                    name="category" 
                    id="category" 
                    value="{{ old('category') }}" 
                    required 
                    list="category-suggestions"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="e.g., Office Supplies, Utilities, Travel"
                >
                <datalist id="category-suggestions">
                    @php
                        try {
                            $existingCategories = \App\Models\Expense::distinct()->pluck('category')->filter();
                            $expenseCategories = collect();
                            if (class_exists(\App\Models\ExpenseCategory::class)) {
                                $expenseCategories = \App\Models\ExpenseCategory::where('is_active', true)->pluck('name');
                            }
                            $allCategories = $existingCategories->merge($expenseCategories)->unique()->sort();
                        } catch (\Exception $e) {
                            $allCategories = \App\Models\Expense::distinct()->pluck('category')->filter();
                        }
                    @endphp
                    @foreach($allCategories as $cat)
                        <option value="{{ $cat }}">
                    @endforeach
                </datalist>
                @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Type to search existing categories or enter a new one</p>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (TZS) *</label>
                <input 
                    type="number" 
                    name="amount" 
                    id="amount" 
                    value="{{ old('amount') }}" 
                    required 
                    min="0" 
                    step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="0.00"
                >
                @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">Expense Date *</label>
                <input 
                    type="date" 
                    name="expense_date" 
                    id="expense_date" 
                    value="{{ old('expense_date', date('Y-m-d')) }}" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                @error('expense_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <select 
                    name="payment_method" 
                    id="payment_method" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                >
                    <option value="">Select payment method</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                </select>
                @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    placeholder="Enter expense description or notes..."
                >{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('expenses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                Record Expense
            </button>
        </div>
    </form>
</div>
@endsection

