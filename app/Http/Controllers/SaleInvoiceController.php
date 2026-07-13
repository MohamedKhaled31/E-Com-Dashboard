<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSaleInvoicesRequest;
use App\Models\SaleInvoice;

class SaleInvoiceController extends Controller
{
    public function index()
    {
        $sale_invoices = SaleInvoice::with(['user'])->withCount('lines')->latest()->get();
        return view('sale-invoices.index', compact('sale_invoices'));
    }

    public function show(SaleInvoice $sale_invoice)
    {
        return view('sale-invoices.show', compact('sale_invoice'));
    }

    public function create()
    {
        return view('sale-invoices.create');
    }

    public function store(AddSaleInvoicesRequest $request)
    {
        $validated = $request->validated();

        $sale_invoice = SaleInvoice::create([
            'customer_name' => $validated['customer_name'],
            'user_id' => auth()->id(),
            'subtotal' => 0,
            'vat' => 0,
            'total' => 0,
            'is_draft' => true,
        ]);

        return redirect()->route('sale-invoices.show', $sale_invoice)->with('success', 'Invoice created successfully.');
    }

    public function destroy(SaleInvoice $sale_invoice)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.index')->with('error', 'You cannot delete a non-draft invoice.');
        }
        $sale_invoice->delete();
        return redirect()->route('sale-invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function save(SaleInvoice $sale_invoice)
    {
        if ($sale_invoice->lines->count() == 0) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot save an invoice without lines.');
        }

        foreach ($sale_invoice->lines as $line) {
            if ($line->product->quantity < $line->quantity) {
                return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'Insufficient stock for product: ' . $line->product->name);
            }
        }

        $sale_invoice->update([
            'is_draft' => false,
        ]);

        foreach ($sale_invoice->lines as $line) {
            $line->product->update([
                'quantity' => $line->product->quantity - $line->quantity,
            ]);
        }

        return redirect()->route('sale-invoices.index')->with('success', 'Invoice saved and stock updated.');
    }
}
