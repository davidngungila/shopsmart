<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trial Balance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 landscape; margin: 15mm; }
        body { 
            font-family: 'DM Sans', 'Roboto', Arial, sans-serif; 
            font-size: 10px;
            line-height: 1.5;
            color: #1f2937;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #009245;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #009245;
            margin-bottom: 5px;
        }
        .header p {
            color: #6b7280;
            font-size: 11px;
        }
        .as-of-date {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #009245;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .account-code {
            width: 10%;
        }
        .account-name {
            width: 40%;
        }
        .account-type {
            width: 15%;
        }
        .debit, .credit {
            width: 17.5%;
            text-align: right;
            font-weight: 600;
        }
        .section-header {
            background-color: #f3f4f6;
            font-weight: 700;
        }
        .total-row {
            background-color: #f3f4f6;
            font-weight: 700;
            border-top: 2px solid #009245;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TRIAL BALANCE</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="as-of-date">
        As of: {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="account-code">Account Code</th>
                <th class="account-name">Account Name</th>
                <th class="account-type">Type</th>
                <th class="debit">Debit (TZS)</th>
                <th class="credit">Credit (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentType = '';
            @endphp
            @foreach($accounts as $account)
                @if($currentType !== $account['type'])
                    @php
                        $currentType = $account['type'];
                    @endphp
                    <tr class="section-header">
                        <td colspan="5" style="padding: 8px; text-transform: uppercase;">{{ ucfirst($account['type']) }} Accounts</td>
                    </tr>
                @endif
                <tr>
                    <td>{{ $account['code'] }}</td>
                    <td>{{ $account['name'] }}</td>
                    <td>{{ ucfirst($account['type']) }}</td>
                    <td class="debit">{{ $account['debit'] > 0 ? number_format($account['debit'], 0) : '-' }}</td>
                    <td class="credit">{{ $account['credit'] > 0 ? number_format($account['credit'], 0) : '-' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3"><strong>TOTAL</strong></td>
                <td class="debit"><strong>{{ number_format($totalDebit, 0) }}</strong></td>
                <td class="credit"><strong>{{ number_format($totalCredit, 0) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

