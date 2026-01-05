@extends('user_layout.user_index')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white p-4">
                <h3 class="mb-0 fw-bold"><i
                        class="bi bi-receipt me-2"></i>{{ isset($invoice) ? 'Edit Invoice' : 'Create New Invoice' }}</h3>
            </div>
            <div class="card-body p-4 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="invoiceForm"
                    action="{{ isset($invoice) ? route('update_invoice', $invoice->id) : route('store_invoice') }}"
                    method="POST">
                    @csrf
                    @if (isset($invoice))
                        @method('PUT')
                    @endif

                    <div class="row g-4 mb-4">
                        <!-- Shop Selection -->
                        <div class="col">
                            <div class="form-floating">
                                <select name="shop_id" id="shop_id" class="form-select" required>
                                    <option value="">Select Shop</option>
                                    @foreach ($shops as $shop)
                                        <option value="{{ $shop->id }}"
                                            {{ old('shop_id', isset($invoice) ? $invoice->shop_id : '') == $shop->id ? 'selected' : '' }}>
                                            {{ $shop->shop_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="shop_id">Select Shop</label>
                            </div>
                        </div>
                        <!-- Bill Date -->
                        {{-- <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" name="bill_date" id="bill_date" class="form-control"
                                    value="{{ isset($invoice) ? $invoice->bill_date : date('Y-m-d') }}" required>
                                <label for="bill_date">Bill Date</label>
                            </div>
                        </div> --}}
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- Customer Selection or New Customer -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary text-uppercase small ls-1">Customer Details</label>
                        <div class="d-flex gap-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customer_type" id="existing_customer"
                                    value="existing"
                                    {{ old('customer_type', isset($invoice) ? 'existing' : 'existing') == 'existing' ? 'checked' : '' }}>
                                <label class="form-check-label" for="existing_customer">
                                    Existing Customer
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="customer_type" id="new_customer"
                                    value="new" {{ old('customer_type') == 'new' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new_customer">
                                    New Customer
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="existing_customer_fields" class="mb-3">
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', isset($invoice) ? $invoice->customer_id : '') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="new_customer_fields" class="mb-3" style="display: none;">
                        <input type="text" name="customer_name" class="form-control mb-2" placeholder="Customer Name"
                            value="{{ old('customer_name', isset($invoice) ? $invoice->customer->customer_name ?? '' : '') }}">

                        <input type="email" name="customer_email" class="form-control mb-2" placeholder="Email"
                            value="{{ old('customer_email', isset($invoice) ? $invoice->customer->email ?? '' : '') }}">

                        <input type="text" name="customer_phone" class="form-control mb-2" placeholder="Phone"
                            value="{{ old('customer_phone', isset($invoice) ? $invoice->customer->phone_number ?? '' : '') }}">

                        <textarea name="customer_address" class="form-control" placeholder="Address">{{ old('customer_address', isset($invoice) ? $invoice->customer->address ?? '' : '') }}</textarea>
                    </div>

                    <!-- Bill Date -->
                    <div class="mb-3">
                        <label for="bill_date" class="form-label">Bill Date</label>
                        <input type="date" name="bill_date" id="bill_date" class="form-control"
                            value="{{ isset($invoice) ? $invoice->bill_date : date('Y-m-d') }}" required>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-3 table-responsive">
                        <label class="form-label">Items</label>
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Product</th>
                                    <th style="width: 15%;">Batch</th>
                                    <th style="width: 10%;">Qty</th>
                                    <th style="width: 15%;">Price</th>
                                    <th style="width: 20%;">Total</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @php
                                    $items = old('items', isset($invoice) ? $invoice->items->toArray() : []);
                                @endphp

                                @foreach ($items as $index => $item)
                                    <tr>
                                        <!-- PRODUCT -->
                                        <td>
                                            <select name="items[{{ $index }}][product_id]"
                                                class="form-select product-select" required>
                                                <option value="{{ $item['product_id'] ?? '' }}" selected>
                                                    {{ $item['product_name'] ?? '' }}
                                                </option>
                                            </select>

                                            <input type="hidden" name="items[{{ $index }}][product_name]"
                                                value="{{ $item['product_name'] ?? '' }}">
                                        </td>

                                        <!-- BATCH (NEW) -->
                                        <td>
                                            <select name="items[{{ $index }}][batch_id]"
                                                class="form-select batch-select" required
                                                data-selected-batch="{{ $item['batch_id'] ?? '' }}">
                                                @if (isset($item['batch_id']))
                                                    <option value="{{ $item['batch_id'] }}" selected>
                                                        Batch #{{ $item['batch_id'] }}
                                                    </option>
                                                @else
                                                    <option value="">Select Batch</option>
                                                @endif
                                            </select>
                                        </td>

                                        <!-- QUANTITY -->
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                class="form-control quantity" value="{{ $item['quantity'] ?? 1 }}"
                                                min="1" required>
                                        </td>

                                        <!-- UNIT PRICE -->
                                        <td>
                                            <input type="number" step="0.01"
                                                name="items[{{ $index }}][unit_price]"
                                                class="form-control unit-price" value="{{ $item['unit_price'] ?? 0 }}"
                                                required>
                                        </td>

                                        <!-- LINE TOTAL -->
                                        <td>
                                            <input type="number" step="0.01" class="form-control line-total"
                                                value="{{ $item['line_total'] ?? 0 }}" readonly>
                                        </td>

                                        <!-- ACTION -->
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <button type="button" class="btn btn-primary btn-sm" id="addItem">Add Item</button>
                    </div>

                    <!-- Tax, Discount, Payment -->
                    <div class="row">
                        <div class="col-md-4">
                            <label for="tax" class="form-label">Tax (%)</label>
                            <input type="number" step="0.01" name="tax" id="tax" class="form-control"
                                value="{{ isset($invoice) && $invoice->sub_total > 0 ? round(($invoice->tax / $invoice->sub_total) * 100, 2) : 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label for="discount" class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                                value="{{ isset($invoice) && $invoice->sub_total > 0 ? round(($invoice->discount / $invoice->sub_total) * 100, 2) : 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label for="payment_received" class="form-label">Payment Received</label>
                            <input type="number" step="0.01" name="payment_received" id="payment_received"
                                class="form-control" value="{{ isset($invoice) ? $invoice->paid_amount : 0 }}">
                        </div>
                    </div>

                    <!-- Payment Mode -->
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode" class="form-select" required>
                            <option value="cash" @if (old('payment_mode', isset($invoice) ? $invoice->payment_mode : 'cash') == 'cash') selected @endif>Cash</option>
                            <option value="online" @if (old('payment_mode', isset($invoice) ? $invoice->payment_mode : 'cash') == 'online') selected @endif>Online</option>
                        </select>
                    </div>

                    <!-- Totals Display -->
                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th class="text-end"></th>Sub Total:</th>
                                    <td><input type="text" id="totalAmount"
                                            class="form-control form-control-sm text-end" readonly value="0.00"></td>
                                </tr>
                                <tr>
                                    <th class="text-end">Final Total:</th>
                                    <td><input type="text" id="finalTotal"
                                            class="form-control form-control-sm text-end" readonly value="0.00"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-3">
                        <button type="submit" name="save_as_draft" value="1" class="btn btn-warning">Save as
                            Draft</button>
                        <button type="submit"
                            class="btn btn-success ms-2">{{ isset($invoice) ? 'Update Invoice' : 'Create Invoice' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            let availableProducts = [];

            // Toggle between existing and new customer fields
            document.querySelectorAll('input[name="customer_type"]').forEach(function(el) {
                el.addEventListener('change', function() {
                    if (this.value === 'new') {
                        document.getElementById('new_customer_fields').style.display = 'block';
                        document.getElementById('existing_customer_fields').style.display = 'none';
                        document.getElementById('customer_id').removeAttribute('required');
                        document.querySelector('input[name="customer_name"]').setAttribute(
                            'required', 'required');
                    } else {
                        document.getElementById('new_customer_fields').style.display = 'none';
                        document.getElementById('existing_customer_fields').style.display = 'block';
                        document.getElementById('customer_id').setAttribute('required', 'required');
                        document.querySelector('input[name="customer_name"]').removeAttribute(
                            'required');
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

                availableProducts
                    .filter(product => product.quantity > 0)
                    .forEach(product => {
                        let isSelected = (product.id == currentVal) ? 'selected' : '';
                        html += `
                <option value="${product.id}"
                        data-price="${product.price}">
                    ${product.product_name}
                </option>`;
                    });

                selectElement.innerHTML = html;

                // Update hidden product_name if selected
                let selectedOption = selectElement.options[selectElement.selectedIndex];
                let productNameInput = selectElement
                    .parentElement
                    .querySelector('input[name*="[product_name]"]');

                if (productNameInput && selectedOption) {
                    productNameInput.value = selectedOption.text;
                }
            }


            // Add Item Row
            document.getElementById('addItem').addEventListener('click', function() {
                let index = document.querySelectorAll('#itemsBody tr').length;
                let row = `
            <tr class="border-bottom">
                <td class="ps-3">
                    <select name="items[${index}][product_id]" class="form-select product-select" required>
                        <option value="">Select Product</option>
                    </select>
                    <input type="hidden" name="items[${index}][product_name]" value="">
                </td>
                <td><input type="number" name="items[${index}][quantity]" class="form-control quantity" value="1" min="1" required></td>
                <td>
                    <div class="input-group"><span class="input-group-text">₹</span><input type="number" step="0.01" name="items[${index}][unit_price]" class="form-control unit-price" value="0.00" required></div>
                </td>
                <td>
                    <div class="input-group"><span class="input-group-text">₹</span><input type="number" step="0.01" class="form-control line-total bg-light" value="0.00" readonly></div>
                </td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm border-0 remove-item"><i class="bi bi-trash"></i></button></td>
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
                if (e.target.closest('.remove-item')) {
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
                if (totalEl) totalEl.value = grandTotal.toFixed(2);

                // Calculate tax and discount
                let taxPercent = parseFloat(document.getElementById('tax').value) || 0;
                let discountPercent = parseFloat(document.getElementById('discount').value) || 0;

                let taxAmount = (grandTotal * taxPercent) / 100;
                let discountAmount = (grandTotal * discountPercent) / 100;

                let finalTotal = grandTotal + taxAmount - discountAmount;

                let finalEl = document.getElementById('finalTotal');
                if (finalEl) finalEl.value = (finalTotal > 0 ? finalTotal : 0).toFixed(2);
            }

            // Listen for changes in Tax/Discount/Payment to update totals
            document.getElementById('tax').addEventListener('input', recalculateTotals);
            document.getElementById('discount').addEventListener('input', recalculateTotals);

            // Initial calculation
            recalculateTotals();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const customerType = "{{ old('customer_type', 'existing') }}";

            if (customerType === 'new') {
                document.getElementById('new_customer_fields').style.display = 'block';
                document.getElementById('existing_customer_fields').style.display = 'none';
            } else {
                document.getElementById('existing_customer_fields').style.display = 'block';
                document.getElementById('new_customer_fields').style.display = 'none';
            }
        });




        //last section will be recalculateTotals
        document.addEventListener('DOMContentLoaded', function() {
            recalculateTotals();
        });
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let availableProducts = [];

            /* ===============================
               CUSTOMER TYPE TOGGLE
            =============================== */
            document.querySelectorAll('input[name="customer_type"]').forEach(el => {
                el.addEventListener('change', function() {
                    if (this.value === 'new') {
                        document.getElementById('new_customer_fields').style.display = 'block';
                        document.getElementById('existing_customer_fields').style.display = 'none';
                        document.getElementById('customer_id').removeAttribute('required');
                    } else {
                        document.getElementById('new_customer_fields').style.display = 'none';
                        document.getElementById('existing_customer_fields').style.display = 'block';
                        document.getElementById('customer_id').setAttribute('required', 'required');
                    }
                });
            });

            /* ===============================
               FETCH PRODUCTS BY SHOP
            =============================== */
            const shopSelect = document.getElementById('shop_id');
            if (shopSelect) {
                shopSelect.addEventListener('change', function() {
                    fetchProducts(this.value);
                });

                if (shopSelect.value) {
                    fetchProducts(shopSelect.value);
                }
            }

            function fetchProducts(shopId) {
                if (!shopId) return;

                fetch(`/get-products-by-shop/${shopId}`)
                    .then(res => res.json())
                    .then(data => {
                        availableProducts = data;
                        document.querySelectorAll('.product-select')
                            .forEach(select => populateProductSelect(select));
                    });
            }

            function populateProductSelect(select) {
                let current = select.value;
                let html = '<option value="">Select Product</option>';
                let productExists = false;

                availableProducts
                    .forEach(p => {
                        if (p.id == current) productExists = true;
                        html += `<option value="${p.id}" ${p.id == current ? 'selected' : ''}>
                            ${p.product_name}
                        </option>`;
                    });

                select.innerHTML = html;

                // If a product is already selected (e.g. Edit Mode), trigger batch load
                if (current && productExists) {
                    let row = select.closest('tr');
                    let batchSelect = row.querySelector('.batch-select');
                    let selectedBatchId = batchSelect.getAttribute('data-selected-batch');
                    loadBatches(current, batchSelect, selectedBatchId);
                }
            }

            /* ===============================
               ADD ITEM ROW
            =============================== */
            document.getElementById('addItem').addEventListener('click', function() {
                let index = document.querySelectorAll('#itemsBody tr').length;

                let row = `
        <tr>
            <td>
                <select name="items[${index}][product_id]" class="form-select product-select" required></select>
            </td>
            <td>
                <select name="items[${index}][batch_id]" class="form-select batch-select" required>
                    <option value="">Select Batch</option>
                </select>
            </td>
            <td>
                <input type="number" name="items[${index}][quantity]"
                       class="form-control quantity" value="1" min="1" required>
            </td>
            <td>
                <input type="number" name="items[${index}][unit_price]"
                       class="form-control unit-price" value="0" readonly>
            </td>
            <td>
                <input type="number" class="form-control line-total" value="0" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
            </td>
        </tr>
        `;

                document.getElementById('itemsBody').insertAdjacentHTML('beforeend', row);

                populateProductSelect(
                    document.querySelector('#itemsBody tr:last-child .product-select')
                );
            });

            /* ===============================
               REMOVE ROW
            =============================== */
            document.getElementById('itemsBody').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('tr').remove();
                    recalculateTotals();
                }
            });

            /* ===============================
               PRODUCT → LOAD BATCHES
               BATCH → SET PRICE
            =============================== */
            document.getElementById('itemsBody').addEventListener('change', function(e) {

                // Product selected
                if (e.target.classList.contains('product-select')) {

                    let row = e.target.closest('tr');
                    let productId = e.target.value;
                    let batchSelect = row.querySelector('.batch-select');
                    loadBatches(productId, batchSelect);
                }

                // Batch selected
                if (e.target.classList.contains('batch-select')) {
                    let row = e.target.closest('tr');
                    let option = e.target.options[e.target.selectedIndex];
                    row.querySelector('.unit-price').value = option.getAttribute('data-price') || 0;
                    updateLineTotal(row);
                }
            });

            function loadBatches(productId, batchSelect, selectedBatchId = null) {
                batchSelect.innerHTML = '<option>Loading...</option>';

                fetch(`/get-batches/${productId}`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to fetch batches');
                        }
                        return res.json();
                    })
                    .then(batches => {
                        batchSelect.innerHTML = '<option value="">Select Batch</option>';
                        if (batches.length === 0) {
                            batchSelect.innerHTML = '<option value="">No Batches Found</option>';
                            return;
                        }
                        batches.forEach(batch => {
                            let isSelected = (selectedBatchId && batch.id == selectedBatchId) ? 'selected' : '';
                            batchSelect.innerHTML += `
                        <option value="${batch.id}"
                                data-price="${batch.selling_price}"
                                data-stock="${batch.quantity}" ${isSelected}>
                            ${batch.batch_no} (Stock: ${batch.quantity})
                        </option>`;
                        });
                    })
                    .catch(err => {
                        console.error('Error loading batches:', err);
                        batchSelect.innerHTML = '<option value="">Error Loading Batches</option>';
                    });
            }

            /* ===============================
               QUANTITY CHANGE
            =============================== */
            document.getElementById('itemsBody').addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity')) {
                    let row = e.target.closest('tr');
                    let batchSelect = row.querySelector('.batch-select');
                    let stock = batchSelect?.options[batchSelect.selectedIndex]?.dataset.stock || 0;

                    if (parseInt(e.target.value) > parseInt(stock)) {
                        alert('Quantity exceeds batch stock!');
                        e.target.value = stock;
                    }

                    updateLineTotal(row);
                }
            });

            /* ===============================
               TOTAL CALCULATION
            =============================== */
            function updateLineTotal(row) {
                let qty = parseFloat(row.querySelector('.quantity').value) || 0;
                let price = parseFloat(row.querySelector('.unit-price').value) || 0;
                row.querySelector('.line-total').value = (qty * price).toFixed(2);
                recalculateTotals();
            }

            function recalculateTotals() {
                let total = 0;
                document.querySelectorAll('.line-total').forEach(el => {
                    total += parseFloat(el.value) || 0;
                });

                document.getElementById('totalAmount').value = total.toFixed(2);

                let tax = parseFloat(document.getElementById('tax').value) || 0;
                let discount = parseFloat(document.getElementById('discount').value) || 0;

                let final = total + (total * tax / 100) - (total * discount / 100);
                document.getElementById('finalTotal').value = final.toFixed(2);
            }

            document.getElementById('tax').addEventListener('input', recalculateTotals);
            document.getElementById('discount').addEventListener('input', recalculateTotals);

            recalculateTotals();
        });
    </script>

@endsection
