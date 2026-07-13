@extends('layouts.app')

@section('title', 'Sale Invoice Details')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Sale Invoice</h1>
    <p class="mb-4">Your Sale Invoice Details.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h6 class="m-0 font-weight-bold text-primary mr-2">Sale Invoice Details</h6>
                @if($sale_invoice->is_draft)
                    <div class="badge badge-secondary">Draft</div>
                @else
                    <div class="badge badge-success">Saved</div>
                @endif
            </div>
            @if($sale_invoice->is_draft && $sale_invoice->lines->count() > 0)
                <form action="{{ route('sale-invoices.save', $sale_invoice->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                </form>
            @endif
        </div>
        <div class="card-body">
            Customer: <strong>{{ $sale_invoice->customer_name }}</strong> | Subtotal: {{ $sale_invoice->subtotal }}$ | Vat (14%): {{ $sale_invoice->vat }}$ | Total: {{ $sale_invoice->total }}$
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Sale Invoice Lines</h6>
            @if($sale_invoice->is_draft)
                <a href="{{ route('sale-invoices.lines.create', $sale_invoice->id) }}" class="btn btn-primary btn-sm float-right">Add New Line</a>
            @endif
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
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
                    @forelse($sale_invoice->lines as $line)
                        <tr>
                            <td>{{ $line->product->name }}</td>
                            <td>{{ $line->price }}$</td>
                            <td>{{ $line->quantity }}</td>
                            <td>{{ $line->total }}$</td>
                            <td class="text-right">
                                @if($sale_invoice->is_draft)
                                    <a href="{{ route('sale-invoices.lines.edit', ['sale_invoice' => $sale_invoice->id, 'line' => $line->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('sale-invoices.lines.destroy', ['sale_invoice' => $sale_invoice->id, 'line' => $line->id]) }}" method="POST" class="d-inline">
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
                            <td colspan="5">No Sale Invoice Lines Found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
