<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Notes Report</title>
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
        .delivery-number { width: 12%; }
        .type { width: 10%; }
        .customer-supplier { width: 18%; }
        .date { width: 10%; }
        .items { width: 8%; text-align: center; }
        .address { width: 20%; }
        .status { width: 12%; text-align: center; }
        .contact { width: 10%; }
        .status-badge {
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            display: inline-block;
        }
        .pending { background-color: #fef3c7; color: #92400e; }
        .in_transit { background-color: #dbeafe; color: #1e40af; }
        .delivered { background-color: #d1fae5; color: #065f46; }
        .cancelled { background-color: #fee2e2; color: #991b1b; }
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
        <h1>DELIVERY NOTES REPORT</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="delivery-number">Delivery #</th>
                <th class="type">Type</th>
                <th class="customer-supplier">Customer/Supplier</th>
                <th class="date">Delivery Date</th>
                <th class="items">Items</th>
                <th class="address">Delivery Address</th>
                <th class="contact">Contact</th>
                <th class="status">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deliveryNotes as $note)
            <tr>
                <td>{{ $note->delivery_number }}</td>
                <td>{{ ucfirst($note->type) }}</td>
                <td>
                    @if($note->customer)
                        {{ $note->customer->name }}
                    @elseif($note->supplier)
                        {{ $note->supplier->name }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($note->delivery_date)->format('M d, Y') }}</td>
                <td class="items">{{ $note->items->count() }}</td>
                <td>{{ \Illuminate\Support\Str::limit($note->delivery_address ?? '-', 30) }}</td>
                <td>{{ $note->contact_person ?? '-' }}</td>
                <td class="status">
                    <span class="status-badge {{ str_replace('-', '_', $note->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $note->status)) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #9ca3af;">No delivery notes found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>Total: {{ $deliveryNotes->count() }} delivery notes</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

