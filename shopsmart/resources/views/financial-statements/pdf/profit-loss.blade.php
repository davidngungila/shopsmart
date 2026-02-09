<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit & Loss Statement</title>
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
        .period {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #009245;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .amount {
            text-align: right;
            font-weight: 600;
        }
        .section-header {
            background-color: #f9fafb;
            font-weight: 700;
            font-size: 13px;
        }
        .total-row {
            background-color: #f3f4f6;
            font-weight: 700;
            border-top: 2px solid #009245;
        }
        .positive { color: #059669; }
        .negative { color: #dc2626; }
        .footer {
            margin-top: 40px;
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
        <h1>PROFIT & LOSS STATEMENT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="period">
        Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 70%;">Description</th>
                <th style="width: 30%;" class="amount">Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>
            <tr class="section-header">
                <td>REVENUE</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Sales Revenue</td>
                <td class="amount">{{ number_format($revenue, 0) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total Revenue</strong></td>
                <td class="amount"><strong>{{ number_format($revenue, 0) }}</strong></td>
            </tr>

            <tr class="section-header">
                <td>COST OF GOODS SOLD</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Purchases</td>
                <td class="amount">{{ number_format($cogs, 0) }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total COGS</strong></td>
                <td class="amount"><strong>{{ number_format($cogs, 0) }}</strong></td>
            </tr>

            <tr class="total-row">
                <td><strong>GROSS PROFIT</strong></td>
                <td class="amount {{ $grossProfit >= 0 ? 'positive' : 'negative' }}">
                    <strong>{{ number_format($grossProfit, 0) }}</strong>
                </td>
            </tr>

            <tr class="section-header">
                <td>OPERATING EXPENSES</td>
                <td></td>
            </tr>
            @forelse($expenseBreakdown as $expense)
            <tr>
                <td style="padding-left: 20px;">{{ $expense->category }}</td>
                <td class="amount">{{ number_format($expense->total, 0) }}</td>
            </tr>
            @empty
            <tr>
                <td style="padding-left: 20px;">No expenses recorded</td>
                <td class="amount">0</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td><strong>Total Operating Expenses</strong></td>
                <td class="amount"><strong>{{ number_format($operatingExpenses, 0) }}</strong></td>
            </tr>

            <tr class="total-row" style="background-color: #e6f5ed; font-size: 14px;">
                <td><strong>NET PROFIT / (LOSS)</strong></td>
                <td class="amount {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
                    <strong>{{ number_format($netProfit, 0) }}</strong>
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

