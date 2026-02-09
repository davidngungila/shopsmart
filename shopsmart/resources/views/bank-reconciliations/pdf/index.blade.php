<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Reconciliation Report</title>
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
        .reconciliation-number { width: 15%; }
        .account { width: 20%; }
        .date { width: 12%; }
        .balances { width: 20%; text-align: right; }
        .adjustments { width: 18%; text-align: right; }
        .status { width: 15%; text-align: center; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .reconciled { background-color: #d1fae5; color: #065f46; }
        .discrepancy { background-color: #fee2e2; color: #991b1b; }
        .pending { background-color: #fef3c7; color: #92400e; }
        .reconciliation-detail {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
            page-break-inside: avoid;
        }
        .reconciliation-detail h3 {
            font-size: 13px;
            color: #009245;
            margin-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-label {
            font-weight: 500;
        }
        .detail-value {
            font-weight: 600;
            text-align: right;
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
        <h1>BANK RECONCILIATION REPORT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="reconciliation-number">Reconciliation #</th>
                <th class="account">Account</th>
                <th class="date">Statement Date</th>
                <th class="balances">Bank Balance (TZS)</th>
                <th class="balances">Book Balance (TZS)</th>
                <th class="balances">Adjusted Balance (TZS)</th>
                <th class="status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reconciliations as $reconciliation)
            <tr>
                <td>{{ $reconciliation->reconciliation_number }}</td>
                <td>{{ $reconciliation->account->account_name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($reconciliation->statement_date)->format('M d, Y') }}</td>
                <td class="balances">{{ number_format($reconciliation->bank_balance, 0) }}</td>
                <td class="balances">{{ number_format($reconciliation->book_balance, 0) }}</td>
                <td class="balances" style="font-weight: 600;">{{ number_format($reconciliation->adjusted_balance, 0) }}</td>
                <td class="status">
                    <span class="status-badge {{ $reconciliation->status }}">
                        {{ ucfirst($reconciliation->status) }}
                    </span>
                </td>
            </tr>
            @if($reconciliation->deposits_in_transit || $reconciliation->outstanding_checks || $reconciliation->bank_charges || $reconciliation->interest_earned)
            <tr>
                <td colspan="7">
                    <div class="reconciliation-detail">
                        <h3>Reconciliation Details - {{ $reconciliation->reconciliation_number }}</h3>
                        <div class="detail-row">
                            <span class="detail-label">Deposits in Transit:</span>
                            <span class="detail-value">TZS {{ number_format($reconciliation->deposits_in_transit ?? 0, 0) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Outstanding Checks:</span>
                            <span class="detail-value">TZS {{ number_format($reconciliation->outstanding_checks ?? 0, 0) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Bank Charges:</span>
                            <span class="detail-value">TZS {{ number_format($reconciliation->bank_charges ?? 0, 0) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Interest Earned:</span>
                            <span class="detail-value">TZS {{ number_format($reconciliation->interest_earned ?? 0, 0) }}</span>
                        </div>
                        @if($reconciliation->notes)
                        <div class="detail-row" style="border-bottom: none; margin-top: 10px;">
                            <span class="detail-label">Notes:</span>
                            <span class="detail-value" style="text-align: left; font-weight: normal;">{{ $reconciliation->notes }}</span>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #9ca3af;">No reconciliations found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

