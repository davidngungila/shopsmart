<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Liabilities Report</title>
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
        .liability-number { width: 12%; }
        .name { width: 20%; }
        .type { width: 12%; }
        .dates { width: 18%; }
        .principal { width: 12%; text-align: right; }
        .outstanding { width: 12%; text-align: right; font-weight: 600; color: #dc2626; }
        .status { width: 14%; text-align: center; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .active { background-color: #dbeafe; color: #1e40af; }
        .paid { background-color: #d1fae5; color: #065f46; }
        .overdue { background-color: #fee2e2; color: #991b1b; }
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
        <h1>LIABILITIES REPORT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Liabilities</div>
            <div class="summary-value">TZS {{ number_format($totalLiabilities, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Active Liabilities</div>
            <div class="summary-value">TZS {{ number_format($activeLiabilities, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Overdue Liabilities</div>
            <div class="summary-value">TZS {{ number_format($overdueLiabilities, 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="liability-number">Liability #</th>
                <th class="name">Name</th>
                <th class="type">Type</th>
                <th class="dates">Start / Due Date</th>
                <th class="principal">Principal (TZS)</th>
                <th class="outstanding">Outstanding (TZS)</th>
                <th class="status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($liabilities as $liability)
            <tr>
                <td>{{ $liability->liability_number }}</td>
                <td>{{ $liability->name }}</td>
                <td>{{ ucfirst($liability->type) }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($liability->start_date)->format('M d, Y') }}
                    @if($liability->due_date)
                    <br><span style="font-size: 9px; color: #6b7280;">Due: {{ \Carbon\Carbon::parse($liability->due_date)->format('M d, Y') }}</span>
                    @endif
                </td>
                <td class="principal">{{ number_format($liability->principal_amount, 0) }}</td>
                <td class="outstanding">{{ number_format($liability->outstanding_balance, 0) }}</td>
                <td class="status">
                    <span class="status-badge {{ $liability->status }}">
                        {{ ucfirst($liability->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No liabilities found</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5"><strong>TOTAL OUTSTANDING</strong></td>
                <td class="outstanding"><strong>TZS {{ number_format($totalLiabilities, 0) }}</strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

