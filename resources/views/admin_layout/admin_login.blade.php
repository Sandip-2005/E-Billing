@extends('layout.index')
@section('content')

<div class="login-container">
    <div class="card login-box">

        <div class="login-header text-center mb-4">
            <i class="bi bi-person-circle icon"></i>
            <h2 class="mt-2">Welcome Back!</h2>
            <p class="text-muted">Please enter your credentials to log in.</p>
        </div>

        <form method="POST" action="{{ route('admin_login') }}">
            @csrf

            <div class="form-floating position-relative mb-3">
                <input type="text" class="form-control" id="usernameoremail" name="usernameoremail" placeholder="Email/Username" required>
                <label for="usernameoremail">Email or Username</label>
                <i class="bi bi-person input-icon"></i>
            </div>

            <div class="form-floating position-relative mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
                <i class="bi bi-lock input-icon"></i>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rememberMe" name="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>
                </div>
                <a href="#" class="text-decoration-none small">Forgot Password?</a>
            </div>

            <div class="d-grid">
                <button class="btn btn-primary" type="submit">Login</button>
            </div>

        </form>

        <!-- <div class="text-center mt-4 signup-link">
            <p class="text-muted">Don't have an account? <a href="">Register here</a></p>
        </div> -->

    </div>
</div>

@endsection