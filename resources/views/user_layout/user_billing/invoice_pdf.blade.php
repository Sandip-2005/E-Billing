<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 13px;
        color: #0f172a;
        margin: 0;
        padding: 10px;
        background: #ffffff;
    }

    .invoice-container {
        max-width: 850px;
        margin: auto;
        border: 1px solid #cbd5e1;
    }

    .invoice-header {
        background: #b2caff;
        color: #000000;
        padding: 10px;
        text-align: center;
        border-bottom: 4px solid #1e40af;
    }

    .invoice-header h1 {
        margin: 0;
        font-size: 28px;
        letter-spacing: 2px;
    }

    .invoice-header p {
        margin: 5px 0 0;
        font-size: 14px;
    }

    .badge-paid {
        background: #10b981;
        color: #fff;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 11px;
        display: inline-block;
        margin-top: 10px;
    }

    .badge-due {
        background: #ef4444;
        color: #fff;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 11px;
        display: inline-block;
        margin-top: 10px;
    }

    .card-body {
        padding: 10px;
    }

    .address-table {
        width: 100%;
        margin-bottom: 30px;
    }

    .address-table td {
        vertical-align: top;
        width: 50%;
        padding: 5px;
    }

    .address-title {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .items-table th {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 8px;
        font-size: 11px;
        text-transform: uppercase;
    }

    .items-table td {
        border: 1px solid #cbd5e1;
        padding: 8px;
    }

    .items-table th,
    .items-table td {
        text-align: right;
    }

    .items-table th:first-child,
    .items-table td:first-child {
        text-align: left;
    }

    .summary-table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    .summary-table td {
        padding: 6px;
        border: 1px solid #cbd5e1;
    }

    .summary-title {
        background: #f8fafc;
        font-weight: bold;
    }

    .total-row {
        background: #e0e7ff;
        font-weight: bold;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        font-size: 11px;
        color: #64748b;
        border-top: 1px solid #cbd5e1;
        padding-top: 10px;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
    }
</style>
<div class="invoice-container">

    <div class="invoice-header">
        <h1>INVOICE</h1>
        <p>#{{ $bill->id }} | {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</p>

        @if ($bill->due_amount <= 0)
            <span class="badge-paid">PAID</span>
        @else
            <span class="badge-due">BALANCE DUE</span>
        @endif
    </div>

    <div class="card-body">

        <!-- ADDRESSES -->
        <table class="address-table">
            <tr>
                <td>
                    <div class="address-title">From</div>
                    <strong>{{ $bill->shop->shop_name ?? 'N/A' }}</strong><br>
                    {{ $bill->shop_address ?? ($bill->shop->shop_address ?? 'N/A') }}<br>
                    Phone: {{ $bill->shop_phone ?? ($bill->shop->shop_contact ?? 'N/A') }}<br>
                    GST: {{ $bill->shop_gst ?? ($bill->shop->gst_number ?? 'N/A') }}
                </td>
                <td align="right">
                    <div class="address-title">Bill To</div>
                    <strong>{{ $bill->customer->customer_name ?? 'N/A' }}</strong><br>
                    {{ $bill->customer->address ?? 'N/A' }}<br>
                    Phone: {{ $bill->customer->phone_number ?? 'N/A' }}<br>
                    Email: {{ $bill->customer->email ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <!-- ITEMS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bill->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- SUMMARY -->
        <table class="summary-table">
            <tr>
                <td class="summary-title">Subtotal</td>
                <td align="right">₹{{ number_format($bill->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-title">Tax</td>
                <td align="right">₹{{ number_format($bill->tax, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-title">Discount</td>
                <td align="right">-₹{{ number_format($bill->discount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td align="right">₹{{ number_format($bill->total, 2) }}</td>
            </tr>
            <tr>
                <td>Paid</td>
                <td align="right">₹{{ number_format($bill->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Due</td>
                <td align="right">
                    {{ $bill->due_amount <= 0 ? '₹0.00' : '₹' . number_format($bill->due_amount, 2) }}
                </td>
            </tr>
        </table>
        @if ($bill->payments->count() > 0 && $bill->paid_amount < $bill->total)

            <h3 style="font-size: 14px;font-weight: bold;margin-bottom: 10px;color: #555;border-bottom: 1px solid #ccc;padding-bottom: 5px;">
                Payment History
            </h3>

            <table width="100%" cellpadding="8" cellspacing="0"
                style="border-collapse: collapse;font-size: 12px;margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #f2f2f2; color: #555;">
                        <th align="left" style="border: 1px solid #ddd;">Payment Date</th>
                        <th align="right" style="border: 1px solid #ddd;">Amount</th>
                        <th align="center" style="border: 1px solid #ddd;">Status</th>
                        <th align="left" style="border: 1px solid #ddd;">Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bill->payments as $payment)
                        <tr>
                            <td style="border: 1px solid #ddd;">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
                            </td>

                            <td align="right" style="border: 1px solid #ddd; font-weight: bold;">
                                ₹{{ number_format($payment->amount, 2) }}
                            </td>

                            <td align="center" style="border: 1px solid #ddd;">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}
                            </td>

                            <td style="border: 1px solid #ddd; color: #666;">
                                {{ $payment->note ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif


        <div class="footer">
            Thank you for your business<br>
            Generated on {{ now()->format('d M, Y h:i A') }}
        </div>

    </div>
</div>
