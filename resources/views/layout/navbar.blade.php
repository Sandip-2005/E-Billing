<nav class="navbar navbar-expand-lg" style="background-color: #7af895ff;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand d-flex flex-column align-items-start ms-auto ms-lg-0 order-lg-1" href="{{ route('index') }}">
            <span style="font-size: 1.4rem; font-weight: bold;">E-Billing</span>
            <small style="font-size: 0.6rem; color: #3a3737ff;">Created By Sandipan Bhunia</small>
        </a>

        <div class="collapse navbar-collapse order-lg-2" id="navbarSupportedContent">

            <ul class="navbar-nav nav-middle mb-1 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about_us') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact_us') }}">Contact</a>
                </li>
            </ul>

            <ul class="navbar-nav nav-right mb-1 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Login Options
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        <li><a class="dropdown-item" href="{{ route('signup') }}">Registration</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.login.form') }}">Admin Login</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>