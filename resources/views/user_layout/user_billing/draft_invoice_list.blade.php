@extends('user_layout.user_index')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-bold text-primary" style="color: #007bff;"><i class="bi bi-receipt me-2"></i>Invoices</h3>
            <a href="{{ route('make_new_invoice') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Create New
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold" style="color: #007bff;">Invoice #</th>
                            <th class="py-3 text-uppercase small fw-bold" style="color: #007bff;">Customer</th>
                            <th class="py-3 text-uppercase small fw-bold" style="color: #007bff;">Shop</th>
                            <th class="py-3 text-uppercase small fw-bold" style="color: #007bff;">Date</th>
                            <th class="py-3 text-uppercase small fw-bold" style="color: #007bff;">Total</th>
                            <th class="py-3 text-uppercase small fw-bold" style="color: #007bff;">Status</th>
                            <th class="text-center py-3 pe-4 text-uppercase small fw-bold" style="color: #007bff;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($draftInvoices as $invoice)
                            <tr>
                                <td class="ps-4 fw-bold text-primary" style="color: #007bff;">#{{ $invoice->id }}</td>
                                <td class="fw-medium" style="color: #495057;">{{ $invoice->customer->customer_name ?? 'N/A' }}</td>
                                <td class="text-muted" style="color: #6c757d;">{{ $invoice->shop->shop_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-light text-dark border"><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($invoice->bill_date)->format('d M, Y') }}</span></td>
                                <td class="fw-bold" style="color: #28a745;">₹{{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    @if ($invoice->status == 'draft')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">Draft</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Final</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('show_invoice', $invoice->id) }}" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('edit_invoice', $invoice->id) }}" class="btn btn-sm btn-light text-secondary rounded-circle shadow-sm ms-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                        <p class="mb-0" style="color: #6c757d;">No invoices found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center p-4">
                {{ $draftInvoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection