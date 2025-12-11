<?php

namespace App\Http\Controllers;

use App\Models\AddCustomer;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function user_dashboard(Request $request)
    {
        $user = $request->user();
        $totalCustomers = $user->customers()->count();
        $pendingInvoices = $user->invoices()->where('status', 'draft')->count(); // FIXED LINE
        $totalPayments = 25430; // Example: Payment::sum('amount');
        $revenueThisMonth = 8500; // Example: Payment::whereMonth('created_at', now()->month)->sum('amount');
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
