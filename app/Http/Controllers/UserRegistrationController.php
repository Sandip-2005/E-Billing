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
use Illuminate\Support\Facades\Cookie;
use function PHPUnit\Framework\returnValueMap;


class UserRegistrationController extends Controller
{
    public function user_signup_form()
    {
        return view('login&signup.signup');
    }

    public function email_verification_form()
    {
        return view('login&signup.user_email_verification');
    }

    private function generateOtp(): string
    {
        return (string) rand(100000, 999999);
    }

    private function sendOtp(string $email, string $table, int $expiryMinutes = 30): string
    {
        $code = $this->generateOtp();

        DB::table($table)->updateOrInsert(
            ['email' => $email],
            [
                'code' => $code,
                'created_at' => Carbon::now()
            ]
        );

        if ($table === 'password_reset_codes') {
            $subject = 'Password Reset OTP';
        } else {
            $subject = 'Email Verification OTP';
        }
        Mail::raw(
            "Your OTP is: $code. It expires in {$expiryMinutes} minutes. \nIf you did not request this, please ignore this email. \n\nDo not share this OTP with anyone. E-Billing takes your account security very seriously. E-Billing Customer Service will never ask you to disclose or verify your E-Billing password, OTP, credit card, or banking account number. If you receive a suspicious email with a link to update your account information, do not click on the link—instead, report the email to E-Billing for investigation.
 \n\nThank you,\nE-Billing Team",
            function ($message) use ($email, $subject) {
                $message->to($email)->subject($subject);
            }
        );

        return $code;
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

        $user_image_path = 'user_images/user.png'; // default

        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // store in storage/app/public/user_images
            $image->storeAs('user_images', $imageName, 'public');

            // save ONLY relative path
            $user_image_path = 'user_images/' . $imageName;
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

        $code = $this->sendOtp($request->email, 'email_verification_codes', 30);

        if ($user) {
            return redirect('/verify_email')->with('success', 'User registered successfully.')->with('email', $request->email)->with('code', $code);
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }

    public function verify_email_code(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:user_signup,email',
            'code'  => 'required|string|size:6',
        ]);
        $record = DB::table('email_verification_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return back()->with('fail', 'Invalid verification code.');
        }
        // Check expiry (30 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('email_verification_codes')->where('email', $request->email)->delete();
            return back()->with('fail', 'Code expired. Please request a new one.');
        }
        UserModel::where('email', $request->email)->update([
            'is_verified' => true
        ]);
        DB::table('email_verification_codes')->where('email', $request->email)->delete();
        return redirect()->route('login')->with('success', 'Email verified successfully. Please login.');
    }

    private function setCustomRememberExpiry(int $days)
    {
        /** @var \Illuminate\Auth\SessionGuard $guard */
        $guard = Auth::guard('cuser');
        $cookieName = $guard->getRecallerName(); // remember_cuser_xxx

        // Check queued cookies because the remember token is set during this request
        $queuedCookies = Cookie::getQueuedCookies();
        foreach ($queuedCookies as $cookie) {
            if ($cookie->getName() === $cookieName) {
                Cookie::queue($cookieName, $cookie->getValue(), $days * 24 * 60);
                break;
            }
        }
    }

    public function user_login(Request $request)
    {
        $request->validate([
            'usernameoremail' => 'required|string|max:40',
            'password' => 'required|string|max:100'
        ]);

        $loginField = filter_var($request->usernameoremail, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->usernameoremail,
            'password' => $request->password,
        ];
        $remember = $request->boolean('rememberMe');

        if (Auth::guard('cuser')->attempt($credentials, $remember)) {

            $user = Auth::guard('cuser')->user();

            if (!$user->is_verified) {

                $email = $user->email;

                $this->sendOtp($email, 'email_verification_codes', 30);

                Auth::guard('cuser')->logout();

                return redirect()->route('email_verification_form')->with([
                    'email' => $email,
                    'otp_expires_at' => now()->addMinutes(30)->timestamp,
                    'fail' => 'Please verify your email before logging in.'
                ]);
            }

            $request->session()->regenerate();

            if ($remember) {
                $this->setCustomRememberExpiry(1); // 🔁 30 DAYS
            }

            return redirect()->intended(route('user_dashboard'))
                ->with('success', 'Login successful!');
        }

        return back()
            ->withErrors(['usernameoremail' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('usernameoremail'));
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
            'phone' => 'string|max:10',
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
            $user_image_path = 'user_images/' . $imageName;
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

        $this->sendOtp($request->email, 'password_reset_codes', 30);

        $request->session()->put('reset_email', $request->email);

        if (Auth::guard('cuser')->check()) {
            return redirect()->back()->with(['email' => $request->email, 'success' => 'Verification code sent to ' . $request->email, 'otp_sent' => true]);
        }

        return redirect()->route('verify_otp_form')->with(['email' => $request->email, 'success' => 'Verification code sent to .' . $request->email]);
    }

    public function verify_otp_form()
    {
        return view('login&signup.verify_otp');
    }

    public function verify_Code(Request $request)
    {
        if (!$request->filled('email') && $request->session()->has('reset_email')) {
            $request->merge(['email' => $request->session()->get('reset_email')]);
        }

        $request->validate([
            'email' => 'required|email|exists:user_signup,email',
            'code'  => 'required|string|size:6',
            'password' => 'required|string|confirmed'
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
        $request->session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password reset successful. Please login with your new password.');
    }

    public function update_user_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string',
            'password_confirmation' => 'required|string|same:password'
        ]);

        $user = Auth::guard('cuser')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('fail', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

     public function verify_Code2(Request $request)
    {
        if (!$request->filled('email') && $request->session()->has('reset_email')) {
            $request->merge(['email' => $request->session()->get('reset_email')]);
        }

        $request->validate([
            'email' => 'required|email|exists:user_signup,email',
            'code'  => 'required|string|size:6',
            'password' => 'required|string|confirmed'
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
        $request->session()->forget('reset_email');

        return redirect()->route('user_profile')->with('success', 'Password reset successful. Please login with your new password.');
    }

}