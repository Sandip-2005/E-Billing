@extends('user_layout.user_index')
@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="rounded h-100 p-4" style="background: #f8fafc;">
                <h6 class="mb-4" style="background: linear-gradient(90deg, #f6d365 0%, #fda085 100%); color: #333; padding: 12px 18px; border-radius: 8px; font-weight: 600;">Payment History</h6>
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
                                <th scope="col">Payment Amount</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Invoice Total</th>
                                <th scope="col">Invoice Status</th>
                                <th scope="col">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr style="background: #e3f2fd;">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $payment->invoice->id }}</td>
                                    <td>{{ $payment->invoice->customer->customer_name }}</td>
                                    <td>{{ $payment->invoice->shop->shop_name }}</td>
                                    <td style="color: #ff9800; font-weight: 600;">{{ $payment->amount }}</td>
                                    <td style="color: #4caf50;">{{ $payment->payment_date }}</td>
                                    <td style="color: #2196f3;">{{ $payment->invoice->total }}</td>
                                    <td>{{ $payment->invoice->status }}</td>
                                    <td>{{ $payment->note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
