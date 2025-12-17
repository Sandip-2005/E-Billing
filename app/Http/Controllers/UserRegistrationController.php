<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class UserRegistrationController extends Controller
{
    public function user_signup_form()
    {
        return view('login&signup.signup');
    }

    public function user_signup(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:40',
            'username' => 'required|string|max:40|unique:user_signup,username',
            'email' => 'required|string|email|max:40|unique:user_signup,email',
            'phone' => 'required|string|max:30',
            'pancard' => ['required', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'gstin' => 'nullable|string|max:40',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'address' => 'required|string|max:30',
            'ps' => 'required|string|max:30',
            'district' => 'required|string|max:30',
            'state' => 'required|string|max:30',
            'pincode' => 'required|string|max:20',
            'password' => 'required|string|max:100',
            'confirm_password' => 'required|string|same:password|max:100'
        ]);

        $user_image_path = 'default-avatar.png'; // Set a default image path
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('user_images', $imageName, 'public');
            $user_image_path = 'storage/user_images/' . $imageName;
        }

        $user = UserModel::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'pancard'  => $request->pancard,
            'gstin'    => $request->gstin,
            'user_image' => $user_image_path,
            'address'  => $request->address,
            'ps'       => $request->ps,
            'district' => $request->district,
            'state'    => $request->state,
            'pincode'  => $request->pincode,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return redirect('/login')->with('success', 'User registered successfully.');
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }


    public function user_login(Request $request)
    {
        $request->validate([
            'usernameoremail' => 'required|string|max:40',
            'password' => 'required|string|max:100'
        ]);

        $loginField = filter_var($request->usernameoremail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->usernameoremail,
            'password' => $request->password
        ];

        if (Auth::guard('cuser')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('user_dashboard'))
                ->with('success', 'Login successful!');
        }

        return back()->withErrors(['usernameoremail' => 'The provided credentials do not match our records.'])
            ->onlyInput('usernameoremail');
    }

    public function user_logout(Request $request)
    {
        Auth::guard('cuser')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function get_user_data()
    {
        if (Auth::guard('cuser')->check()) {
            return response()->json(Auth::guard('cuser')->user());
        }

        return response()->json(['error' => 'Not authenticated'], 401);
    }

    public function user_profile(Request $requset)
    {
        $user = $requset->user();
        return view('user_layout.user_profile', compact('user'));
    }

    public function update_user_profile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'string|max:40',
            'username' => 'string|max:40|unique:user_signup,username,' . $user->id,
            'email' => 'string|email|max:40|unique:user_signup,email,' . $user->id,
            'phone' => 'string|max:30',
            'pancard' => ['required', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'gstin' => 'string|max:40',
            'user_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'address' => 'string|max:30',
            'ps' => 'string|max:30',
            'district' => 'string|max:30',
            'state' => 'string|max:30',
            'pincode' => 'string|max:20',
        ]);
        $user_image_path = $user->user_image;
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('user_images', $imageName, 'public');
            $user_image_path = 'storage/user_images/' . $imageName;
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('username')) {
            $user->username = $request->username;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->filled('pancard')) {
            $user->pancard = $request->pancard;
        }
        if ($request->filled('gstin')) {
            $user->gstin = $request->gstin;
        }
        if ($request->filled('address')) {
            $user->address = $request->address;
        }
        if ($request->filled('ps')) {
            $user->ps = $request->ps;
        }
        if ($request->filled('district')) {
            $user->district = $request->district;
        }
        if ($request->filled('state')) {
            $user->state = $request->state;
        }
        if ($request->filled('pincode')) {
            $user->pincode = $request->pincode;
        }
        $user->user_image = $user_image_path;

        $user->save();

        if ($user) {
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }

    public function forget_password_form()
    {
        return view('login&signup.forgot_password');
    }

    public function send_code(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_signup,email'
        ]);

        $code = rand(100000, 999999);
        $user = UserModel::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('fail', 'Email not found.');
        }

        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $code,
                'created_at' => Carbon::now()
            ]
        );

        try {
            Mail::raw("Your password reset OTP is: $code. It expires in 10 minutes.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset Code');
            });
            // Log success for debugging
            \Log::info("OTP sent to {$request->email}: $code");
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP to {$request->email}: " . $e->getMessage());
            return back()->with('fail', 'Failed to send email. Please check your mail configuration or try again later.');
        }

        return redirect()->route('verify_otp_form')->with(['email' => $request->email, 'success' => 'Verification code sent to your email.']);
    }

    public function verify_otp_form()
    {
        return view('login&signup.verify_otp');
    }

    public function verify_Code(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_signup,email',
            'code'  => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return back()->with('fail', 'Invalid verification code.');
        }

        // Check expiry (10 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            DB::table('password_reset_codes')->where('email', $request->email)->delete();
            return back()->with('fail', 'Code expired. Please request a new one.');
        }

        UserModel::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successful. Please login with your new password.');
    }
}
