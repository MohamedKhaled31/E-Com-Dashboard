<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPurchaseInvoiceRequest;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;

class PurchaseInvoiceController extends Controller
{
    public function index(){
        $purchase_invoices = PurchaseInvoice::withCount('lines')->get();
        return view('purchase-invoices.index', compact('purchase_invoices'));
    }

    public function show(PurchaseInvoice $purchase_invoice){
        return view('purchase-invoices.show', compact('purchase_invoice'));
    }

    public function create(){
        $suppliers = Supplier::all();
        return view('purchase-invoices.create', compact('suppliers'));
    }

    public function store(AddPurchaseInvoiceRequest $request){
        $validated = $request->validated();

        $purchase_invoice = PurchaseInvoice::create([
            'supplier_id' => $validated['supplier_id'],
            'user_id' => auth()->id(),
            'subtotal' => 0,
            'vat' => 0,
            'total' => 0,
            'is_draft' => true,
        ]);

        return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('success', 'Invoice created successfully as draft.');
    }

    public function destroy(PurchaseInvoice $purchase_invoice){
        if(!$purchase_invoice->is_draft){
            return redirect()->route('purchase-invoices.index')->with('error', 'You cannot delete a non-draft invoice.');
        }
        $purchase_invoice->delete();
        return redirect()->route('purchase-invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function save(PurchaseInvoice $purchase_invoice){
        if($purchase_invoice->lines->count() == 0){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot save an invoice without lines.');
        }

        $purchase_invoice->update([
            'is_draft' => false,
        ]);

        foreach ($purchase_invoice->lines as $line){
            $line->product->update([
                'quantity' => $line->product->quantity + $line->quantity,
            ]);
        }

        return redirect()->route('purchase-invoices.index')->with('success', 'Invoice completed and stock updated.');
    }
}
