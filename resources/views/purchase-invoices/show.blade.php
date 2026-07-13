@extends('layouts.app')

@section('title', 'Add New Purchase Invoice')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Purchase Invoice</h1>
    <p class="mb-4">Your Purchase Invoice Details.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary mr-2">Purchase Invoice Details</h6>
                @if($purchase_invoice->is_draft)
                    <div class="badge badge-secondary">Draft</div>
                @else
                    <div class="badge badge-success">Saved</div>
                @endif
            </div>
            @if($purchase_invoice->is_draft && $purchase_invoice->lines->count() > 0)
                <form
                    action="{{ route('purchase-invoices.save', $purchase_invoice->id) }}"
                    method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </form>
            @endif
        </div>
        <div class="card-body">
            Supplier: {{ $purchase_invoice->supplier->name }} | Subtotal: {{ $purchase_invoice->subtotal }}$ |
            Vat: {{ $purchase_invoice->vat }}$ | Total: {{ $purchase_invoice->total }}$
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Invoice Lines</h6>
            @if($purchase_invoice->is_draft)
                <a href="{{ route('purchase-invoices.lines.create', $purchase_invoice->id) }}"
                   class="btn btn-primary btn-sm float-right">Add New
                    Line</a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($purchase_invoice->lines as $line)
                        <tr>
                            <td>
                                {{ $line->product->name }}
                            </td>
                            <td>{{ $line->price }}$</td>
                            <td>{{ $line->quantity }}</td>
                            <td>{{ $line->total }}$</td>
                            <td class="text-right">
                                @if($purchase_invoice->is_draft)
                                    <a href="{{ route('purchase-invoices.lines.edit', ['purchase_invoice' => $purchase_invoice->id, 'line' => $line->id]) }}"
                                       class="btn btn-primary btn-sm">Edit</a>
                                    <form
                                        action="{{ route('purchase-invoices.lines.destroy', ['purchase_invoice' => $purchase_invoice->id, 'line' => $line->id]) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No Purchase Invoices Lines Founded.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
