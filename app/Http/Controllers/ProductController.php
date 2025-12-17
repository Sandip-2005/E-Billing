<?php

namespace App\Http\Controllers;
use App\Models\ProductModel;
use App\Models\ShopModel;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function add_products ()
    {
        $shops = ShopModel::where('user_id', auth()->id())->get();
        return view('user_layout.user_inventory.add_products', compact('shops'));
    }

    public function store_products (Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shop_profile,id',
            'product_id' => 'nullable|string',
            'product_name' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ], [
            'price.required' => 'Price is required.',
            'quantity.required' => 'Quantity is required.',
        ]);

        if (!$request->product_id && !$request->product_name) {
            return back()->withErrors(['product_id' => 'Either Product ID or Product Name must be provided.']);
        }

        // Store product in database
        ProductModel::create([
            'user_id' => auth()->id(), // Use the default guard which should reference the 'users' table
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('add_products')->with('success', 'Product added successfully!');
    }

    public function index()
    {
        $products = ProductModel::where('user_id', auth()->id())->get();
        return view('user_layout.user_inventory.manage_products', compact('products'));
    }

    public function manage_products (Request $request)
    {
        $shops = ShopModel::where('user_id', auth()->id())->get();
        $shop_id = $request->get('shop_id');
        $products = ProductModel::with('shop')
            ->where('user_id', auth()->id())
            ->when($shop_id, function($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->get();

        return view('user_layout.user_inventory.manage_products', compact('products', 'shops', 'shop_id'));
    }

    public function edit_products($id)
    {
        $product = ProductModel::where('user_id', auth()->id())->findOrFail($id);
        $shops = ShopModel::where('user_id', auth()->id())->get();
        return view('user_layout.user_inventory.edit_products', compact('product', 'shops'));
    }

    public function update_products(Request $request, $id)
    {
        $request->validate([
            'shop_id' => 'required|exists:shop_profile,id',
            'product_id' => 'nullable|string',
            'product_name' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = ProductModel::where('user_id', auth()->id())->findOrFail($id);

        $product->update([
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('manage_products')->with('success', 'Product updated successfully!');
    }
}
