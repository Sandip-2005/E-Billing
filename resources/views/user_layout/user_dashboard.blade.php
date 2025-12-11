@extends('user_layout.user_index')

@section('content')
    <div class="container-fluid p-4">

        <div class="row g-4 mb-4">
            <!-- Total Customers -->
            <div class="col-xl col-md-6">
                <a href="{{ route('manage_customers') }}" style="text-decoration: none; color: inherit;">
                    <div class="card kpi-card kpi-card-blue h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="kpi-icon me-3">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">{{ $totalCustomers ?? '00' }}</h4>
                                <p class="card-text mb-0">Total Customers</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pending Invoices -->
            <div class="col-xl col-md-6">
                <a href="{{ route('draft_invoice_list') }}" style="text-decoration: none; color: inherit;">
                    <div class="card kpi-card kpi-card-yellow h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="kpi-icon me-3">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <h4 class="card-title mb-1">{{ $pendingInvoices ?? '00' }}</h4>
                                <p class="card-text mb-0">Pending Invoices</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Payments Collected -->
            <div class="col-xl col-md-6">
                <div class="card kpi-card kpi-card-green h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon me-3">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <div>
                            <h4 class="card-title mb-1">${{ number_format($totalPayments ?? 25430, 2) }}</h4>
                            <p class="card-text mb-0">Payments Collected</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue This Month -->
            <div class="col-xl col-md-6">
                <div class="card kpi-card kpi-card-purple h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon me-3">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h4 class="card-title mb-1">${{ number_format($revenueThisMonth ?? 8500, 2) }}</h4>
                            <p class="card-text mb-0">Revenue This Month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue Invoices -->
            <div class="col-xl col-md-6">
                <div class="card kpi-card kpi-card-red h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="kpi-icon me-3">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="card-title mb-1">{{ $overdueInvoices ?? '15' }}</h4>
                            <p class="card-text mb-0">Overdue Invoices</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row -->


        <!-- =================================== -->
        <!-- 2. Main Content Grid              -->
        <!-- =================================== -->
        <div class="row g-4">

            <!-- Main Content Column -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <!-- Monthly Revenue Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Monthly Revenue Trend</h5>
                                <div class="chart-container-lg">
                                    <canvas id="monthlyRevenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Status Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Invoice Status</h5>
                                <div class="chart-container">
                                    <canvas id="invoiceStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Payment Methods</h5>
                                <div class="chart-container">
                                    <canvas id="paymentMethodsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('make_new_invoice') }}" class="btn btn-primary"><i
                                    class="fa-solid fa-plus me-2"></i> Create New Invoice</a>
                            <a href="{{ route('add_customer') }}" class="btn btn-secondary"><i
                                    class="fa-solid fa-user-plus me-2"></i> Add New Customer</a>
                            <a href="#" class="btn btn-outline-secondary"><i class="fa-solid fa-file-export me-2"></i>
                                Generate Report</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Recent Activity</h5>
                        <ul class="list-unstyled recent-activity-list">
                            <li class="activity-item">
                                <div class="activity-icon bg-success"><i class="fa-solid fa-check"></i></div>
                                <div class="activity-text">Payment of <strong>$250.00</strong> received from <em>John
                                        Doe</em>.</div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-info"><i class="fa-solid fa-file-invoice"></i></div>
                                <div class="activity-text">Invoice <strong>#INV-015</strong> for <strong>$1,200</strong> was
                                    generated.</div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-danger"><i class="fa-solid fa-bell"></i></div>
                                <div class="activity-text">Invoice <strong>#INV-009</strong> is now <em>overdue</em>.</div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon bg-success"><i class="fa-solid fa-check"></i></div>
                                <div class="activity-text">Payment of <strong>$85.50</strong> received from <em>Jane
                                        Smith</em>.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div> <!-- .row -->
    </div> <!-- .container-fluid -->
@endsection
