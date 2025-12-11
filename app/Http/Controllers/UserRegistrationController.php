<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        return back()->with('fail', 'The provided credentials do not match our records.')
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
}
