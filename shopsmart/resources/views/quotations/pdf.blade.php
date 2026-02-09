<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quotation_number }} - {{ $settings['company_name'] ?? 'ShopSmart' }}</title>
    <style>
        @page {
            margin: 10mm 12mm;
            size: A4;
        }
        body { 
            font-family: 'DM Sans', 'Roboto', Arial, sans-serif; 
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        /* Header Image - Full Width */
        .header {
            border-bottom: 3px solid #009245;
            padding-bottom: 15px;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        
        .header-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 15px auto;
        }
        
        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #009245 0%, #007a38 100%);
            padding: 15px 20px;
            color: #fff;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .quotation-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        .quotation-number {
            font-size: 18px;
            font-weight: 600;
            opacity: 0.95;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        .company-info {
            text-align: right;
            font-size: 11px;
            line-height: 1.8;
        }
        .company-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        /* Info Grid */
        .info-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 25px; 
            margin: 25px 30px;
            margin-bottom: 30px;
        }
        .info-section {
            background: #f9fafb;
            padding: 18px;
            border-radius: 8px;
            border-left: 4px solid #009245;
        }
        .info-section h3 { 
            color: #009245; 
            font-size: 10px; 
            text-transform: uppercase; 
            margin-bottom: 12px; 
            font-weight: 700;
            letter-spacing: 1px;
        }
        .info-section p { 
            font-size: 12px; 
            margin: 5px 0; 
            color: #374151;
        }
        .info-section strong {
            color: #1f2937;
            font-weight: 600;
        }
        .info-section .label {
            color: #6b7280;
            font-size: 11px;
            display: inline-block;
            min-width: 90px;
        }
        .customer-name {
            font-weight: 700;
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        /* Table */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 0 30px 25px 30px;
            background: #fff;
            font-size: 11px;
        }
        table thead {
            background: linear-gradient(135deg, #009245 0%, #007a38 100%);
            color: #fff;
        }
        table th { 
            padding: 12px 8px; 
            text-align: left; 
            font-size: 10px; 
            text-transform: uppercase; 
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        table th.text-right { text-align: right; }
        table th.text-center { text-align: center; }
        table td { 
            padding: 12px 8px; 
            border-bottom: 1px solid #e5e7eb; 
            color: #374151;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .product-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 3px;
            font-size: 12px;
        }
        .product-details {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }
        .product-sku {
            display: inline-block;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            margin-right: 6px;
        }
        
        /* Summary */
        .summary { 
            margin: 0 30px 25px 30px;
            max-width: 350px; 
            margin-left: auto; 
        }
        .summary-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 8px 0; 
            font-size: 12px;
        }
        .summary-row .label {
            color: #6b7280;
        }
        .summary-row .value {
            font-weight: 600;
            color: #1f2937;
        }
        .summary-row.total { 
            border-top: 3px solid #009245; 
            margin-top: 12px; 
            padding-top: 15px; 
            font-size: 18px; 
            font-weight: 700; 
        }
        .summary-row.total .value {
            color: #009245;
            font-size: 22px;
        }
        .summary-row.highlight {
            background: #f9fafb;
            padding: 8px 12px;
            border-radius: 5px;
            margin: 4px 0;
        }
        
        /* Additional Sections */
        .section {
            margin: 25px 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .section h3 {
            font-size: 12px;
            font-weight: 700;
            color: #009245;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-content {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.7;
            white-space: pre-line;
        }
        .notes-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            border-radius: 5px;
            margin-top: 8px;
        }
        .conversion-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 12px;
            border-radius: 5px;
            margin: 20px 30px;
        }
        .conversion-info strong {
            color: #1e40af;
        }
        
        /* Footer */
        .footer { 
            margin: 30px 30px 20px 30px;
            padding-top: 20px; 
            border-top: 2px solid #e5e7eb; 
            font-size: 10px; 
            color: #6b7280;
            text-align: center;
        }
        .footer p {
            margin: 4px 0;
        }
        .footer strong {
            color: #1f2937;
        }
        
        /* Expiry Warning */
        .expiry-warning {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            padding: 12px;
            border-radius: 5px;
            margin: 20px 30px;
            font-size: 11px;
            color: #991b1b;
        }
        .expiry-info {
            background: #d1fae5;
            border-left: 4px solid #059669;
            padding: 12px;
            border-radius: 5px;
            margin: 20px 30px;
            font-size: 11px;
            color: #065f46;
        }
        
        /* Print Styles */
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .header-section { page-break-inside: avoid; }
            table { page-break-inside: auto; }
            table tr { page-break-inside: avoid; page-break-after: auto; }
            .summary { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Header Image -->
    <div class="header">
        @php
            $headerImagePath = public_path('header-mfumo.png');
            $headerBase64 = '';
            if (file_exists($headerImagePath)) {
                $headerImageData = file_get_contents($headerImagePath);
                $headerBase64 = 'data:image/png;base64,' . base64_encode($headerImageData);
            }
        @endphp
        @if($headerBase64)
        <img src="{{ $headerBase64 }}" alt="FeedTan Header" class="header-image">
        @endif

        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <div>
                    <div class="quotation-title">QUOTATION</div>
                    <div class="quotation-number">#{{ $quotation->quotation_number }}</div>
                    <span class="status-badge">
                        {{ ucfirst($quotation->status) }}
                    </span>
                </div>
                <div class="company-info">
                    <div class="company-name">{{ $settings['company_name'] ?? 'ShopSmart' }}</div>
                    @if($settings['company_address'] ?? '')
                        <div>{{ $settings['company_address'] }}</div>
                    @endif
                    @if($settings['company_phone'] ?? '')
                        <div>Phone: {{ $settings['company_phone'] }}</div>
                    @endif
                    @if($settings['company_email'] ?? '')
                        <div>Email: {{ $settings['company_email'] }}</div>
                    @endif
                    @if($settings['company_website'] ?? '')
                        <div>Web: {{ $settings['company_website'] }}</div>
                    @endif
                    @if($settings['tax_id'] ?? '')
                        <div style="margin-top: 8px; font-weight: 600;">Tax ID: {{ $settings['tax_id'] }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expiry Warning/Info -->
        @if($quotation->expiry_date)
            @php
                $daysRemaining = now()->diffInDays($quotation->expiry_date, false);
            @endphp
            @if($daysRemaining < 0)
                <div class="expiry-warning">
                    <strong>⚠ EXPIRED:</strong> This quotation expired on {{ $quotation->expiry_date->format('F d, Y') }} ({{ abs($daysRemaining) }} {{ abs($daysRemaining) == 1 ? 'day' : 'days' }} ago)
                </div>
            @elseif($daysRemaining == 0)
                <div class="expiry-warning">
                    <strong>⚠ EXPIRES TODAY:</strong> This quotation expires on {{ $quotation->expiry_date->format('F d, Y') }}
                </div>
            @elseif($daysRemaining <= 7)
                <div class="expiry-info">
                    <strong>⏱ VALID UNTIL:</strong> {{ $quotation->expiry_date->format('F d, Y') }} ({{ $daysRemaining }} {{ $daysRemaining == 1 ? 'day' : 'days' }} remaining)
                </div>
            @endif
        @endif

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-section">
                <h3>Bill To</h3>
                <div class="customer-name">
                    {{ $quotation->customer->name ?? 'Walk-in Customer' }}
                </div>
                @if($quotation->customer)
                    @if($quotation->customer->email)
                        <p><span class="label">Email:</span> {{ $quotation->customer->email }}</p>
                    @endif
                    @if($quotation->customer->phone)
                        <p><span class="label">Phone:</span> {{ $quotation->customer->phone }}</p>
                    @endif
                    @if($quotation->customer->address)
                        <p><span class="label">Address:</span> {{ $quotation->customer->address }}</p>
                    @endif
                    @if($quotation->customer->tax_id)
                        <p><span class="label">Tax ID:</span> {{ $quotation->customer->tax_id }}</p>
                    @endif
                @else
                    <p style="color: #9ca3af; font-style: italic;">No customer information</p>
                @endif
            </div>
            <div class="info-section">
                <h3>Quotation Details</h3>
                <p><span class="label">Date:</span> <strong>{{ $quotation->quotation_date->format('F d, Y') }}</strong></p>
                <p><span class="label">Time:</span> {{ $quotation->created_at->format('h:i A') }}</p>
                @if($quotation->expiry_date)
                    <p><span class="label">Valid Until:</span> <strong>{{ $quotation->expiry_date->format('F d, Y') }}</strong></p>
                @endif
                @if($quotation->warehouse)
                    <p><span class="label">Warehouse:</span> {{ $quotation->warehouse->name }}</p>
                @endif
                @if($quotation->user)
                    <p><span class="label">Prepared By:</span> {{ $quotation->user->name }}</p>
                @endif
                @if($quotation->is_sent && $quotation->sent_at)
                    <p style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <span class="label">Sent:</span> {{ $quotation->sent_at->format('M d, Y h:i A') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Product / Description</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 15%;">Unit Price</th>
                    <th class="text-right" style="width: 15%;">Discount</th>
                    <th class="text-right" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                <tr>
                    <td class="text-center" style="color: #9ca3af;">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product->name }}</div>
                        <div class="product-details">
                            @if($item->product->sku)
                                <span class="product-sku">SKU: {{ $item->product->sku }}</span>
                            @endif
                            @if($item->product->category)
                                <span style="color: #6b7280;">{{ $item->product->category->name }}</span>
                            @endif
                        </div>
                        @if($item->description)
                            <div style="margin-top: 4px; font-size: 10px; color: #6b7280; font-style: italic;">
                                {{ $item->description }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ number_format($item->quantity) }}
                        @if($item->product->unit)
                            <span style="color: #9ca3af; font-size: 9px;">{{ $item->product->unit }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($item->unit_price, 0) }} TZS</strong>
                    </td>
                    <td class="text-right">
                        @if($item->discount > 0)
                            <span style="color: #dc2626;">-{{ number_format($item->discount, 0) }} TZS</span>
                        @else
                            <span style="color: #9ca3af;">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <strong style="color: #009245;">{{ number_format($item->total, 0) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span class="label">Subtotal:</span>
                <span class="value">{{ number_format($quotation->subtotal ?? 0, 0) }} TZS</span>
            </div>
            @if(($quotation->discount ?? 0) > 0)
            <div class="summary-row highlight">
                <span class="label">Total Discount:</span>
                <span class="value" style="color: #dc2626;">-{{ number_format($quotation->discount, 0) }} TZS</span>
            </div>
            @endif
            <div class="summary-row">
                <span class="label">Tax:</span>
                <span class="value">{{ number_format($quotation->tax ?? 0, 0) }} TZS</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span class="value">{{ number_format($quotation->total ?? 0, 0) }} TZS</span>
            </div>
        </div>

        <!-- Conversion Info -->
        @if($quotation->status === 'converted' && $quotation->converted_to_sale_id)
        <div class="conversion-info">
            <strong>✓ This quotation has been converted to a sale.</strong><br>
            <span style="font-size: 10px;">
                Invoice Number: <strong>{{ $quotation->sale->invoice_number ?? 'N/A' }}</strong><br>
                Converted on: {{ $quotation->updated_at->format('F d, Y h:i A') }}
            </span>
        </div>
        @endif

        <!-- Terms & Conditions -->
        @if($quotation->terms_conditions)
        <div class="section">
            <h3>Terms & Conditions</h3>
            <div class="section-content">{{ $quotation->terms_conditions }}</div>
        </div>
        @endif

        <!-- Notes -->
        @if($quotation->notes)
        <div class="section">
            <h3>Additional Notes</h3>
            <div class="notes-box">
                {{ $quotation->notes }}
            </div>
        </div>
        @endif

        <!-- Customer Notes -->
        @if($quotation->customer_notes)
        <div class="section">
            <h3>Customer Notes</h3>
            <div class="section-content">{{ $quotation->customer_notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated quotation. No signature required.</p>
            <p style="margin-top: 12px; font-size: 9px; color: #9ca3af;">
                Generated on {{ now()->format('F d, Y \a\t h:i A') }} | 
                Quotation #{{ $quotation->quotation_number }}
            </p>
        </div>
    </div>
</body>
</html>
