@extends('user_layout.user_index')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-receipt-cutoff"></i> Invoice #{{ $bill->id }}</h3>
                <span class="fs-5">Date: {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</span>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                {{-- Shop Details --}}
                <div class="col-md-6">
                    <h5 class="fw-bold">From:</h5>
                    <address class="mb-0">
                        <strong>{{ $bill->shop->shop_name ?? 'N/A' }}</strong><br>
                        {{ $bill->shop_address ?? ($bill->shop->shop_address ?? 'N/A') }}<br>
                        Phone: {{ $bill->shop_phone ?? ($bill->shop->shop_contact ?? 'N/A') }}<br>
                        GST: {{ $bill->shop_gst ?? ($bill->shop->gst_number ?? 'N/A') }}
                    </address>
                </div>

                {{-- Customer Details --}}
                <div class="col-md-6 text-md-end">
                    <h5 class="fw-bold">To:</h5>
                    <address class="mb-0">
                        <strong>{{ $bill->customer->customer_name ?? 'N/A' }}</strong><br>
                        {{ $bill->customer->address ?? 'N/A' }}<br>
                        Phone: {{ $bill->customer->phone_number ?? 'N/A' }}<br>
                        Email: {{ $bill->customer->email ?? 'N/A' }}
                    </address>
                </div>
            </div>

            {{-- Bill Items --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th class="col-6">Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">₹{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="row justify-content-end">
                <div class="col-md-5 col-lg-4">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th class="text-end">Subtotal:</th>
                                <td class="text-end">₹{{ number_format($bill->sub_total, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Tax:</th>
                                <td class="text-end">₹{{ number_format($bill->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Discount:</th>
                                <td class="text-end text-danger">- ₹{{ number_format($bill->discount, 2) }}</td>
                            </tr>
                            <tr class="fw-bold fs-5 table-primary">
                                <th class="text-end">Total:</th>
                                <td class="text-end">₹{{ number_format($bill->total, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Paid Amount:</th>
                                <td class="text-end">₹{{ number_format($bill->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-end">Due Amount:</th>
                                <td class="text-end">₹{{ number_format($bill->due_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment History --}}
            @if ($bill->payments->count() > 0)
            <h5 class="fw-bold mt-4">Payment History</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bill->payments as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                            <td class="text-end">₹{{ number_format($payment->amount, 2) }}</td>
                            <td class="text-center">{{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}</td>
                            <td>{{ $payment->note ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="text-center mt-4 text-muted">
                <p>Thank you for your business!</p>
                <small>Generated on {{ now()->format('d M, Y h:i A') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
