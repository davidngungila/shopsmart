<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Categories</title>
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
            color: #009245;
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
        .code { width: 12%; }
        .name { width: 30%; }
        .account { width: 25%; }
        .status { width: 15%; text-align: center; }
        .description { width: 18%; }
        .inactive {
            color: #9ca3af;
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
        <h1>EXPENSE CATEGORIES</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Total Categories</div>
            <div class="summary-value">{{ number_format($totalCategories, 0) }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Active Categories</div>
            <div class="summary-value">{{ number_format($activeCategories, 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="code">Code</th>
                <th class="name">Category Name</th>
                <th class="account">Linked Account</th>
                <th class="status">Status</th>
                <th class="description">Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr class="{{ !$category->is_active ? 'inactive' : '' }}">
                <td>{{ $category->code ?? '-' }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->account->account_name ?? '-' }}</td>
                <td class="status">
                    @if($category->is_active)
                        <span style="color: #059669;">Active</span>
                    @else
                        <span style="color: #9ca3af;">Inactive</span>
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit($category->description ?? '-', 40) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: #9ca3af;">No categories found</td>
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

