<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap-5/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/login_signup.css')}}" />

</head>

<body>

    <div class="admin-wrapper">

        <!-- Sidebar -->
        @include('admin_layout.admin_sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <!-- Sidebar Toggle for mobile -->
            <button class="btn btn-light m-1 d-lg-none" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>

            <!-- Page Content -->
            <main class="p-2 p-md-4">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay"></div>
    </div>

    <script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const sidebarToggle = $('#sidebarToggle');
            const wrapper = $('.admin-wrapper');
            const overlay = $('.sidebar-overlay');

            function isMobile() {
                return window.innerWidth < 992;
            }

            sidebarToggle.on('click', function() {
                wrapper.toggleClass('sidebar-open');
            });

            overlay.on('click', function() {
                wrapper.removeClass('sidebar-open');
            });

            // Close mobile sidebar on window resize to desktop
            $(window).on('resize', function() {
                if (!isMobile()) {
                    wrapper.removeClass('sidebar-open');
                }
            });
        });
    </script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-sidebar {
            width: 20%;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s ease;
        }
        .main-content {
            margin-left: 20%;
            width: calc(100% - 20%);
            transition: margin-left 0.3s ease;
        }

        /* Mobile styles */
        @media (max-width: 991.98px) {
            /* Sidebar is hidden by default on mobile */
            .admin-sidebar {
                width: 280px; /* A fixed width for mobile is often better */
                left: -280px;
                transition: left 0.3s ease;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
            }
            .admin-wrapper.sidebar-open .admin-sidebar {
                left: 0; /* Show sidebar */
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }

            /* Overlay for when mobile sidebar is open */
            .sidebar-overlay {
                display: none;
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 1039; /* Below sidebar */
            }
            .admin-wrapper.sidebar-open .sidebar-overlay {
                display: block;
            }
        }
    </style>

</body>


</html>