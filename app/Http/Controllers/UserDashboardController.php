<?php

namespace App\Http\Controllers;

use App\Models\AddCustomer;
use App\Models\UserModel;
use App\Models\InvoiceModel;
use App\Models\PaymentsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserDashboardController extends Controller
{
    public function user_dashboard(Request $request)
    {
        $user = $request->user();
        $totalCustomers = $user->customers()->count();
        $pendingInvoices = InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->where('status', 'draft')->count();
        $totalPayments = PaymentsModel::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', Auth::guard('cuser')->id())
            ->whereDay('payments.created_at', Carbon::now()->day)
            ->sum('payments.amount');
        $revenueThisMonth = PaymentsModel::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', Auth::guard('cuser')->id())
            ->whereMonth('payments.created_at', Carbon::now()->month)
            ->sum('payments.amount');
        $overdueInvoices = 15; // Example: Invoice::where('status', 'overdue')->count();

        return view('user_layout.user_dashboard', compact(
            'totalCustomers',
            'pendingInvoices',
            'totalPayments',
            'revenueThisMonth',
            'overdueInvoices'
        ));
    }
}
