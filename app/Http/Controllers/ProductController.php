<?php

namespace App\Http\Controllers;
use App\Models\ProductModel;
use App\Models\ProductBatch;
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
        $product = ProductModel::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->shop_id,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        // Create initial batch for the new product
        ProductBatch::create([
            'product_id' => $product->id,
            'batch_no' => 'B-' . date('dmy-Hi') . '-' . strtoupper(substr(uniqid(), -6)),
            'quantity' => $request->quantity,
            'selling_price' => $request->price,
            'expiry_date' => $request->expiry_date?? null,
            'purchase_price' => $request->purchase_price ?? 0.0,
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

        // Sync price to batches so invoices use the new price
        ProductBatch::where('product_id', $id)->update(['selling_price' => $request->price]);

        return redirect()->route('manage_products')->with('success', 'Product updated successfully!');
    }

    public function delete_products($id)
    {
        $product = ProductModel::where('user_id', auth()->id())->findOrFail($id);
        $product->delete();

        return redirect()->route('manage_products')->with('success', 'Product deleted successfully!');
    }

    public function stock_alert(Request $request)
    {
        $shops = ShopModel::where('user_id', auth()->id())->get();
        $shop_id = $request->get('shop_id');
        $products = ProductModel::with('shop')
            ->where('user_id', auth()->id())
            ->where('quantity', '<=', 10) // Assuming stock alert threshold is 10
            ->when($shop_id, function($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->get();

        return view('user_layout.user_inventory.stock_alert', compact('products', 'shops', 'shop_id'));
    }
}
