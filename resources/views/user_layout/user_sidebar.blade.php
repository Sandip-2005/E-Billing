<nav class="sidebar-light">
    <div class="sidebar-header">
        <a href="{{ route('user_dashboard') }}" class="d-flex align-items-center text-decoration-none text-dark">
            <i class="bi bi-receipt-cutoff me-2 fs-5"></i>
            <h5 class="mb-0">E-Billing</h5>
        </a>
    </div>
    <div class="sidebar-body">
        <ul class="nav flex-column sidebar-nav">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('user_dashboard') }}">
                <i class="bi bi-house-dash-fill me-2"></i> <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#customerSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="customerSubmenu">
                <i class="bi bi-people-fill me-2"></i> <span>Customers</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="customerSubmenu">
                <li><a class="nav-link" href="{{ route('add_customer') }}"><i class="bi bi-dot"></i> Add Customer</a></li>
                <li><a class="nav-link" href="{{ route('manage_customers') }}"><i class="bi bi-dot"></i> Manage Customers</a></li>
                {{-- <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Customer History</a></li> --}}
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#invoiceSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="invoiceSubmenu">
                <i class="bi bi-receipt me-2"></i> <span>Billing / Invoices</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="invoiceSubmenu">
                <li><a class="nav-link" href="{{ route('make_new_invoice') }}"><i class="bi bi-dot"></i> Create New Invoice</a></li>
                <li><a class="nav-link" href="{{ route('list_invoices') }}"><i class="bi bi-dot"></i> Invoice List (all bills)</a></li>
                {{-- <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Pending Payments</a></li> --}}
                <li><a class="nav-link" href="{{ route('draft_invoice_list') }}"><i class="bi bi-dot"></i> Draft Invoices</a></li>
            </ul>
        </li>
        
        <li class="nav-item">
            <a class="nav-link collapsed" href="#paymentSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="paymentSubmenu">
                <i class="bi bi-credit-card-fill me-2"></i> <span>Payments</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="paymentSubmenu">
                {{-- <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Record Payment</a></li> --}}
                <li><a class="nav-link" href="{{ route('payment_history') }}"><i class="bi bi-dot"></i> Payment History</a></li>
                <li><a class="nav-link" href="{{ route('payments_dues') }}"><i class="bi bi-dot"></i> Dues/Outstanding</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#inventorySubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="inventorySubmenu">
                <i class="bi bi-box-seam me-2"></i> <span>Inventory</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="inventorySubmenu">
                <li><a class="nav-link" href="{{ route('add_products') }}"><i class="bi bi-dot"></i> Add Products</a></li>
                <li><a class="nav-link" href="{{ route('manage_products') }}"><i class="bi bi-dot"></i> Manage Products</a></li>
                <li><a class="nav-link" href="{{ route('stock_alert') }}"><i class="bi bi-dot"></i> Stock Alerts</a></li>
            </ul>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link collapsed" href="#reportSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="reportSubmenu">
                <i class="bi bi-journal-text me-2"></i> <span>Reports</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="reportSubmenu">
                <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Sales Report</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Tax/GST Report</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Product Report</a></li>
                <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Export (Excel/PDF)</a></li>
            </ul>
        </li>
        
        <li class="nav-item">
             <a class="nav-link collapsed" href="#notifSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="notifSubmenu">
                <i class="bi bi-bell-fill me-2"></i> <span>Notifications</span>
                <i class="bi bi-chevron-down arrow ms-auto"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="notifSubmenu">
                 <li><a class="nav-link" href="#"><i class="bi bi-dot"></i> Reminders (payments due)</a></li>
            </ul>
        </li> --}}
    </ul>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentUrl = window.location.href;
        const dashboardLink = document.querySelector('.sidebar-nav > .nav-item > a.nav-link');
        if (dashboardLink && dashboardLink.href !== currentUrl) {
            dashboardLink.classList.remove('active');
        }

        const submenuLinks = document.querySelectorAll('.sidebar-nav .submenu .nav-link');

        submenuLinks.forEach(function(link) {

            if (link.href === currentUrl) {

                link.classList.add('active');

                const submenu = link.closest('.submenu.collapse');
                if (submenu) {
                    submenu.classList.add('show');

                    const triggerLink = document.querySelector(`a[data-bs-toggle="collapse"][href="#${submenu.id}"]`);
                    if (triggerLink) {
                        triggerLink.classList.add('active');
                        triggerLink.classList.remove('collapsed');
                        triggerLink.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        });

        const topLevelLinks = document.querySelectorAll('.sidebar-nav > .nav-item > a.nav-link');
        topLevelLinks.forEach(function(link) {
            if (link.href === currentUrl) {
                link.classList.add('active');
            }
        });
    });
</script>
