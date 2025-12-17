<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddCustomer;

class AddCustomerController extends Controller
{
    public function add_customer_form()
    {
        return view('user_layout.user_customer.add_customer');
    }

    public function save_customer(Request $request){
        $request->validate([
            'customer_name' => 'required|string|max:40',
            'email' => 'nullable|string|email|max:40',
            'phone_number' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:100',
        ]);

        $customer= $request->user()->customers()->create([
            'customer_name'=>$request->customer_name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'address'=>$request->address
        ]);

        if($customer){
            return redirect('add_customer')->with('success', 'Customer added successfully.');
        }else{
            return redirect()->back()->with('fail', 'Something went wrong, try again later.');
        }
    }

    public function edit_customer(Request $request, $id){
        $customer = $request->user()->customers()->findOrFail($id);
        return view('user_layout.user_customer.edit_customer',compact('customer'));
    }

    public function update_customer(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:40',
            'email' => 'nullable|string|email|max:40',
            'phone_number' => 'required|string|max:30',
            'address' => 'required|string|max:100',
        ]);

        $customer = $request->user()->customers()->findOrFail($id);

        $updated = $customer->update([
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        if ($updated) {
            return redirect()->route('manage_customers')->with('success', 'Customer updated successfully.');
        }
        return back()->with('fail', 'Something went wrong, try again later.');
    }

    public function delete_customer(Request $request, $id){
        $customer= $request->user()->customers()->findOrFail($id);
        $customer->delete();
        if($customer){
            return redirect()->route('manage_customers')->with('success', 'Customer deleted successfully.');
        }else{
            return redirect()->route('manage_customers')->with('fail', 'Something went wrong, try again later.');
        }
    }
    // public function customer_count(){
    //     $totalcustomers = AddCustomer::count();
    //     return $totalcustomers;
    // }
}
