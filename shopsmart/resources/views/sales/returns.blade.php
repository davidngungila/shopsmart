@extends('layouts.app')

@section('title', 'Returns')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Returns</h1>
            <p class="text-gray-600 mt-1">View all returned sales</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sales ?? [] as $sale)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sale->invoice_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">-${{ number_format($sale->total, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No returns found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

