<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPurchaseInvoiceLineRequest;
use App\Http\Requests\EditPurchaseInvoiceLineRequest;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceLine;

class PurchaseInvoiceLineController extends Controller
{
    public function create(PurchaseInvoice $purchase_invoice){
        if(!$purchase_invoice->is_draft){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot add lines to a non-draft invoice');
        }
        $purchase_invoice_products = $purchase_invoice->lines->pluck('product_id');
        $products = Product::whereNotIn('id', $purchase_invoice_products)->get() ;
        return view('purchase-invoices.lines.create', compact('products', 'purchase_invoice'));
    }

    public function store(PurchaseInvoice $purchaseInvoice, AddPurchaseInvoiceLineRequest $request){
        if(!$purchaseInvoice->is_draft){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot add lines to a non-draft invoice');
        }
        $validated = $request->validated();
        $total = $validated['price'] * $validated['quantity'];

        PurchaseInvoiceLine::create([
            'product_id' => $validated['product_id'],
            'purchase_invoice_id' => $purchaseInvoice->id,
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'total' => $total,
        ]);

        $this->update_invoice_totals($purchaseInvoice);

        return redirect()->route('purchase-invoices.show', $purchaseInvoice);
    }

    public function edit(PurchaseInvoice $purchase_invoice, PurchaseInvoiceLine $line){
        if(!$purchase_invoice->is_draft){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot add lines to a non-draft invoice');
        }
        return view('purchase-invoices.lines.edit', compact('line', 'purchase_invoice'));
    }

    public function update(PurchaseInvoice $purchaseInvoice, PurchaseInvoiceLine $line, EditPurchaseInvoiceLineRequest $request){
        if(!$purchaseInvoice->is_draft){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot add lines to a non-draft invoice');
        }
        $validated = $request->validated();
        $total = $validated['price'] * $validated['quantity'];
        $line->update([
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'total' => $total,
        ]);

        $this->update_invoice_totals($purchaseInvoice);

        return redirect()->route('purchase-invoices.show', $purchaseInvoice);
    }

    public function destroy(PurchaseInvoice $purchaseInvoice, PurchaseInvoiceLine $line){
        if(!$purchaseInvoice->is_draft){
            return redirect()->route('purchase-invoices.show', $purchase_invoice)->with('error', 'You cannot add lines to a non-draft invoice');
        }
        $line->delete();

        $this->update_invoice_totals($purchaseInvoice);

        return redirect()->back();
    }

    protected function update_invoice_totals($purchaseInvoice)
    {
        $invoice_subtotal = $purchaseInvoice->lines->sum('total');
        $invoice_vat = $invoice_subtotal * 0.14;
        $invoice_total = $invoice_subtotal + $invoice_vat;

        $purchaseInvoice->update([
            'subtotal' => $invoice_subtotal,
            'vat' => $invoice_vat,
            'total' => $invoice_total,
        ]);
    }
}
