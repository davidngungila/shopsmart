<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Capital Report</title>
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
            grid-template-columns: repeat(3, 1fr);
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
            font-size: 16px;
            font-weight: 700;
        }
        .positive { color: #059669; }
        .negative { color: #dc2626; }
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
        .transaction-number { width: 15%; }
        .date { width: 12%; }
        .type { width: 15%; }
        .description { width: 30%; }
        .reference { width: 15%; }
        .amount { width: 13%; text-align: right; font-weight: 600; }
        .contribution { color: #059669; }
        .withdrawal { color: #dc2626; }
        .total-row {
            background-color: #f3f4f6;
            font-weight: 700;
            border-top: 2px solid #009245;
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
        <h1>CAPITAL REPORT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Contributions</div>
            <div class="summary-value positive">TZS {{ number_format($totalContributions, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Withdrawals</div>
            <div class="summary-value negative">TZS {{ number_format($totalWithdrawals, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Net Capital</div>
            <div class="summary-value {{ $netCapital >= 0 ? 'positive' : 'negative' }}">TZS {{ number_format($netCapital, 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="transaction-number">Transaction #</th>
                <th class="date">Date</th>
                <th class="type">Type</th>
                <th class="description">Description</th>
                <th class="reference">Reference</th>
                <th class="amount">Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($capitals as $capital)
            <tr>
                <td>{{ $capital->transaction_number }}</td>
                <td>{{ \Carbon\Carbon::parse($capital->transaction_date)->format('M d, Y') }}</td>
                <td>
                    <span class="{{ $capital->type === 'contribution' || $capital->type === 'profit' ? 'contribution' : 'withdrawal' }}">
                        {{ ucfirst($capital->type) }}
                    </span>
                </td>
                <td>{{ \Illuminate\Support\Str::limit($capital->description ?? '-', 40) }}</td>
                <td>{{ $capital->reference ?? '-' }}</td>
                <td class="amount {{ $capital->type === 'contribution' || $capital->type === 'profit' ? 'contribution' : 'withdrawal' }}">
                    {{ number_format($capital->amount, 0) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">No capital transactions found</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5"><strong>NET CAPITAL</strong></td>
                <td class="amount {{ $netCapital >= 0 ? 'positive' : 'negative' }}">
                    <strong>TZS {{ number_format($netCapital, 0) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

