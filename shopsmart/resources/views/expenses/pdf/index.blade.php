<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4; margin: 20mm; }
        body { 
            font-family: 'DM Sans', 'Roboto', Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.6;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #009245;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #009245;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            font-size: 12px;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #dc2626;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #009245;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        .expense-number { width: 15%; }
        .date { width: 12%; }
        .category { width: 18%; }
        .description { width: 25%; }
        .payment { width: 15%; }
        .amount { width: 15%; text-align: right; font-weight: 600; color: #dc2626; }
        .total-row {
            background-color: #f3f4f6;
            font-weight: 700;
            border-top: 2px solid #009245;
        }
        .category-breakdown {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .category-breakdown h3 {
            font-size: 14px;
            color: #009245;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #009245;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>EXPENSES REPORT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Expenses</div>
            <div class="summary-value">TZS {{ number_format($totalAmount, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Number of Expenses</div>
            <div class="summary-value">{{ number_format($expenses->count(), 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="expense-number">Expense #</th>
                <th class="date">Date</th>
                <th class="category">Category</th>
                <th class="description">Description</th>
                <th class="payment">Payment Method</th>
                <th class="amount">Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $expense->expense_number }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</td>
                <td>{{ $expense->category }}</td>
                <td>{{ \Illuminate\Support\Str::limit($expense->description ?? '-', 40) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $expense->payment_method)) }}</td>
                <td class="amount">{{ number_format($expense->amount, 0) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">No expenses found</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL</strong></td>
                <td class="amount"><strong>TZS {{ number_format($totalAmount, 0) }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($categoryBreakdown->count() > 0)
    <div class="category-breakdown">
        <h3>Category Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th style="text-align: right;">Count</th>
                    <th style="text-align: right;">Total Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryBreakdown as $breakdown)
                <tr>
                    <td>{{ $breakdown->category }}</td>
                    <td style="text-align: right;">{{ number_format($breakdown->count, 0) }}</td>
                    <td style="text-align: right; font-weight: 600; color: #dc2626;">{{ number_format($breakdown->total, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

