<?php

namespace App\Http\Controllers;

use App\Models\InvoiceModel;
use App\Models\PaymentsModel;
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

    public function paymentHistory()
    {
        $payments = PaymentsModel::whereHas('invoice', function ($query) {
            $query->where('user_id', Auth::guard('cuser')->id());
        })
        ->with(['invoice.customer', 'invoice.shop'])
        ->latest('payment_date')
        ->paginate(50);

        return view('user_layout.user_payments.payment_history', compact('payments'));
    }

    public function paymentsDues()
    {
        $partiallyPaidInvoices = InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->where('status', 'partially_paid')
            ->with(['customer', 'shop'])
            ->latest('bill_date')
            ->paginate(50);

        return view('user_layout.user_payments.payment_dues', compact('partiallyPaidInvoices'));
    }
}
