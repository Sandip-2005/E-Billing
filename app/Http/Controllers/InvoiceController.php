<?php

namespace App\Http\Controllers;

use App\Models\ShopModel;
use App\Models\AddCustomer;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\PaymentsModel; // Add this line
use App\Models\ProductModel; // <<< ADDED
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function make_new_invoice()
    {
        $user = Auth::guard('cuser')->user();
        $shops = $user->shops;
        $customers = $user->customers;

        return view('user_layout.user_billing.make_new_invoice', [
            'shops' => $shops,
            'customers' => $customers
        ]);
    }
    public function store_invoice(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shop_profile,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'payment_received' => 'nullable|numeric|min:0',
            'payment_mode' => 'required|string|in:cash,online',
        ]);

        DB::beginTransaction();

        try {
            // Handle new or existing customer
            if ($request->has('customer_name') && $request->customer_name !== null) {
                $customer = AddCustomer::create([
                    'user_id' => Auth::guard('cuser')->id(),
                    'customer_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone_number' => $request->customer_phone,
                    'address' => $request->customer_address,
                ]);
                $customer_id = $customer->id;
            } else {
                $customer_id = $request->customer_id;
            }

            // Determine if saving as draft
            $isDraft = $request->input('save_as_draft') ? true : false;

            // Fetch shop details to ensure data integrity
            $shop = ShopModel::findOrFail($request->shop_id);

            // Calculate totals
            $subTotal = $this->calculateSubTotal($request->items);
            $taxPercent = $request->tax ?? 0;
            $discountPercent = $request->discount ?? 0;
            $taxAmount = ($subTotal * $taxPercent) / 100;
            $discountAmount = ($subTotal * $discountPercent) / 100;
            $finalTotal = max(0, $subTotal + $taxAmount - $discountAmount);

            // Handle payment received logic
            $paymentReceived = $request->input('payment_received', 0);
            $dueAmount = max(0, $finalTotal - $paymentReceived);

            $invoiceStatus = 'unpaid';
            if ($isDraft) {
                $invoiceStatus = 'draft';
            } elseif ($paymentReceived >= $finalTotal) {
                $invoiceStatus = 'paid';
            } elseif ($paymentReceived > 0 && $paymentReceived < $finalTotal) {
                $invoiceStatus = 'partially_paid';
            }

            // Create main bill
            $bill = InvoiceModel::create([
                'user_id' => Auth::guard('cuser')->id(),
                'shop_id' => $request->shop_id,
                'customer_id' => $customer_id,
                'shop_phone' => $shop->shop_contact,
                'shop_gst' => $shop->gst_number,
                'shop_address' => $shop->shop_address,
                'bill_date' => $request->bill_date,
                'sub_total' => $subTotal,
                'tax' => $taxAmount,
                'discount' => $discountAmount,
                'total' => $finalTotal,
                'status' => $invoiceStatus,
                'payment_mode'=>$request->payment_mode,
                'due_amount' => $dueAmount, // Add due_amount to invoice
            ]);

            // Store the initial payment if payment_received > 0 and not a draft
            if ($paymentReceived > 0 && !$isDraft) {
                PaymentsModel::create([
                    'invoice_id' => $bill->id,
                    'customer_id' => $customer_id,
                    'amount' => $paymentReceived,
                    'payment_date' => now(), // Or $request->bill_date if preferred
                    'note' => 'Initial payment',
                    'due_amount' => $dueAmount, // Store the due amount at the time of this payment
                    'payment_status' => ($paymentReceived >= $finalTotal) ? 'paid' : 'partially_paid',
                ]);
            }

            // Store each bill item and decrement product stock
            foreach ($request->items as $item) {
                // Validate product exists and stock available
                $product = ProductModel::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Product not found (ID: {$item['product_id']})");
                }
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name} (Available: {$product->quantity})");
                }

                $lineTotal = $item['quantity'] * $item['unit_price'];
                InvoiceItemModel::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? $product->product_name ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);

                // decrement stock and save
                $product->quantity = $product->quantity - $item['quantity'];
                $product->save();
            }

            DB::commit();

            if ($invoiceStatus === 'draft') {
                return redirect()->route('make_new_invoice')->with('success', 'Bill saved as draft!');
            } else {
                return redirect()->route('show_invoice', ['id' => $bill->id])->with('success', 'Bill created successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create bill: ' . $e->getMessage()]);
        }
    }

    private function calculateSubTotal($items)
    {
        $sum = 0;
        foreach ($items as $item) {
            $sum += ($item['quantity'] * $item['unit_price']);
        }
        return $sum;
    }

    private function calculateTotal($items, $tax, $discount)
    {
        $sub = $this->calculateSubTotal($items);
        return max(0, $sub + ($tax ?? 0) - ($discount ?? 0));
    }

    public function show_invoice($id)
    {
        $bill = \App\Models\InvoiceModel::with(['shop', 'customer', 'items', 'payments'])->findOrFail($id);
        return view('user_layout.user_billing.show_invoice', compact('bill'));
    }

    public function list_invoices()
    {
        $invoices = InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->with(['customer', 'shop'])
            ->latest('bill_date')
            ->paginate(15);

        return view('user_layout.user_billing.list_invoices', [
            'invoices' => $invoices
        ]);
    }

    public function draft_invoice_list()
    {
        $draftInvoices = InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->where('status', 'draft')
            ->with(['customer', 'shop'])
            ->latest('bill_date')
            ->paginate(15);

        return view('user_layout.user_billing.draft_invoice_list', [
            'draftInvoices' => $draftInvoices
        ]);
    }
    
    public function edit_invoice($id)
    {
        $invoice = InvoiceModel::with(['items', 'customer', 'shop'])->findOrFail($id);
        if ($invoice->status !== 'draft') {
            abort(403, 'Only draft invoices can be edited.');
        }
        $user = Auth::guard('cuser')->user();
        $shops = $user->shops;
        $customers = $user->customers;

        return view('user_layout.user_billing.make_new_invoice', [
            'shops' => $shops,
            'customers' => $customers,
            'invoice' => $invoice, // Pass invoice data for prefill
        ]);
    }

    public function submit_draft($id)
    {
        $invoice = InvoiceModel::findOrFail($id);
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['error' => 'Only draft invoices can be submitted.']);
        }

        DB::beginTransaction();
        try {
            // Recalculate totals if needed (assuming no changes)
            $paymentReceived = $invoice->paid_amount ?? 0;
            $dueAmount = max(0, $invoice->total - $paymentReceived);

            $status = 'unpaid';
            if ($paymentReceived >= $invoice->total) {
                $status = 'paid';
            } elseif ($paymentReceived > 0) {
                $status = 'partially_paid';
            }

            $invoice->update([
                'status' => $status,
                'due_amount' => $dueAmount,
            ]);

            // If there's an initial payment, ensure it's recorded (already handled in store_invoice)
            DB::commit();
            return redirect()->route('show_invoice', $id)->with('success', 'Invoice submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit invoice: ' . $e->getMessage()]);
        }
    }

    public function update_invoice(Request $request, $id)
    {
        $invoice = InvoiceModel::with('items')->findOrFail($id);
        if ($invoice->status !== 'draft') {
            abort(403, 'Only draft invoices can be updated.');
        }

        $request->validate([
            'shop_id' => 'required|exists:shop_profile,id',
            'bill_date' => 'required|date',
            'items' => 'required|array|min:1',
            'payment_received' => 'nullable|numeric|min:0',
            'payment_mode' => 'required|string|in:cash,online',
        ]);

        DB::beginTransaction();
        try {
            // Restore old stock
            foreach ($invoice->items as $oldItem) {
                $product = ProductModel::find($oldItem->product_id);
                if ($product) {
                    $product->quantity += $oldItem->quantity;
                    $product->save();
                }
            }

            // Delete old items
            InvoiceItemModel::where('bill_id', $id)->delete();

            // Handle customer (similar to store_invoice)
            if ($request->has('customer_name') && $request->customer_name !== null) {
                $customer = AddCustomer::create([
                    'user_id' => Auth::guard('cuser')->id(),
                    'customer_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone_number' => $request->customer_phone,
                    'address' => $request->customer_address,
                ]);
                $customer_id = $customer->id;
            } else {
                $customer_id = $request->customer_id;
            }

            $shop = ShopModel::findOrFail($request->shop_id);
            $subTotal = $this->calculateSubTotal($request->items);
            $taxPercent = $request->tax ?? 0;
            $discountPercent = $request->discount ?? 0;
            $taxAmount = ($subTotal * $taxPercent) / 100;
            $discountAmount = ($subTotal * $discountPercent) / 100;
            $finalTotal = max(0, $subTotal + $taxAmount - $discountAmount);
            $paymentReceived = $request->input('payment_received', 0);
            $dueAmount = max(0, $finalTotal - $paymentReceived);

            $isDraft = $request->input('save_as_draft') ? true : false;
            $invoiceStatus = $isDraft ? 'draft' : ($paymentReceived >= $finalTotal ? 'paid' : ($paymentReceived > 0 ? 'partially_paid' : 'unpaid'));

            // Update invoice
            $invoice->update([
                'shop_id' => $request->shop_id,
                'customer_id' => $customer_id,
                'shop_phone' => $shop->shop_contact,
                'shop_gst' => $shop->gst_number,
                'shop_address' => $shop->shop_address,
                'bill_date' => $request->bill_date,
                'sub_total' => $subTotal,
                'tax' => $taxAmount,
                'discount' => $discountAmount,
                'total' => $finalTotal,
                'status' => $invoiceStatus,
                'payment_mode' => $request->payment_mode,
                'due_amount' => $dueAmount,
            ]);

            // Handle payment (delete old, add new if not draft)
            PaymentsModel::where('invoice_id', $id)->delete();
            if ($paymentReceived > 0 && !$isDraft) {
                PaymentsModel::create([
                    'invoice_id' => $id,
                    'customer_id' => $customer_id,
                    'amount' => $paymentReceived,
                    'payment_date' => now(),
                    'note' => 'Updated payment',
                    'due_amount' => $dueAmount,
                    'payment_status' => ($paymentReceived >= $finalTotal) ? 'paid' : 'partially_paid',
                ]);
            }

            // Add new items and deduct stock
            foreach ($request->items as $item) {
                $product = ProductModel::find($item['product_id']);
                if (!$product || $product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}");
                }

                $lineTotal = $item['quantity'] * $item['unit_price'];
                InvoiceItemModel::create([
                    'bill_id' => $id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? $product->product_name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);

                $product->quantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            if($request->input('save_as_draft')){
                return redirect()->route('draft_invoice_list')->with('success', 'Invoice updated and saved as draft!');
            }
            else{
                return redirect()->route('show_invoice', $id)->with('success', 'Invoice updated successfully!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update invoice: ' . $e->getMessage()]);
        }
    }

    public function getProductsByShop($shopId)
    {
        $products = ProductModel::where('shop_id', $shopId)->get(['id', 'product_name', 'quantity', 'price']); // Ensure 'price' field exists; if it's 'unit_price', change accordingly
        return response()->json($products);
    }
}
