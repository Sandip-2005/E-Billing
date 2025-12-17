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
        // $overdueInvoices = 15; // Example: Invoice::where('status', 'overdue')->count();
        $online=InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->where('payment_mode', 'ONLINE')
            ->count();
        $cash=InvoiceModel::where('user_id', Auth::guard('cuser')->id())
            ->where('payment_mode', 'Cash')
            ->count();

        // Monthly Revenue Chart Data (Last 7 Months)
        $revenueChartLabels = [];
        $revenueChartData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueChartLabels[] = $date->format('M');
            
            $monthlySum = PaymentsModel::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
                ->where('invoices.user_id', $user->id)
                ->whereYear('payments.payment_date', $date->year)
                ->whereMonth('payments.payment_date', $date->month)
                ->sum('payments.amount');
                
            $revenueChartData[] = $monthlySum;
        }

        $paid = PaymentsModel::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', Auth::guard('cuser')->id())->where('payment_status', 'paid');
        $pending= PaymentsModel::join('invoices', 'payments.invoice_id', '=', 'invoices.id')
            ->where('invoices.user_id', Auth::guard('cuser')->id())->where('payment_status', 'partially_paid');

        return view('user_layout.user_dashboard', compact(
            'totalCustomers',
            'pendingInvoices',
            'totalPayments',
            'revenueThisMonth',
            // 'overdueInvoices',
            'online',
            'cash',
            'revenueChartLabels',
            'revenueChartData',
            'paid',
            'pending'
        ));
    }
}
