@extends('layout.index')

@section('content')
<div class="signup-container">
    <div class="card signup-box">

        <div class="signup-header text-center mb-4">
            <i class="bi bi-person-plus-fill icon"></i>
            <h2 class="mt-2">Create Your Account</h2>
            <p class="text-muted">Join E-Billing and simplify your invoicing today.</p>
        </div>

        <form action="{{route('user_signup')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    <div class="row g-3">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="fullName" name="name" placeholder="Full Name">
                <label for="fullName"><i class="bi bi-person-badge me-2"></i>Full Name</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                <label for="username"><i class="bi bi-at me-2"></i>Username</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                <label for="email"><i class="bi bi-envelope-fill me-2"></i>Email Address</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">+91</span>
                <div class="form-floating flex-grow-1">
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                    <label for="phone">Phone Number</label>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="pancard" name="pancard" placeholder="PAN Card No.">
                <label for="pancard"><i class="bi bi-card-text me-2"></i>PAN Card No.</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="gstin" name="gstin" placeholder="GSTIN">
                <label for="gstin"><i class="bi bi-building me-2"></i>GSTIN (if applicable)</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <input type="file" class="form-control" id="user_image" name="user_image">
                <label for="user_image"><i class="bi bi-building me-2"></i>Image</label>
            </div>
        </div>
        <div class="col-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="address" name="address" placeholder="Village/City">
                <label for="address"><i class="bi bi-house-door-fill me-2"></i>Village / City</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="ps" name="ps" placeholder="Police Station">
                <label for="ps"><i class="bi bi-geo-alt-fill me-2"></i>Police Station (P.S)</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="district" name="district" placeholder="District">
                <label for="district"><i class="bi bi-geo-alt-fill me-2"></i>District</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <select class="form-select" id="state" name="state">
                    <option selected disabled value="">Choose...</option>
                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                    <option value="Assam">Assam</option>
                    <option value="Bihar">Bihar</option>
                    <option value="Chhattisgarh">Chhattisgarh</option>
                    <option value="Goa">Goa</option>
                    <option value="Gujarat">Gujarat</option>
                    <option value="Haryana">Haryana</option>
                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                    <option value="Jharkhand">Jharkhand</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Kerala">Kerala</option>
                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Manipur">Manipur</option>
                    <option value="Meghalaya">Meghalaya</option>
                    <option value="Mizoram">Mizoram</option>
                    <option value="Nagaland">Nagaland</option>
                    <option value="Odisha">Odisha</option>
                    <option value="Punjab">Punjab</option>
                    <option value="Rajasthan">Rajasthan</option>
                    <option value="Sikkim">Sikkim</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Telangana">Telangana</option>
                    <option value="Tripura">Tripura</option>
                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                    <option value="Uttarakhand">Uttarakhand</option>
                    <option value="West Bengal">West Bengal</option>
                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                    <option value="Chandigarh">Chandigarh</option>
                    <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                    <option value="Ladakh">Ladakh</option>
                    <option value="Lakshadweep">Lakshadweep</option>
                    <option value="Puducherry">Puducherry</option>
                </select>
                <label for="state"><i class="bi bi-map-fill me-2"></i>State</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode">
                <label for="pincode"><i class="bi bi-pin-map-fill me-2"></i>Pincode</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password"><i class="bi bi-lock-fill me-2"></i>Password</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm Password">
                <label for="confirmPassword"><i class="bi bi-check-circle-fill me-2"></i>Confirm Password</label>
            </div>
        </div>
    </div>

    <div class="form-check my-4">
        <input class="form-check-input" type="checkbox" value="" id="terms" required>
        <label class="form-check-label" for="terms">
            I agree to the <a href="#">Terms and Conditions</a>
        </label>
    </div>

    <div class="d-grid">
        <button class="btn btn-primary btn-register" type="submit">Register</button>
    </div>

    </form>

    <div class="text-center mt-4 login-link">
        <p class="text-muted">Already have an account? <a href="{{ route('user_login') }}">Login here</a></p>
    </div>

</div>
</div>
@endsection