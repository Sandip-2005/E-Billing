@extends('user_layout.user_index')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-receipt"></i> Create New Bill</h3>
                <a href="{{ route('user_dashboard') }}" class="btn btn-light btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
            <div class="card-body">
                {{-- Error Alerts --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Bill Form --}}
                <form action="{{ route('store_invoice') }}" method="POST" id="billForm">
                    @csrf

                    {{-- Select Shop --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Shop <span class="text-danger">*</span></label>
                        <select name="shop_id" id="shopSelect" class="form-control" required>
                            <option value="">Choose Shop</option>
                            @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Shop Details (auto-filled) --}}
                    <div class="row g-3 mb-4" id="shopDetails" style="display:none;">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Shop Phone</label>
                            <input type="text" id="shopPhone" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">GST Number</label>
                            <input type="text" id="shopGST" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Shop Address</label>
                            <textarea id="shopAddress" rows="2" class="form-control bg-light" readonly></textarea>
                        </div>
                    </div>

                    {{-- Hidden inputs to submit shop details --}}
                    <input type="hidden" name="shop_phone" id="shop_phone_input">
                    <input type="hidden" name="shop_gst" id="shop_gst_input">
                    <input type="hidden" name="shop_address" id="shop_address_input">

                    {{-- Customer Info --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Existing Customer<span class="text-danger">*</span></label>
                        <select name="customer_id" id="customerSelect" class="form-select" required>
                            <option value="">-- Select a Customer --</option>
                            @forelse ($customers as $customer)
                                <option value="{{ $customer->id }}" data-name="{{ $customer->customer_name }}"
                                    data-phone="{{ $customer->phone_number }}" data-email="{{ $customer->email }}"
                                    data-address="{{ $customer->address }}">
                                    Name: {{ $customer->customer_name }}&nbsp;&nbsp;||&nbsp;&nbsp;
                                    Phone: ({{ $customer->phone_number }})&nbsp;&nbsp;||&nbsp;&nbsp;
                                    Email: ({{ $customer->email }})
                                </option>
                            @empty
                                <option value="" disabled>No customers found</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Toggle for New Customer Form --}}
                    <div class="mb-3 text-center">
                        <button type="button" id="toggleCustomerBtn" class="btn btn-link">
                            <i class="bi bi-person-plus"></i> Or Add a New Customer
                        </button>
                    </div>

                    {{-- New Customer Form --}}
                    <div id="customerSection" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Customer Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                    class="form-control" placeholder="Enter customer name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                    class="form-control" placeholder="Enter customer email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                                    class="form-control" placeholder="Enter customer phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="customer_address" rows="2" class="form-control" placeholder="Enter address">{{ old('customer_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label fw-semibold">Bill Date</label>
                        <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}"
                            class="form-control">
                    </div>

                    {{-- Bill Items --}}
                    <h5 class="fw-bold mt-4">Bill Items</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle text-center" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th style="width: 100px;">Qty</th>
                                    <th style="width: 150px;">Unit Price</th>
                                    <th style="width: 150px;">Line Total</th>
                                    <th style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][product_id]" class="form-control product-select" required>
                                            <option value="">Select Product</option>
                                        </select>
                                        <input type="hidden" name="items[0][product_name]" class="product-name">
                                    </td>
                                    <td><input type="number" name="items[0][quantity]" min="1" value="1"
                                            class="form-control qty" required></td>
                                    <td><input type="number" name="items[0][unit_price]" step="0.01" min="0"
                                            value="0.00" class="form-control price" required></td>
                                    <td class="line-total text-end">0.00</td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">✖</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="addRow" class="btn btn-outline-primary mb-4">
                        <i class="bi bi-plus-circle"></i> Add Item
                    </button>

                    {{-- Totals --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Sub Total</label>
                            <input type="text" id="grandTotal" readonly class="form-control bg-light" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tax</label>
                            <input type="number" name="tax" id="tax" step="0.01" min="0"
                                value="{{ old('tax', 0) }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount</label>
                            <input type="number" name="discount" id="discount" step="0.01" min="0"
                                value="{{ old('discount', 0) }}" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold fs-5">Total Amount</label>
                        <input type="text" id="total" readonly class="form-control bg-light fw-bold fs-5"
                            value="0.00">
                    </div>

                    {{-- Payment Received --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment Received</label>
                        <input type="number" name="payment_received" id="paymentReceived" step="0.01" min="0"
                            value="{{ old('payment_received', 0) }}" class="form-control">
                    </div>

                    {{-- Due Amount --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Due Amount</label>
                        <input type="text" id="dueAmount" readonly class="form-control bg-light" value="0.00">
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4">💾 Create Bill</button>
                        <button type="submit" name="save_as_draft" value="1"
                            class="btn btn-warning px-4 text-white">
                            <i class="bi bi-file-earmark"></i> Save as Draft
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tax').addEventListener('input', recalc);
            document.getElementById('discount').addEventListener('input', recalc);
            const shopSelect = document.getElementById('shopSelect');
            const itemsTbody = document.querySelector('#itemsTable tbody');
            const addRowBtn = document.getElementById('addRow');
            let currentProducts = [];

            function loadProducts(shopId, select) {
                select.innerHTML = '<option value="">Select Product</option>';
                if (!shopId) return;
                fetch(`/api/products/by-shop/${shopId}`)
                    .then(res => res.json())
                    .then(products => {
                        currentProducts = products;
                        products.forEach(product => {
                            let label = product.product_name ? product.product_name : product
                                .product_id;
                            select.innerHTML += `<option value="${product.id}">${label}</option>`;
                        });
                    });
            }

            function setPriceOnSelect(select, priceInput) {
                select.addEventListener('change', function() {
                    const selectedId = select.value;
                    const product = currentProducts.find(p => p.id == selectedId);
                    priceInput.value = product ? product.price : '';
                    const productNameInput = select.closest('td').querySelector('.product-name');
                    productNameInput.value = product ? product.product_name : '';
                    recalc();
                });
            }

            function attachRowEvents(row) {
                row.querySelector('.qty').addEventListener('input', recalc);
                row.querySelector('.price').addEventListener('input', recalc);
                row.querySelector('.remove-row').addEventListener('click', function() {
                    if (document.querySelectorAll('.item-row').length === 1) {
                        row.querySelector('.qty').value = 1;
                        row.querySelector('.price').value = '0.00';
                    } else {
                        row.remove();
                    }
                    recalc();
                });
            }

            shopSelect.addEventListener('change', function() {
                document.querySelectorAll('.item-row').forEach(row => {
                    const select = row.querySelector('.product-select');
                    const priceInput = row.querySelector('.price');
                    loadProducts(shopSelect.value, select);
                    setPriceOnSelect(select, priceInput);
                });
                recalc();
            });

            addRowBtn.addEventListener('click', function() {
                let idx = document.querySelectorAll('.item-row').length;
                let tr = document.createElement('tr');
                tr.classList.add('item-row');
                tr.innerHTML = `
            <td>
                <select name="items[${idx}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                </select>
                <input type="hidden" name="items[${idx}][product_name]" class="product-name">
            </td>
            <td><input type="number" name="items[${idx}][quantity]" min="1" value="1" class="form-control qty" required></td>
            <td><input type="number" name="items[${idx}][unit_price]" step="0.01" min="0" value="0.00" class="form-control price" required></td>
            <td class="line-total text-end">0.00</td>
            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row">✖</button></td>
        `;
                itemsTbody.appendChild(tr);
                const select = tr.querySelector('.product-select');
                const priceInput = tr.querySelector('.price');
                loadProducts(shopSelect.value, select);
                setPriceOnSelect(select, priceInput);
                attachRowEvents(tr);
                recalc();
            });

            const firstRow = document.querySelector('.item-row');
            const firstSelect = firstRow.querySelector('.product-select');
            const firstPrice = firstRow.querySelector('.price');
            loadProducts(shopSelect.value, firstSelect);
            setPriceOnSelect(firstSelect, firstPrice);
            attachRowEvents(firstRow);
            recalc();

            function recalc() {
                let grandTotal = 0;

                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.qty').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;
                    const lineTotal = qty * price;

                    row.querySelector('.line-total').textContent = lineTotal.toFixed(2);
                    grandTotal += lineTotal;
                });

                document.getElementById('grandTotal').value = grandTotal.toFixed(2);

                const taxPercent = parseFloat(document.getElementById('tax').value) || 0;
                const discountPercent = parseFloat(document.getElementById('discount').value) || 0;

                const taxAmount = (grandTotal * taxPercent) / 100;
                const discountAmount = (grandTotal * discountPercent) / 100;

                let finalTotal = grandTotal + taxAmount - discountAmount;

                if (finalTotal < 0) finalTotal = 0;

                document.getElementById('total').value = finalTotal.toFixed(2);

                // Calculate Due Amount
                const paymentReceived = parseFloat(document.getElementById('paymentReceived').value) || 0;
                let dueAmount = finalTotal - paymentReceived;
                if (dueAmount < 0) dueAmount = 0; // Due amount cannot be negative
                document.getElementById('dueAmount').value = dueAmount.toFixed(2);
            }

            document.getElementById('discount').addEventListener('input', recalc);
            document.getElementById('tax').addEventListener('input', recalc);
            document.getElementById('paymentReceived').addEventListener('input', recalc);

            const toggleCustomerBtn = document.getElementById('toggleCustomerBtn');
            const customerSection = document.getElementById('customerSection');
            const customerSelect = document.getElementById('customerSelect');
            const customerNameInput = document.querySelector('input[name="customer_name"]');

            toggleCustomerBtn.addEventListener('click', function() {
                const isHidden = customerSection.style.display === 'none';
                customerSection.style.display = isHidden ? 'block' : 'none';

                if (isHidden) {
                    customerSelect.required = false;
                    customerSelect.value = ''; // Clear selection
                    customerNameInput.required = true;
                    toggleCustomerBtn.innerHTML = '<i class="bi bi-person-check"></i> Select Existing Customer';
                } else {
                    customerSelect.required = true;
                    customerNameInput.required = false;
                    // Clear new customer fields
                    customerNameInput.value = '';
                    document.querySelector('input[name="customer_email"]').value = '';
                    document.querySelector('input[name="customer_phone"]').value = '';
                    document.querySelector('textarea[name="customer_address"]').value = '';
                    toggleCustomerBtn.innerHTML = '<i class="bi bi-person-plus"></i> Or Add a New Customer';
                }
            });
        });
    </script>
@endsection
