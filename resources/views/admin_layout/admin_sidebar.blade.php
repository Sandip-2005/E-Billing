<!-- Sidebar for Large Screens (always visible) -->
<nav class="admin-sidebar bg-light border-end">
    <div class="sidebar-header p-3 mb-3">
        <a href="{{ route('admin_dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <i class="bi bi-people-fill fs-3 me-2 text-primary"></i>
            <h5 class="mb-0 text-dark">Admin Panel</h5>
        </a>
    </div>

    <ul class="nav flex-column">
        <!-- Dashboard --> 
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin_dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <!-- Manage Users -->
        <li class="nav-item">
            <a class="nav-link collapsed d-flex justify-content-between align-items-center" 
               data-bs-toggle="collapse" href="#usersMenu" role="button" aria-expanded="false">
                <span><i class="bi bi-person-lines-fill me-2"></i> Manage Users</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="usersMenu">
                <li><a class="nav-link" href="{{ route('admin_add_user') }}">Add User</a></li>
                <li><a class="nav-link" href="{{ route('manage_users') }}">User List</a></li>
                <li><a class="nav-link" href="">User History</a></li>
                <li><a class="nav-link" href="">Roles & Permissions</a></li>
            </ul>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a class="nav-link collapsed d-flex justify-content-between align-items-center" 
               data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false">
                <span><i class="bi bi-gear-fill me-2"></i> Settings</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <ul class="collapse list-unstyled submenu" id="settingsMenu">
                <li><a class="nav-link" href="#">Profile Settings</a></li>
                <li><a class="nav-link" href="#">System Config</a></li>
            </ul>
        </li>

        <!-- Logout -->
        <li class="nav-item mt-3">
            <form action="{{ route('admin_logout') }}" method="get" class="d-inline">
                @csrf
                <button type="submit" class="nav-link text-danger w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>
