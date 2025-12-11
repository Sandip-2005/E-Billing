<?php

namespace App\Http\Controllers;

use App\Models\ShopModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;

class ShopController extends Controller
{
    public function user_shop_profile()
    {
        $shops = Auth::guard('cuser')->user()->shops;
        return view('user_layout.user_shop.user_shop_profile', compact('shops'));
    }
    public function add_user_shop_profile()
    {
        return view('user_layout.user_shop.add_user_shop_profile');
    }

    public function store_user_shop_profile(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:100',
            'shop_contact' => 'required|string|max:20|unique:shop_profile,shop_contact',
            'gst_number' => 'nullable|string|max:40|unique:shop_profile,gst_number',
            'shop_address' => 'required|string|max:255',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        $user = Auth::guard('cuser')->user();

        $shop_logo_path = null;
        if ($request->hasFile('shop_logo')) {
            $image = $request->file('shop_logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('shop_logos', $imageName, 'public');
            $shop_logo_path = 'storage/shop_logos/' . $imageName;
        }

        $shop = $user->shops()->create([
            'shop_name' => $request->shop_name,
            'shop_contact' => $request->shop_contact,
            'gst_number' => $request->gst_number,
            'shop_address' => $request->shop_address,
            'shop_logo' => $shop_logo_path,
        ]);

        if ($shop) {
        return redirect()->route('user_shop_profile')->with('success', 'Shop profile created successfully!');
        }
        else{
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }

    public function edit_user_shop_profile($id)
    {
        $shop = ShopModel::findOrFail($id);
        return view('user_layout.user_shop.edit_user_shop_profile', compact('shop'));
    }

    public function update_user_shop_profile(Request $request, $id)
    {
        $shop = ShopModel::findOrFail($id);

        $request->validate([
            'shop_name' => 'required|string|max:100',
            'shop_contact' => 'required|string|max:20|unique:shop_profile,shop_contact,' . $shop->id,
            'gst_number' => 'nullable|string|max:40|unique:shop_profile,gst_number,' . $shop->id,
            'shop_address' => 'required|string|max:255',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($request->hasFile('shop_logo')) {
            $image = $request->file('shop_logo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('shop_logos', $imageName, 'public');
            $shop->shop_logo = 'storage/shop_logos/' . $imageName;
        }

        $shop->shop_name = $request->shop_name;
        $shop->shop_contact = $request->shop_contact;
        $shop->gst_number = $request->gst_number;
        $shop->shop_address = $request->shop_address;
        $updated = $shop->save();

        if ($updated) {
            return redirect()->route('user_shop_profile')->with('success', 'Shop profile updated successfully!');
        } else {
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }
}
