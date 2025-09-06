<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(){
        return response()->json(Customer::all());
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'nullable|email|unique:customers',
            'phone'=>'nullable|unique:customers',
        ]);

        $customer = Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'address'=>$request->address,
        ]);

        return response()->json($customer,201);
    }

    public function show($id){
        return response()->json(Customer::findOrFail($id));
    }

    public function update(Request $request,$id){
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'=>'required',
            'email'=>'nullable|email|unique:customers,email,'.$id,
            'phone'=>'nullable|unique:customers,phone,'.$id,
        ]);

        $customer->update($request->only(['name','email','phone','address']));
        return response()->json($customer);
    }

    public function destroy($id){
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json(['message'=>'Customer deleted']);
    }
}
