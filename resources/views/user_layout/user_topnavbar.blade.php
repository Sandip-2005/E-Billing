<!-- ======== TOP NAVBAR START ======== -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm dashboard-navbar">
    <div class="container-fluid">
        <!-- Left Side: Website Name & Sidebar Toggle -->
        <div class="d-flex align-items-center">
            <button class="btn btn-light me-3" type="button" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a class="navbar-brand fw-bold" href="#">E-Billing</a>
        </div>

        <!-- Add Bootstrap Navbar Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <!-- <span class="navbar-toggler-icon"></span> -->
            <i class="bi bi-person fs-5"></i>
        </button>

        <!-- Right Side: Profile Dropdown -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-dropdown-toggle d-flex align-items-center" href="#"
                        id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- User Name -->
                        <span>{{ Auth::guard('cuser')->user()->name }}</span>

                        <img src="{{ asset('storage/'.Auth::guard('cuser')->user()->user_image) }}" alt="{{ asset('storage/user_images/user.png') }}"
                            class="profile-pictures rounded-circle"
                            style="object-fit: cover; width: 40px; height: 40px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('user_shop_profile') }}">
                                <i class="bi bi-shop me-2"></i>
                                Shop Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user_profile') }}">
                                <i class="bi bi-person-fill me-2"></i>
                                User Profile
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('user_logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item logout-link">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- ======== TOP NAVBAR END ======== -->
