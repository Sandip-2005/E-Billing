<?php

namespace App\Http\Controllers;

use App\Models\InvoiceModel;
use App\Models\PaymentsModel;
use App\Models\AddCustomer;
use App\Models\ShopModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function storePayment(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $invoice = InvoiceModel::findOrFail($request->invoice_id);

            $paidAmount = $request->amount;
            $currentDue = $invoice->getDueAmountAttribute(); // Use the accessor

            if ($paidAmount > $currentDue) {
                return back()->withErrors(['amount' => 'Payment amount exceeds the due amount.']);
            }

            // Create payment record
            PaymentsModel::create([
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'amount' => $paidAmount,
                'payment_date' => $request->payment_date,
                'note' => $request->note,
                'due_amount' => $currentDue - $paidAmount, // Store the due amount after this payment
                'payment_status' => ($paidAmount >= $currentDue) ? 'paid' : 'partially_paid',
            ]);

            // Update invoice status and due amount
            $invoice->due_amount = $currentDue - $paidAmount; // Update the invoice's due_amount

            if ($invoice->due_amount <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->due_amount < $invoice->total) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'unpaid';
            }
            $invoice->save();

            DB::commit();

            return redirect()->route('payment_history')->with('success', 'Payment recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record payment: ' . $e->getMessage()]);
        }
    }

    public function paymentHistory(Request $request)
    {
        // available filter lists
        $customers = AddCustomer::where('user_id', auth()->id())->get();
        $shops = ShopModel::where('user_id', auth()->id())->get();

        // build query
        $query = InvoiceModel::with(['payments', 'customer', 'shop'])
            ->where('user_id', auth()->id())->where('status','!=','draft');

        // apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('bill_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('bill_date', '<=', $request->date_to);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status)
            &&$query->orWhere('payment_mode', $request->status);
        }
        

        $invoices = $query->latest('bill_date')
            ->paginate(50)
            ->appends($request->except('page'));

        $filters = $request->only(['date_from', 'date_to', 'customer_id', 'shop_id', 'status']);

        return view('user_layout.user_payments.payment_history', compact('invoices','customers','shops','filters'));
    }

    public function paymentsDues()
    {
        $partiallyPaidInvoices = InvoiceModel::where('user_id', Auth::id())
            ->whereIn('status', ['unpaid', 'partially_paid'])->where('due_amount', '>', 0)
            ->with(['customer', 'shop'])
            ->latest('bill_date')
            ->paginate(50);

        return view('user_layout.user_payments.payment_dues', compact('partiallyPaidInvoices'));
    }
}
