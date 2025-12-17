@extends('user_layout.user_index')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="rounded h-100 p-4" style="background: #f8fafc;">
                <h6 class="mb-4">Payment History</h6>

                {{-- Filters --}}
                <form method="GET" action="{{ route('payment_history') }}" class="row g-2 mb-4">
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}" placeholder="From">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}" placeholder="To">
                    </div>
                    <div class="col-md-3">
                        <select name="customer_id" class="form-control">
                            <option value="">All Customers</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (isset($filters['customer_id']) && $filters['customer_id']==$c->id) ? 'selected' : '' }}>
                                    {{ $c->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="shop_id" class="form-control">
                            <option value="">All Shops</option>
                            @foreach($shops as $s)
                                <option value="{{ $s->id }}" {{ (isset($filters['shop_id']) && $filters['shop_id']==$s->id) ? 'selected' : '' }}>
                                    {{ $s->shop_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="paid" {{ (isset($filters['status']) && $filters['status']=='paid') ? 'selected' : '' }}>Paid</option>
                            <option value="partially_paid" {{ (isset($filters['status']) && $filters['status']=='partially_paid') ? 'selected' : '' }}>Partially Paid</option>
                            {{-- <option value="unpaid" {{ (isset($filters['status']) && $filters['status']=='unpaid') ? 'selected' : '' }}>Unpaid</option> --}}
                            <option value="cash" {{ (isset($filters['status']) && $filters['status']=='cash') ? 'selected' : '' }}>Cash</option>
                            <option value="online" {{ (isset($filters['status']) && $filters['status']=='online') ? 'selected' : '' }}>Online</option>
                            {{-- <option value="draft" {{ (isset($filters['status']) && $filters['status']=='draft') ? 'selected' : '' }}>Draft</option> --}}
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-md-12 mt-2">
                        <a href="{{ route('payment_history') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Shop</th>
                                <th>Paid</th>
                                <th>Last Payment Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Mode</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td>{{ $loop->iteration + ($invoices->currentPage()-1)*$invoices->perPage() }}</td>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->customer->customer_name ?? '-' }}</td>
                                    <td>{{ $invoice->shop->shop_name ?? '-' }}</td>
                                    <td>₹{{ number_format($invoice->payments->sum('amount'),2) }}</td>
                                    <td>{{ $invoice->payments->last()->payment_date ?? '-' }}</td>
                                    <td>₹{{ number_format($invoice->total,2) }}</td>
                                    <td>{{ $invoice->status }}</td>
                                    <td>{{ $invoice->payment_mode ?? '-'}}</td>
                                    <td>{{ $invoice->payments->last()->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No invoices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
