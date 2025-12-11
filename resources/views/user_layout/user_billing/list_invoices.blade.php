@extends('user_layout.user_index')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-list-ul"></i> All Invoices</h3>
            <a href="{{ route('make_new_invoice') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Create New Invoice
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Shop</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->customer->customer_name ?? 'N/A' }}</td>
                                <td>{{ $invoice->shop->shop_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->bill_date)->format('d M, Y') }}</td>
                                <td>₹{{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    @if ($invoice->status == 'draft')
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @else
                                        <span class="badge bg-success">Final</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('show_invoice', $invoice->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection