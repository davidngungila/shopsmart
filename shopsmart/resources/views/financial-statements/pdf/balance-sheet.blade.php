<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>
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
        .as-of-date {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .balance-sheet {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 20px;
        }
        .section {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
        }
        .section-header {
            background-color: #009245;
            color: white;
            padding: 12px;
            font-weight: 700;
            font-size: 13px;
        }
        .section-body {
            padding: 15px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item-label {
            font-weight: 500;
        }
        .item-value {
            font-weight: 600;
            text-align: right;
        }
        .total {
            background-color: #f3f4f6;
            padding: 12px;
            margin-top: 10px;
            border-top: 2px solid #009245;
            font-weight: 700;
            font-size: 13px;
        }
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
        <h1>BALANCE SHEET</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="as-of-date">
        As of: {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}
    </div>

    <div class="balance-sheet">
        <!-- Assets -->
        <div class="section">
            <div class="section-header">ASSETS</div>
            <div class="section-body">
                <div class="item">
                    <span class="item-label">Current Assets</span>
                    <span class="item-value">{{ number_format($currentAssets, 0) }}</span>
                </div>
                <div class="item">
                    <span class="item-label">Fixed Assets</span>
                    <span class="item-value">{{ number_format($fixedAssets, 0) }}</span>
                </div>
                <div class="total">
                    <div class="item">
                        <span>Total Assets</span>
                        <span>{{ number_format($totalAssets, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity -->
        <div class="section">
            <div class="section-header">LIABILITIES & EQUITY</div>
            <div class="section-body">
                <div class="item">
                    <span class="item-label">Current Liabilities</span>
                    <span class="item-value">{{ number_format($currentLiabilities, 0) }}</span>
                </div>
                <div class="item">
                    <span class="item-label">Long-term Liabilities</span>
                    <span class="item-value">{{ number_format($longTermLiabilities, 0) }}</span>
                </div>
                <div class="total" style="margin-top: 20px;">
                    <div class="item">
                        <span>Total Liabilities</span>
                        <span>{{ number_format($totalLiabilities, 0) }}</span>
                    </div>
                </div>
                <div class="item" style="margin-top: 15px;">
                    <span class="item-label">Capital</span>
                    <span class="item-value">{{ number_format($capital, 0) }}</span>
                </div>
                <div class="item">
                    <span class="item-label">Retained Earnings</span>
                    <span class="item-value">{{ number_format($retainedEarnings, 0) }}</span>
                </div>
                <div class="total">
                    <div class="item">
                        <span>Total Equity</span>
                        <span>{{ number_format($totalEquity, 0) }}</span>
                    </div>
                </div>
                <div class="total" style="background-color: #e6f5ed; margin-top: 15px;">
                    <div class="item">
                        <span>Total Liabilities & Equity</span>
                        <span>{{ number_format($totalLiabilities + $totalEquity, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

