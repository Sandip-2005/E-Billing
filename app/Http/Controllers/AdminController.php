<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\AdminModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
   public function admin_create()
{
    // use http://127.0.0.1:8000/create-admin to store admin data in database
    $admin = new AdminModel();
    $admin->name = 'Sandipan Bhunia';
    $admin->email = 'sandipanbhunia18@gmail.com';
    $admin->password = bcrypt('2005'); // hash the password
    $admin->save();

    return redirect()->route('admin_index')->with('success', 'Admin created successfully!');

    // $admin = ['name'=>$request->name];
}
    public function admin_login(Request $request){
        $request->validate([
            'usernameoremail' => 'required|string',
            'password' => 'required|string',
        ]);
        $loginfield = filter_var($request->usernameoremail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [
            $loginfield => $request->usernameoremail,
            'password' => $request->password,
        ];
        if (Auth::guard('cadmin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin_dashboard'))
                ->with('success', 'Login successful!');
        }
        return back()->with('fail', 'The provided credentials do not match our records.')
            ->onlyInput('usernameoremail');

    }

    public function admin_dashboard(){
        return view('admin_layout.admin_dashboard');
    }

    public function admin_logout(Request $request){
        Auth::guard('cadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect()->route('admin.login.form');
    }

    public function manage_users(){
        $users = UserModel::all();
        return view('admin_layout.admin_users.manage_users', compact('users'));
    }

    public function edit_user(Request $request, $id){
        $user = UserModel::findOrFail($id);
        $admin = Auth::guard('cadmin')->user();
        return view('admin_layout.admin_users.edit_users',compact('user', 'admin'));
    }

    public function admin_update_user(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);

        $request->validate([
            'name' => 'string|max:40',
            'username' => 'string|max:40|unique:user_signup,username,' . $user->id,
            'email' => 'string|email|max:40|unique:user_signup,email,' . $user->id,
            'phone' => 'string|max:30',
            'gstin' => 'nullable|string|max:40',
            'user_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'address' => 'string|max:30',
            'ps' => 'string|max:30',
            'district' => 'string|max:30',
            'state' => 'string|max:30',
            'pincode' => 'string|max:20',
        ]);

        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('user_images', $imageName, 'public');
            // Update user_image path only if a new image is uploaded
            $user->user_image = 'storage/user_images/' . $imageName;
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

        $user->save();

        if ($user) {
            return redirect()->route('manage_users')->with('success', 'User profile updated successfully.');
        } else {
            return redirect()->route('manage_users')->with('fail', 'Something went wrong, try again later.');
        }
    }

    public function delete_user($id){
        $user= UserModel::findorfail($id);
        $user->delete();
        return redirect()->route('manage_users')->with('success', 'User deleted successfully.');
    }

    public function add_user(){
        return view('admin_layout.admin_users.add_user');
    }

    public function admin_store_user(Request $request){

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

        $user_image_path = null;
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
            return redirect('/manage_users')->with('success', 'User registered successfully.');
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }

}
