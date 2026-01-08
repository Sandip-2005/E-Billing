@extends('layout.index')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 shadow">
                <div class="card-header bg-info text-white text-center">
                    <h3>Email Verification</h3>
                </div>

                <div class="card-body">

                    {{-- Alerts --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
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

                    {{-- Countdown --}}
                    <div class="text-center mb-3">
                        <strong>OTP expires in:</strong>
                        <span id="timer" class="text-danger fw-bold"></span>
                    </div>

                    <form action="{{ route('verify_email_code') }}" method="POST" id="otpForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">

                        <div class="mb-3">
                            <label class="form-label">Enter 6-Digit Verification Code</label>
                            <input type="text"
                                   class="form-control text-center"
                                   name="code"
                                   maxlength="6"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-info w-100" id="verifyBtn">
                            Verify Email
                        </button>
                    </form>

                    {{-- Expired message --}}
                    <div id="expiredMsg" class="alert alert-danger text-center mt-3 d-none">
                        OTP expired. Please request a new one.
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- TIMER SCRIPT --}}
<script>
    let expiryTime = {{ session('otp_expires_at') ?? 0 }};
    let timerEl = document.getElementById('timer');
    let verifyBtn = document.getElementById('verifyBtn');
    let expiredMsg = document.getElementById('expiredMsg');

    function startTimer() {
        let interval = setInterval(() => {
            let now = Math.floor(Date.now() / 1000);
            let remaining = expiryTime - now;

            if (remaining <= 0) {
                clearInterval(interval);
                timerEl.innerText = "00:00";
                verifyBtn.disabled = true;
                expiredMsg.classList.remove('d-none');
                return;
            }

            let minutes = Math.floor(remaining / 60);
            let seconds = remaining % 60;

            timerEl.innerText =
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);
    }

    if (expiryTime > 0) {
        startTimer();
    }
</script>
@endsection
