@extends('user_layout.user_index')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="rounded h-100 p-4" style="background: #f8fafc;">
                <h6 class="mb-4" style="background: linear-gradient(90deg, #f6d365 0%, #fda085 100%); color: #333; padding: 12px 18px; border-radius: 8px; font-weight: 600;">Partially Paid Bills (Dues)</h6>
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session()->get('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover align-middle" style="background: #fffde7; border-radius: 8px; overflow: hidden;">
                        <thead style="background: linear-gradient(90deg, #f6d365 0%, #fda085 100%); color: #333;">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Invoice ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Shop Name</th>
                                <th scope="col">Invoice Total</th>
                                <th scope="col">Paid Amount</th>
                                <th scope="col">Due Amount</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partiallyPaidInvoices as $invoice)
                                <tr style="background: #e3f2fd;">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->customer->customer_name }}</td>
                                    <td>{{ $invoice->shop->shop_name }}</td>
                                    <td style="color: #2196f3; font-weight: 600;">{{ $invoice->total }}</td>
                                    <td style="color: #4caf50; font-weight: 600;">{{ $invoice->paid_amount }}</td>
                                    <td style="color: #ff9800; font-weight: 600;">{{ $invoice->due_amount }}</td>
                                    <td>{{ $invoice->status }}</td>
                                    <td>
                                        <a href="{{ route('show_invoice', ['id' => $invoice->id]) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $invoice->id }}">Pay Now</a>
                                        <!-- Payment Modal -->
                                        <div class="modal fade" id="paymentModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $invoice->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg" style="background: #f8fafc;">
                                                    <div class="modal-header" style="background: linear-gradient(90deg, #f6d365 0%, #fda085 100%); color: #333;">
                                                        <h5 class="modal-title mb-0" id="paymentModalLabel{{ $invoice->id }}">
                                                            <i class="bi bi-cash-coin me-2" style="color: #ff9800;"></i>
                                                            <span style="font-weight: 600;">Make Payment for Invoice #{{ $invoice->id }}</span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('store_payment') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body p-4">
                                                            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                                            <div class="mb-3">
                                                                <label for="amount{{ $invoice->id }}" class="form-label fw-semibold" style="color: #ff9800;">Amount to Pay</label>
                                                                <input type="number" step="0.01" class="form-control form-control-lg" id="amount{{ $invoice->id }}" name="amount" max="{{ $invoice->due_amount }}" required style="border: 1px solid #ffe0b2; background: #fffde7;">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="payment_date{{ $invoice->id }}" class="form-label fw-semibold" style="color: #4caf50;">Payment Date</label>
                                                                <input type="date" class="form-control" id="payment_date{{ $invoice->id }}" name="payment_date" value="{{ date('Y-m-d') }}" required style="border: 1px solid #c8e6c9; background: #e8f5e9;">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="note{{ $invoice->id }}" class="form-label fw-semibold" style="color: #2196f3;">Note (Optional)</label>
                                                                <textarea class="form-control" id="note{{ $invoice->id }}" name="note" rows="3" style="border: 1px solid #bbdefb; background: #e3f2fd;"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-end gap-2" style="background: #fffde7;">
                                                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-warning px-4 text-white" style="background: linear-gradient(90deg, #f6d365 0%, #fda085 100%); border: none;">Submit Payment</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $partiallyPaidInvoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
