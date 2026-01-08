@extends('layout.index')
@section('content')
    <div class="container m-5 p-5">
        <div class="row justify-content-center m-5 align-items-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Verify OTP</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('fail'))
                            <div class="alert alert-danger">{{ session('fail') }}</div>
                        @endif
                        <form action="{{ route('verify_code') }}" method="POST" id="otpForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            <div class="mb-3">
                                <label for="code" class="form-label">Enter 6-Digit Code</label>
                                <input type="text" class="form-control" id="code" name="code" maxlength="6"
                                    pattern="[0-9]{6}" required title="Enter exactly 6 digits">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required
                                    minlength="1"
                                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                                    title="Password must be at least 8 characters with uppercase, lowercase, number, and special character">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Reset Password</button>
                        </form>
                        <div class="text-center mt-3">
                            <button class="btn btn-link" onclick="resendOTP()">Resend OTP</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match.');
        }
    });

    function resendOTP() {
        // Implement resend logic, e.g., AJAX call to backend
        alert('OTP resent to your email.');
    }
</script>
