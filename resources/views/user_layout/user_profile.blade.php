@extends('user_layout.user_index')
@section('content')


    <div>
        <div class="mb-4">
            <h2 class="fw-bold">User Profile</h2>
            <p class="text-muted">Manage your personal information and settings</p>
        </div>
        <div class="text-center mb-4">
            {{-- <img src="{{ asset(Auth::guard('cuser')->user()->user_image) }}" alt="User Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;"> --}}
        @php
            $user = Auth::guard('cuser')->user();
            $image = $user->user_image ? asset($user->user_image) : asset('storage/user_images/user.png');
        @endphp

            <div class="text-center">
                <input type="file" id="user_image" name="user_image" accept="image/*" style="display: none;"
                    onchange="previewImage(event)">

                <label for="user_image" class="profile-img-wrapper">
                    <img id="profilePreview" src="{{ $image }}" alt="User Profile Picture"
                        class="rounded-circle shadow profile-img">

                    <span class="profile-text">Tap to update</span>
                </label>
            </div>

            <h4 class="mt-3">{{ ucwords(Auth::guard('cuser')->user()->name) }}</h4>
        </div>
        <form action="{{ route('update_user_profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($errors->any())
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
                        <input type="text" class="form-control" id="fullName" name="name"
                            value="{{ $user->name }}">
                        <label for="fullName"><i class="bi bi-person-badge me-2"></i>Full Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ $user->username }}">
                        <label for="username"><i class="bi bi-at me-2"></i>Username</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $user->email }}">
                        <label for="email"><i class="bi bi-envelope-fill me-2"></i>Email Address</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">+91</span>
                        <div class="form-floating flex-grow-1">
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="{{ $user->phone }}">
                            <label for="phone">Phone Number</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="pancard" name="pancard"
                            value="{{ $user->pancard }}">
                        <label for="pancard"><i class="bi bi-card-text me-2"></i>PAN Card No.</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="gstin" name="gstin"
                            value="{{ $user->gstin }}">
                        <label for="gstin"><i class="bi bi-building me-2"></i>GSTIN (if applicable)</label>
                    </div>
                </div>

                <!-- <div class="col-md-6">
                    <div class="form-floating">
                        <input type="file" class="form-control" id="user_image" name="user_image">
                        <label for="user_image"><i class="bi bi-building me-2"></i>Image</label>
                    </div>
                </div> -->
                <div class="col-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="address" name="address"
                            value="{{ $user->address }}">
                        <label for="address"><i class="bi bi-house-door-fill me-2"></i>Village / City</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="ps" name="ps"
                            value="{{ $user->ps }}">
                        <label for="ps"><i class="bi bi-geo-alt-fill me-2"></i>Police Station (P.S)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="district" name="district"
                            value="{{ $user->district }}">
                        <label for="district"><i class="bi bi-geo-alt-fill me-2"></i>District</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="state" name="state">
                            <option selected disabled value="{{ $user->state }}">{{ $user->state }}</option>
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
                            <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and
                                Diu</option>
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
                        <input type="text" class="form-control" id="pincode" name="pincode"
                            value="{{ $user->pincode }}">
                        <label for="pincode"><i class="bi bi-pin-map-fill me-2"></i>Pincode</label>
                    </div>
                </div>

                <!-- <div class="col-md-6">
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
                </div> -->
            </div>

            <div class="form-check my-4">
                <input class="form-check-input" type="checkbox" value="" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#">Terms and Conditions</a>
                </label>
            </div>

            <div class="d-grid d-flex gap-2 justify-content-center">
                <button class="btn btn-primary btn-register btn-sm" type="submit"
                    onclick="return confirm('Are you sure you want to update your profile?')">Update Profile</button>
                <button class="btn btn-danger btn-sm"><a href="{{ route('user_profile') }}" class="text-white"
                        style="text-decoration: none">Cancel</a></button>
            </div>

        </form>

    </div>

@endsection
