<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chart of Accounts</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 landscape; margin: 15mm; }
        body { 
            font-family: 'DM Sans', 'Roboto', Arial, sans-serif; 
            font-size: 9px;
            line-height: 1.4;
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
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-label {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: 700;
            color: #009245;
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
            font-size: 9px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }
        .account-code { width: 10%; }
        .account-name { width: 25%; }
        .account-type { width: 12%; }
        .account-category { width: 15%; }
        .balance { width: 15%; text-align: right; font-weight: 600; }
        .status { width: 10%; text-align: center; }
        .description { width: 13%; }
        .section-header {
            background-color: #f3f4f6;
            font-weight: 700;
        }
        .inactive {
            color: #9ca3af;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHART OF ACCOUNTS</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Accounts</div>
            <div class="summary-value">{{ number_format($totalAccounts, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Total Balance</div>
            <div class="summary-value">TZS {{ number_format($totalBalance, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Generated</div>
            <div class="summary-value">{{ now()->format('M d, Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="account-code">Code</th>
                <th class="account-name">Account Name</th>
                <th class="account-type">Type</th>
                <th class="account-category">Category</th>
                <th class="balance">Balance (TZS)</th>
                <th class="status">Status</th>
                <th class="description">Description</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentType = '';
            @endphp
            @foreach($accounts as $account)
                @if($currentType !== $account->account_type)
                    @php
                        $currentType = $account->account_type;
                    @endphp
                    <tr class="section-header">
                        <td colspan="7" style="padding: 8px; text-transform: uppercase;">{{ ucfirst($account->account_type) }} Accounts</td>
                    </tr>
                @endif
                <tr class="{{ !$account->is_active ? 'inactive' : '' }}">
                    <td>{{ $account->account_code }}</td>
                    <td>{{ $account->account_name }}</td>
                    <td>{{ ucfirst($account->account_type) }}</td>
                    <td>{{ $account->account_category ?? '-' }}</td>
                    <td class="balance">{{ number_format($account->current_balance, 0) }}</td>
                    <td class="status">
                        @if($account->is_active)
                            <span style="color: #059669;">Active</span>
                        @else
                            <span style="color: #9ca3af;">Inactive</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($account->description ?? '-', 30) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

