@extends('layouts.app')

@section('title', 'Add New Expense')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add New Expense</h1>
        <p class="text-gray-600 mt-1">Record a detailed business expense with full information</p>
    </div>

    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-8" id="expenseForm">
        @csrf

        <!-- Basic Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Expense Category *
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="category" 
                            id="category" 
                            value="{{ old('category') }}" 
                            required 
                            list="category-suggestions"
                            autocomplete="off"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                            placeholder="Type to search or enter new category..."
                        >
                        <datalist id="category-suggestions">
                            @php
                                try {
                                    $existingCategories = \App\Models\Expense::distinct()->pluck('category')->filter();
                                    $expenseCategories = collect();
                                    if (class_exists(\App\Models\ExpenseCategory::class)) {
                                        $expenseCategories = \App\Models\ExpenseCategory::where('is_active', true)->orderBy('name')->pluck('name');
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
                        <button type="button" onclick="document.getElementById('category').value=''; document.getElementById('category').focus();" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600" title="Clear">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @error('category')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Select from existing categories or create a new one</p>
                </div>

                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Expense Date *
                        <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="expense_date" 
                        id="expense_date" 
                        value="{{ old('expense_date', date('Y-m-d')) }}" 
                        required 
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    >
                    @error('expense_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number / Invoice #
                    </label>
                    <input 
                        type="text" 
                        name="reference_number" 
                        id="reference_number" 
                        value="{{ old('reference_number') }}" 
                        maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., INV-2026-001, PO-12345"
                    >
                    @error('reference_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Invoice, receipt, or purchase order number</p>
                </div>
            </div>
        </div>

        <!-- Financial Details Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Financial Details
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount (TZS) *
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">TZS</span>
                        <input 
                            type="number" 
                            name="amount" 
                            id="amount" 
                            value="{{ old('amount') }}" 
                            required 
                            min="0" 
                            step="0.01"
                            oninput="calculateTotal()"
                            class="w-full pl-16 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                            placeholder="0.00"
                        >
                    </div>
                    @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Tax / VAT Amount (TZS)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">TZS</span>
                        <input 
                            type="number" 
                            name="tax_amount" 
                            id="tax_amount" 
                            value="{{ old('tax_amount', 0) }}" 
                            min="0" 
                            step="0.01"
                            oninput="calculateTotal()"
                            class="w-full pl-16 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                            placeholder="0.00"
                        >
                    </div>
                    @error('tax_amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                        Discount (TZS)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">TZS</span>
                        <input 
                            type="number" 
                            name="discount" 
                            id="discount" 
                            value="{{ old('discount', 0) }}" 
                            min="0" 
                            step="0.01"
                            oninput="calculateTotal()"
                            class="w-full pl-16 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                            placeholder="0.00"
                        >
                    </div>
                    @error('discount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Total Amount (TZS)
                    </label>
                    <div class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                        <p class="text-lg font-bold text-[#009245]" id="totalAmount">TZS 0.00</p>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Calculated: Amount + Tax - Discount</p>
                </div>
            </div>
        </div>

        <!-- Payment Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Payment Information
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method *
                        <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="payment_method" 
                        id="payment_method" 
                        required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                    >
                        <option value="">Select payment method</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash</option>
                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>💳 Card / Debit Card</option>
                        <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money (M-Pesa, Tigo Pesa, etc.)</option>
                    </select>
                    @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Reference / Transaction ID
                    </label>
                    <input 
                        type="text" 
                        name="payment_reference" 
                        id="payment_reference" 
                        value="{{ old('payment_reference') }}" 
                        maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., Transaction ID, Check #, Reference #"
                    >
                    @error('payment_reference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Additional Details Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Additional Details
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description / Notes
                    </label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="Enter detailed description of the expense, purpose, and any relevant notes..."
                    >{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Provide detailed information about this expense</p>
                </div>

                <div>
                    <label for="vendor_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Vendor / Supplier Name
                    </label>
                    <input 
                        type="text" 
                        name="vendor_name" 
                        id="vendor_name" 
                        value="{{ old('vendor_name') }}" 
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., ABC Supplies Ltd, John Doe"
                    >
                    @error('vendor_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="vendor_contact" class="block text-sm font-medium text-gray-700 mb-2">
                        Vendor Contact (Phone/Email)
                    </label>
                    <input 
                        type="text" 
                        name="vendor_contact" 
                        id="vendor_contact" 
                        value="{{ old('vendor_contact') }}" 
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., +255 123 456 789 or email@example.com"
                    >
                    @error('vendor_contact')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location / Department
                    </label>
                    <input 
                        type="text" 
                        name="location" 
                        id="location" 
                        value="{{ old('location') }}" 
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., Main Office, Warehouse, Sales Department"
                    >
                    @error('location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags (comma-separated)
                    </label>
                    <input 
                        type="text" 
                        name="tags" 
                        id="tags" 
                        value="{{ old('tags') }}" 
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent"
                        placeholder="e.g., urgent, office, travel, equipment"
                    >
                    @error('tags')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Add tags to categorize and filter expenses</p>
                </div>
            </div>
        </div>

        <!-- Receipt & Documents Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#009245]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Receipt & Documents
            </h2>
            <div>
                <label for="receipt" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Receipt / Invoice (PDF, Image)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#009245] transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="receipt" class="relative cursor-pointer rounded-md font-medium text-[#009245] hover:text-[#007a38] focus-within:outline-none focus-within:ring-2 focus-within:ring-[#009245] focus-within:ring-offset-2">
                                <span>Upload a file</span>
                                <input id="receipt" name="receipt" type="file" accept="image/*,.pdf" class="sr-only" onchange="handleFileSelect(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB</p>
                        <p class="text-xs text-gray-400 mt-2" id="fileName"></p>
                    </div>
                </div>
                @error('receipt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                <span class="text-red-500">*</span> Required fields
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('expenses.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 text-white rounded-lg font-semibold transition-colors" style="background-color: #009245;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                    <span class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Record Expense</span>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function calculateTotal() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total = amount + tax - discount;
    
    document.getElementById('totalAmount').textContent = 'TZS ' + total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
        document.getElementById('fileName').textContent = `Selected: ${fileName} (${fileSize} MB)`;
        document.getElementById('fileName').classList.remove('text-gray-400');
        document.getElementById('fileName').classList.add('text-[#009245]', 'font-semibold');
    } else {
        document.getElementById('fileName').textContent = '';
    }
}

// Initialize total on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    
    // Set max date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('expense_date').setAttribute('max', today);
});
</script>
@endsection
