<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductController extends Controller
{
    public function index(){
        return response()->json(Product::with(['category','brand'])->get());
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'category_id'=>'required|exists:categories,id',
            'brand_id'=>'required|exists:brands,id',
            'price'=>'required|numeric',
            'stock'=>'required|integer',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $product->image = $filename;
        }

        $product->save();
        return response()->json($product,201);
    }

    public function show($id){
        return response()->json(Product::with(['category','brand'])->findOrFail($id));
    }

    public function update(Request $request, $id){
        $product = Product::findOrFail($id);
        $request->validate([
            'name'=>'required',
            'category_id'=>'required|exists:categories,id',
            'brand_id'=>'required|exists:brands,id',
            'price'=>'required|numeric',
            'stock'=>'required|integer',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->stock = $request->stock;

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename);
            $product->image = $filename;
        }

        $product->save();
        return response()->json($product);
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message'=>'Product deleted']);
    }
}
