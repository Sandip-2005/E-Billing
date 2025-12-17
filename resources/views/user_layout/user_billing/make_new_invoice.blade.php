@extends('user_layout.user_index')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-receipt"></i> {{ isset($invoice) ? 'Edit Invoice' : 'Create New Invoice' }}</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="invoiceForm" action="{{ isset($invoice) ? route('update_invoice', $invoice->id) : route('store_invoice') }}" method="POST">
                @csrf
                @if(isset($invoice))
                    @method('PUT')
                @endif

                <!-- Shop Selection -->
                <div class="mb-3">
                    <label for="shop_id" class="form-label">Shop</label>
                    <select name="shop_id" id="shop_id" class="form-select" required>
                        <option value="">Select Shop</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ isset($invoice) && $invoice->shop_id == $shop->id ? 'selected' : '' }}>
                                {{ $shop->shop_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer Selection or New Customer -->
                <div class="mb-3">
                    <label class="form-label">Customer</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_type" id="existing_customer" value="existing" {{ isset($invoice) ? 'checked' : 'checked' }}>
                        <label class="form-check-label" for="existing_customer">Existing Customer</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="customer_type" id="new_customer" value="new">
                        <label class="form-check-label" for="new_customer">New Customer</label>
                    </div>
                </div>

                <div id="existing_customer_fields" class="mb-3">
                    <select name="customer_id" id="customer_id" class="form-select">
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ isset($invoice) && $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="new_customer_fields" class="mb-3" style="display: none;">
                    <input type="text" name="customer_name" class="form-control mb-2" placeholder="Customer Name" value="{{ isset($invoice) && !$invoice->customer_id ? $invoice->customer->customer_name ?? '' : '' }}">
                    <input type="email" name="customer_email" class="form-control mb-2" placeholder="Email" value="{{ isset($invoice) && !$invoice->customer_id ? $invoice->customer->email ?? '' : '' }}">
                    <input type="text" name="customer_phone" class="form-control mb-2" placeholder="Phone" value="{{ isset($invoice) && !$invoice->customer_id ? $invoice->customer->phone_number ?? '' : '' }}">
                    <textarea name="customer_address" class="form-control" placeholder="Address">{{ isset($invoice) && !$invoice->customer_id ? $invoice->customer->address ?? '' : '' }}</textarea>
                </div>

                <!-- Bill Date -->
                <div class="mb-3">
                    <label for="bill_date" class="form-label">Bill Date</label>
                    <input type="date" name="bill_date" id="bill_date" class="form-control" value="{{ isset($invoice) ? $invoice->bill_date : date('Y-m-d') }}" required>
                </div>

                <!-- Items Table -->
                <div class="mb-3">
                    <label class="form-label">Items</label>
                    <table class="table table-bordered" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <!-- Prefill existing items if editing -->
                            @if(isset($invoice))
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $loop->index }}][product_id]" class="form-select product-select" required>
                                                <option value="{{ $item->product_id }}" selected>{{ $item->product_name }}</option>
                                            </select>
                                            <input type="hidden" name="items[{{ $loop->index }}][product_name]" value="{{ $item->product_name }}">
                                        </td>
                                        <td><input type="number" name="items[{{ $loop->index }}][quantity]" class="form-control quantity" value="{{ $item->quantity }}" min="1" required></td>
                                        <td><input type="number" step="0.01" name="items[{{ $loop->index }}][unit_price]" class="form-control unit-price" value="{{ $item->unit_price }}" required></td>
                                        <td><input type="number" step="0.01" class="form-control line-total" value="{{ $item->line_total }}" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" id="addItem">Add Item</button>
                </div>

                <!-- Tax, Discount, Payment -->
                <div class="row">
                    <div class="col-md-4">
                        <label for="tax" class="form-label">Tax (%)</label>
                        <input type="number" step="0.01" name="tax" id="tax" class="form-control" value="{{ isset($invoice) && $invoice->sub_total > 0 ? round(($invoice->tax / $invoice->sub_total) * 100, 2) : 0 }}">
                    </div>
                    <div class="col-md-4">
                        <label for="discount" class="form-label">Discount (%)</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ isset($invoice) && $invoice->sub_total > 0 ? round(($invoice->discount / $invoice->sub_total) * 100, 2) : 0 }}">
                    </div>
                    <div class="col-md-4">
                        <label for="payment_received" class="form-label">Payment Received</label>
                        <input type="number" step="0.01" name="payment_received" id="payment_received" class="form-control" value="{{ isset($invoice) ? $invoice->paid_amount : 0 }}">
                    </div>
                </div>

                <!-- Payment Mode -->
                <div class="mb-3">
                    <label for="payment_mode" class="form-label">Payment Mode</label>
                    <select name="payment_mode" id="payment_mode" class="form-select" required>
                        <option value="cash" @if(old('payment_mode', isset($invoice) ? $invoice->payment_mode : 'cash') == 'cash') selected @endif>Cash</option>
                        <option value="online" @if(old('payment_mode', isset($invoice) ? $invoice->payment_mode : 'cash') == 'online') selected @endif>Online</option>
                    </select>
                </div>

                <!-- Totals Display -->
                <div class="row mb-3 justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th class="text-end"></th>Sub Total:</th>
                                <td><input type="text" id="totalAmount" class="form-control form-control-sm text-end" readonly value="0.00"></td>
                            </tr>
                            <tr>
                                <th class="text-end">Final Total:</th>
                                <td><input type="text" id="finalTotal" class="form-control form-control-sm text-end" readonly value="0.00"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-3">
                    <button type="submit" name="save_as_draft" value="1" class="btn btn-warning">Save as Draft</button>
                    <button type="submit" class="btn btn-success ms-2">{{ isset($invoice) ? 'Update Invoice' : 'Create Invoice' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let availableProducts = [];

    // Toggle between existing and new customer fields
    document.querySelectorAll('input[name="customer_type"]').forEach(function(el) {
        el.addEventListener('change', function() {
            if (this.value === 'new') {
                document.getElementById('new_customer_fields').style.display = 'block';
                document.getElementById('existing_customer_fields').style.display = 'none';
                document.getElementById('customer_id').removeAttribute('required');
                document.querySelector('input[name="customer_name"]').setAttribute('required', 'required');
            } else {
                document.getElementById('new_customer_fields').style.display = 'none';
                document.getElementById('existing_customer_fields').style.display = 'block';
                document.getElementById('customer_id').setAttribute('required', 'required');
                document.querySelector('input[name="customer_name"]').removeAttribute('required');
            }
        });
    });

    // Fetch Products when Shop Changes
    const shopSelect = document.getElementById('shop_id');
    if (shopSelect) {
        shopSelect.addEventListener('change', function() {
            fetchProducts(this.value);
        });
        // Initial load if shop is selected (e.g. edit mode)
        if (shopSelect.value) {
            fetchProducts(shopSelect.value);
        }
    }

    function fetchProducts(shopId) {
        if (!shopId) {
            availableProducts = [];
            return;
        }
        fetch(`{{ url('/get-products-by-shop') }}/${shopId}`)
            .then(response => response.json())
            .then(data => {
                availableProducts = data;
                // Populate all existing product selects (including prefilled ones in edit mode)
                document.querySelectorAll('.product-select').forEach(select => populateSelect(select));
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    function populateSelect(selectElement) {
        let currentVal = selectElement.value;
        let html = '<option value="">Select Product</option>';
        availableProducts.forEach(product => {
            let isSelected = (product.id == currentVal) ? 'selected' : '';
            html += `<option value="${product.id}" data-price="${product.price}" ${isSelected}>${product.product_name}</option>`;
        });
        selectElement.innerHTML = html;
        // Update hidden product_name if selected
        let selectedOption = selectElement.options[selectElement.selectedIndex];
        let productNameInput = selectElement.parentElement.querySelector('input[name*="[product_name]"]');
        if (productNameInput && selectedOption) {
            productNameInput.value = selectedOption.text;
        }
    }

    // Add Item Row
    document.getElementById('addItem').addEventListener('click', function() {
        let index = document.querySelectorAll('#itemsBody tr').length;
        let row = `
            <tr>
                <td>
                    <select name="items[${index}][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                    </select>
                    <input type="hidden" name="items[${index}][product_name]" value="">
                </td>
                <td><input type="number" name="items[${index}][quantity]" class="form-control quantity" value="1" min="1" required></td>
                <td><input type="number" step="0.01" name="items[${index}][unit_price]" class="form-control unit-price" value="0.00" required></td>
                <td><input type="number" step="0.01" class="form-control line-total" value="0.00" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
            </tr>
        `;
        document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);
        
        // Populate the new select
        let newSelect = document.querySelector('#itemsBody tr:last-child .product-select');
        populateSelect(newSelect);
        recalculateTotals();
    });

    // Remove Item Row
    document.getElementById('itemsBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('tr').remove();
            recalculateTotals();
        }
    });

    // Handle Product Selection (Update Price and Product Name)
    document.getElementById('itemsBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            let option = e.target.options[e.target.selectedIndex];
            let price = option.getAttribute('data-price') || 0;
            let row = e.target.closest('tr');
            row.querySelector('.unit-price').value = price;
            // Update hidden product_name
            let productNameInput = row.querySelector('input[name*="[product_name]"]');
            if (productNameInput) {
                productNameInput.value = option.text;
            }
            updateLineTotal(row);
        }
    });

    // Handle Input Changes (Quantity, Price)
    document.getElementById('itemsBody').addEventListener('input', function(e) { 
        if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
            updateLineTotal(e.target.closest('tr'));
        }
    });

    function updateLineTotal(row) {
        let qty = parseFloat(row.querySelector('.quantity').value) || 0;
        let price = parseFloat(row.querySelector('.unit-price').value) || 0;
        let total = qty * price;
        row.querySelector('.line-total').value = total.toFixed(2);
        recalculateTotals();
    }

    function recalculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.line-total').forEach(function(el) {
            grandTotal += parseFloat(el.value) || 0;
        });
        
        let totalEl = document.getElementById('totalAmount');
        if(totalEl) totalEl.value = grandTotal.toFixed(2);

        // Calculate tax and discount
        let taxPercent = parseFloat(document.getElementById('tax').value) || 0;
        let discountPercent = parseFloat(document.getElementById('discount').value) || 0;
        
        let taxAmount = (grandTotal * taxPercent) / 100;
        let discountAmount = (grandTotal * discountPercent) / 100;
        
        let finalTotal = grandTotal + taxAmount - discountAmount;

        let finalEl = document.getElementById('finalTotal');
        if(finalEl) finalEl.value = (finalTotal > 0 ? finalTotal : 0).toFixed(2);
    }

    // Listen for changes in Tax/Discount/Payment to update totals
    document.getElementById('tax').addEventListener('input', recalculateTotals);
    document.getElementById('discount').addEventListener('input', recalculateTotals);

    // Initial calculation
    recalculateTotals();
});
</script>
@endsection
