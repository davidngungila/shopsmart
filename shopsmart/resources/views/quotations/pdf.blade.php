<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 40px; color: #333; }
        .header { border-bottom: 3px solid #9333ea; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #9333ea; font-size: 28px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-section h3 { color: #666; font-size: 12px; text-transform: uppercase; margin-bottom: 8px; }
        .info-section p { font-size: 14px; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table th { background: #f3f4f6; padding: 12px; text-align: left; font-size: 12px; text-transform: uppercase; color: #666; }
        table td { padding: 12px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .text-right { text-align: right; }
        .summary { margin-top: 30px; }
        .summary-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .summary-row.total { border-top: 2px solid #9333ea; margin-top: 10px; padding-top: 15px; font-size: 18px; font-weight: bold; }
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #666; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>QUOTATION</h1>
        <p style="font-size: 18px; font-weight: bold;">#{{ $quotation->quotation_number }}</p>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>Bill To</h3>
            <p style="font-weight: bold; font-size: 16px;">{{ $quotation->customer->name ?? 'Walk-in Customer' }}</p>
            @if($quotation->customer)
            <p>{{ $quotation->customer->email ?? '' }}</p>
            <p>{{ $quotation->customer->phone ?? '' }}</p>
            <p>{{ $quotation->customer->address ?? '' }}</p>
            @endif
        </div>
        <div class="info-section">
            <h3>Quotation Details</h3>
            <p><strong>Date:</strong> {{ $quotation->quotation_date->format('F d, Y') }}</p>
            @if($quotation->expiry_date)
            <p><strong>Valid Until:</strong> {{ $quotation->expiry_date->format('F d, Y') }}</p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($quotation->status) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Discount</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product->name }}</strong>
                    @if($item->description)
                    <br><small style="color: #666;">{{ $item->description }}</small>
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->discount, 2) }}</td>
                <td class="text-right"><strong>${{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary" style="max-width: 400px; margin-left: auto;">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>${{ number_format($quotation->subtotal, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Discount:</span>
            <span>${{ number_format($quotation->discount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Tax (10%):</span>
            <span>${{ number_format($quotation->tax, 2) }}</span>
        </div>
        <div class="summary-row total">
            <span>Total:</span>
            <span>${{ number_format($quotation->total, 2) }}</span>
        </div>
    </div>

    @if($quotation->terms_conditions)
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
        <h3 style="font-size: 14px; margin-bottom: 10px; color: #666;">Terms & Conditions</h3>
        <p style="font-size: 12px; line-height: 1.6; white-space: pre-line;">{{ $quotation->terms_conditions }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated quotation. No signature required.</p>
    </div>
</body>
</html>

