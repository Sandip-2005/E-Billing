@extends('user_layout.user_index')

@section('content')
    <div class="container py-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h3 class="mb-1 fw-bold"><i class="bi bi-receipt-cutoff me-2"></i>Invoice #{{ $bill->id }}</h3>
                        <span class="opacity-75"><i class="bi bi-calendar-event me-1"></i>
                            {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') }}</span>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'paid' => 'success',
                                'draft' => 'warning',
                                'unpaid' => 'danger',
                                'partially_paid' => 'info',
                            ];
                            $badgeColor = $statusColors[$bill->status] ?? 'secondary';
                        @endphp
                        <span
                            class="badge bg-white text-{{ $badgeColor }} fs-6 px-3 py-2 rounded-pill shadow-sm text-uppercase">
                            {{ str_replace('_', ' ', $bill->status) }}
                        </span>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="card-body p-5">
                <div class="row mb-5 g-4">
                    {{-- Shop Details --}}
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small ls-1">From</h6>
                        <address class="mb-0 fs-5">
                            <strong class="text-dark d-block mb-2">{{ $bill->shop->shop_name ?? 'N/A' }}</strong>
                            <span class="fs-6 text-secondary d-block mb-1"><i
                                    class="bi bi-geo-alt me-2"></i>{{ $bill->shop_address ?? ($bill->shop->shop_address ?? 'N/A') }}</span>
                            <span class="fs-6 text-secondary d-block mb-1"><i
                                    class="bi bi-telephone me-2"></i>{{ $bill->shop_phone ?? ($bill->shop->shop_contact ?? 'N/A') }}</span>
                            <span class="fs-6 text-secondary d-block"><i class="bi bi-file-earmark-text me-2"></i>GST:
                                {{ $bill->shop_gst ?? ($bill->shop->gst_number ?? 'N/A') }}</span>
                        </address>
                    </div>

                    {{-- Customer Details --}}
                    <div class="col-md-6 text-md-end border-start-md ps-md-5">
                        <h6 class="text-uppercase text-muted fw-bold mb-3 small ls-1">Bill To</h6>
                        <address class="mb-0 fs-5">
                            <strong class="text-dark d-block mb-2">{{ $bill->customer->customer_name ?? 'N/A' }}</strong>
                            <span class="fs-6 text-secondary d-block mb-1"><i
                                    class="bi bi-geo-alt me-2"></i>{{ $bill->customer->address ?? 'N/A' }}</span>
                            <span class="fs-6 text-secondary d-block mb-1"><i class="bi bi-telephone me-1"></i>
                                {{ $bill->customer->phone_number ?? 'N/A' }}</span>
                            <span class="fs-6 text-secondary d-block"><i class="bi bi-envelope me-1"></i>
                                {{ $bill->customer->email ?? 'N/A' }}</span>
                        </address>
                    </div>
                </div>

                {{-- Bill Items --}}
                <div class="table-responsive mb-5 rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="col-6 py-3 ps-4 text-uppercase small fw-bold">Product</th>
                                <th class="py-3 text-center text-uppercase small fw-bold">Qty</th>
                                <th class="py-3 text-end text-uppercase small fw-bold">Unit Price</th>
                                <th class="py-3 pe-4 text-end text-uppercase small fw-bold">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach ($bill->items as $item)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end pe-4 fw-bold">₹{{ number_format($item->line_total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="row justify-content-end mb-5">
                    <div class="col-md-5 col-lg-4">
                        <div class="bg-light p-4 rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">₹{{ number_format($bill->sub_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax:</span>
                                <span class="fw-bold">₹{{ number_format($bill->tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Discount:</span>
                                <span class="text-danger fw-bold">- ₹{{ number_format($bill->discount, 2) }}</span>
                            </div>
                            <div
                                class="border-top border-bottom py-3 mb-3 d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">Total:</span>
                                <span class="h4 mb-0 text-primary">₹{{ number_format($bill->total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Paid Amount:</span>
                                <span class="fw-bold text-success">₹{{ number_format($bill->paid_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Due Amount:</span>
                                <span class="fw-bold text-danger">₹{{ number_format($bill->due_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment History --}}
                @if ($bill->payments->count() > 0)
                    <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-clock-history me-2"></i>Payment History</h5>
                    <div class="table-responsive mb-4 rounded-3 border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="ps-4">Payment Date</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="pe-4">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->payments as $payment)
                                    <tr>
                                        <td class="ps-4">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                        <td class="text-end fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                {{ ucfirst(str_replace('_', ' ', $payment->payment_status)) }}
                                            </span>
                                        </td>
                                        <td class="pe-4 text-muted">{{ $payment->note ?? '-' }}</td>
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

                @if ($bill->status == 'draft')
                    <div class="text-center mt-3">
                        <form action="{{ route('submit_draft', $bill->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg shadow-sm px-5 rounded-pill"><i
                                    class="bi bi-check-circle me-2"></i>Submit Invoice</button>
                        </form>
                    </div>
                @endif

                <div class="text-center mt-3">
                    <a href="{{ route('edit_invoice', $bill->id) }}" class="btn btn-outline-primary rounded-pill px-4"><i
                            class="bi bi-file-earmark-pdf me-2"></i>Edit Invoice</a>
                    <a href="{{ route('invoice_download', $bill->id) }}"
                        class="btn btn-outline-primary rounded-pill px-4"><i
                            class="bi bi-file-earmark-pdf me-2"></i>Download PDF</a>
                    <a href="{{ route('invoice_mail', $bill->id) }}" class="btn btn-outline-primary rounded-pill px-4"><i
                            class="bi bi-envelope me-2"></i>Mail Invoice</a>
                </div>
            </div>
        </div>
    </div>
@endsection
