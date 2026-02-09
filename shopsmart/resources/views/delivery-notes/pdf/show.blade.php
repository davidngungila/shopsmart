<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note {{ $deliveryNote->delivery_number }}</title>
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
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }
        .info-box {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 4px;
        }
        .info-box h3 {
            font-size: 13px;
            color: #009245;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #009245;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            text-align: right;
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
        .item-name { width: 30%; }
        .quantity { width: 15%; text-align: right; }
        .unit { width: 15%; }
        .description { width: 40%; }
        .status-badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        .pending { background-color: #fef3c7; color: #92400e; }
        .in_transit { background-color: #dbeafe; color: #1e40af; }
        .delivered { background-color: #d1fae5; color: #065f46; }
        .cancelled { background-color: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        .notes {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
        }
        .notes h3 {
            font-size: 13px;
            color: #009245;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DELIVERY NOTE</h1>
        <p>{{ config('app.name', 'ShopSmart') }}</p>
        <p style="margin-top: 10px; font-size: 14px; font-weight: 600;">{{ $deliveryNote->delivery_number }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h3>Delivery Information</h3>
            <div class="info-row">
                <span class="info-label">Delivery Date:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($deliveryNote->delivery_date)->format('M d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Type:</span>
                <span class="info-value">{{ ucfirst($deliveryNote->type) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge {{ str_replace('-', '_', $deliveryNote->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $deliveryNote->status)) }}
                    </span>
                </span>
            </div>
            @if($deliveryNote->delivery_address)
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Delivery Address:</span>
                <span class="info-value" style="text-align: left;">{{ $deliveryNote->delivery_address }}</span>
            </div>
            @endif
        </div>

        <div class="info-box">
            <h3>{{ $deliveryNote->type === 'sale' ? 'Customer' : ($deliveryNote->type === 'purchase' ? 'Supplier' : 'Contact') }} Information</h3>
            @if($deliveryNote->customer)
            <div class="info-row">
                <span class="info-label">Customer:</span>
                <span class="info-value">{{ $deliveryNote->customer->name }}</span>
            </div>
            @if($deliveryNote->customer->phone)
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value">{{ $deliveryNote->customer->phone }}</span>
            </div>
            @endif
            @endif
            @if($deliveryNote->supplier)
            <div class="info-row">
                <span class="info-label">Supplier:</span>
                <span class="info-value">{{ $deliveryNote->supplier->name }}</span>
            </div>
            @endif
            @if($deliveryNote->contact_person)
            <div class="info-row">
                <span class="info-label">Contact Person:</span>
                <span class="info-value">{{ $deliveryNote->contact_person }}</span>
            </div>
            @endif
            @if($deliveryNote->contact_phone)
            <div class="info-row" style="border-bottom: none;">
                <span class="info-label">Contact Phone:</span>
                <span class="info-value">{{ $deliveryNote->contact_phone }}</span>
            </div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="item-name">Item Name</th>
                <th class="quantity">Quantity</th>
                <th class="unit">Unit</th>
                <th class="description">Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deliveryNote->items as $item)
            <tr>
                <td>{{ $item->item_name }}</td>
                <td class="quantity">{{ number_format($item->quantity, 0) }}</td>
                <td>{{ $item->unit ?? 'pcs' }}</td>
                <td>{{ $item->description ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px; color: #9ca3af;">No items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($deliveryNote->notes)
    <div class="notes">
        <h3>Notes</h3>
        <p>{{ $deliveryNote->notes }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>

