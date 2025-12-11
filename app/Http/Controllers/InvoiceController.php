<?php

namespace App\Http\Controllers;

use App\Models\ShopModel;
use App\Models\AddCustomer;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\PaymentsModel; // Add this line
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
            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $finalTotal = $this->calculateTotal($request->items, $tax, $discount);

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
                'tax' => $tax,
                'discount' => $discount,
                'total' => $finalTotal,
                'status' => $invoiceStatus,
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

            // Store each bill item
            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                InvoiceItemModel::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'] ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ]);
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
}
