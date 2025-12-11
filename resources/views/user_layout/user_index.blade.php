<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Index</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap-5/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/user_css/user_topnavbar.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/user_css/user-dashboard.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/user_css/add-customer.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/user_css/user-profile.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/user_css/show_bill.css')}}" />

    <style>

    </style>
</head>

<body>

    <div class="dashboard-wrapper">

        <!-- Sidebar Partial -->
        @include('user_layout.user_sidebar')

        <!-- Main Content Wrapper -->
        <div class="main-content">

            <!-- Top Navbar Partial -->
            <header class="dashboard-navbar">
                @include('user_layout.user_topnavbar')
            </header>

            <!-- Page-specific Content -->
            <main class="p-4">
                @yield('content')
            </main>

        </div>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay"></div>
    </div>


    <script src="{{asset('assets/jquery/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap-5/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            const sidebarToggle = $('#sidebarToggle');
            // const sidebarToggle1 = $('#sidebarToggle1');
            const wrapper = $('.dashboard-wrapper');
            const overlay = $('.sidebar-overlay');

            function isMobile() {
                return window.innerWidth < 992;
            }
            // sidebarToggle1.on('click', function() {
            //     if (isMobile()) {
            //         wrapper.toggleClass('sidebar-open');
            //     } else {
            //         wrapper.toggleClass('sidebar-collapsed');
            //     }
            // });

            sidebarToggle.on('click', function() {
                if (isMobile()) {
                    wrapper.toggleClass('sidebar-open');
                } else {
                    wrapper.toggleClass('sidebar-collapsed');
                }
            });

            overlay.on('click', function() {
                wrapper.removeClass('sidebar-open');
            });

            // Optional: Remove sidebar-open on resize to desktop
            $(window).on('resize', function() {
                if (!isMobile()) {
                    wrapper.removeClass('sidebar-open');
                }
            });
        });

        // Example for Monthly Revenue Line Chart

        document.addEventListener("DOMContentLoaded", () => {
            // Monthly Revenue Chart
            const ctxRevenue = document.getElementById('monthlyRevenueChart');
            if (ctxRevenue) {
                new Chart(ctxRevenue, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Revenue',
                            data: [1200, 1900, 3000, 5000, 2300, 3100, 4000],
                            borderColor: '#0d6efd',
                            tension: 0.2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Invoice Status Pie Chart
            const ctxStatus = document.getElementById('invoiceStatusChart');
            if (ctxStatus) {
                new Chart(ctxStatus, {
                    type: 'doughnut',
                    data: {
                        labels: ['Paid', 'Pending', 'Overdue'],
                        datasets: [{
                            data: [300, 82, 15],
                            backgroundColor: ['#198754', '#ffc107', '#dc3545']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Payment Methods Bar Chart
            const ctxMethods = document.getElementById('paymentMethodsChart');
            if (ctxMethods) {
                new Chart(ctxMethods, {
                    type: 'bar',
                    data: {
                        labels: ['UPI', 'Cash'],
                        datasets: [{
                            label: 'Payments',
                            data: [150, 250],
                            backgroundColor: ['#6f42c1', '#007bff']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
</body>

</html>