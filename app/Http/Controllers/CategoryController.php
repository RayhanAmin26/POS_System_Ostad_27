<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        return response()->json(Category::all());
    }

    public function store(Request $request){
        $request->validate(['name'=>'required|unique:categories']);
        $category = Category::create(['name'=>$request->name]);
        return response()->json($category,201);
    }

    public function show($id){
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request,$id){
        $category = Category::findOrFail($id);
        $request->validate(['name'=>'required|unique:categories,name,'.$id]);
        $category->name = $request->name;
        $category->save();
        return response()->json($category);
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message'=>'Category deleted']);
    }
}
