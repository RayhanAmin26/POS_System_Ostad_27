<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(){
        return response()->json(Invoice::with(['customer','products'])->get());
    }

    public function store(Request $request){
        $request->validate([
            'customer_id'=>'required|exists:customers,id',
            'products'=>'required|array',
            'products.*.id'=>'required|exists:products,id',
            'products.*.quantity'=>'required|integer|min:1',
        ]);

        $invoice = Invoice::create([
            'customer_id'=>$request->customer_id,
            'total'=>0
        ]);

        $total = 0;
        foreach($request->products as $p){
            $product = Product::find($p['id']);
            $invoice->products()->attach($product->id, ['quantity'=>$p['quantity'], 'price'=>$product->price]);
            $total += $product->price * $p['quantity'];
        }

        $invoice->total = $total;
        $invoice->save();

        return response()->json($invoice->load(['customer','products']),201);
    }

    public function show($id){
        return response()->json(Invoice::with(['customer','products'])->findOrFail($id));
    }

    public function destroy($id){
        $invoice = Invoice::findOrFail($id);
        $invoice->products()->detach();
        $invoice->delete();
        return response()->json(['message'=>'Invoice deleted']);
    }

    // Optional PDF Generation
    public function generatePDF($id){
        $invoice = Invoice::with(['customer','products'])->findOrFail($id);
        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));
        return $pdf->download('invoice_'.$id.'.pdf');
    }
}
