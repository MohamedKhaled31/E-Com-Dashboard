<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSaleInvoicesLineRequest;
use App\Models\Product;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceLine;

class SaleInvoiceLineController extends Controller
{
    public function create(SaleInvoice $sale_invoice)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot add lines to a non-draft invoice.');
        }
        $sale_invoice_products = $sale_invoice->lines->pluck('product_id');
        $products = Product::whereNotIn('id', $sale_invoice_products)->get();
        return view('sale-invoices.lines.create', compact('products', 'sale_invoice'));
    }

    public function store(SaleInvoice $sale_invoice, AddSaleInvoicesLineRequest $request)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot add lines to a non-draft invoice.');
        }

        $validated = $request->validated();

        $product = Product::findOrFail($validated['product_id']);

        if ($product->quantity < $validated['quantity']) {
            return redirect()->back()->withInput()->withErrors([
                'quantity' => 'The quantity exceeds the available stock. Only ' . $product->quantity . ' items left.'
            ]);
        }

        $total = $product->price * $validated['quantity'];

        SaleInvoiceLine::create([
            'product_id' => $validated['product_id'],
            'sale_invoice_id' => $sale_invoice->id,
            'price' => $product->price,
            'quantity' => $validated['quantity'],
            'total' => $total,
        ]);

        $this->update_invoice_totals($sale_invoice);

        return redirect()->route('sale-invoices.show', $sale_invoice)->with('success', 'Item added to invoice.');
    }

    public function edit(SaleInvoice $sale_invoice, SaleInvoiceLine $line)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot edit lines of a non-draft invoice.');
        }
        return view('sale-invoices.lines.edit', compact('line', 'sale_invoice'));
    }

    public function update(SaleInvoice $sale_invoice, SaleInvoiceLine $line, AddSaleInvoicesLineRequest $request)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot update lines of a non-draft invoice.');
        }

        $validated = $request->validated();

        $product = $line->product;

        if ($product->quantity < $validated['quantity']) {
            return redirect()->back()->withInput()->withErrors([
                'quantity' => 'The requested quantity exceeds the available stock. Only ' . $product->quantity . ' items left.'
            ]);
        }

        $total = $line->price * $validated['quantity'];

        $line->update([
            'quantity' => $validated['quantity'],
            'total' => $total,
        ]);

        $this->update_invoice_totals($sale_invoice);

        return redirect()->route('sale-invoices.show', $sale_invoice)->with('success', 'Item updated successfully.');
    }

    public function destroy(SaleInvoice $sale_invoice, SaleInvoiceLine $line)
    {
        if (!$sale_invoice->is_draft) {
            return redirect()->route('sale-invoices.show', $sale_invoice)->with('error', 'You cannot delete lines from a non-draft invoice.');
        }

        $line->delete();

        $this->update_invoice_totals($sale_invoice);

        return redirect()->back()->with('success', 'Item removed from invoice successfully.');
    }

    protected function update_invoice_totals($sale_invoice)
    {
        $invoice_subtotal = $sale_invoice->lines->sum('total');
        $invoice_vat = $invoice_subtotal * 0.14;
        $invoice_total = $invoice_subtotal + $invoice_vat;

        $sale_invoice->update([
            'subtotal' => $invoice_subtotal,
            'vat' => $invoice_vat,
            'total' => $invoice_total,
        ]);
    }
}
