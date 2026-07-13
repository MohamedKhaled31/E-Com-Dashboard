@extends('layouts.app')

@section('title', 'Sale Invoices')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Sale Invoices</h1>
    <p class="mb-4">Manage and view all your sale invoices.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Sale Invoices List</h6>
            <a href="{{ route('sale-invoices.create') }}" class="btn btn-primary btn-sm float-right">Add Sale Invoice</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Items Count</th>
                        <th>Subtotal</th>
                        <th>Vat (14%)</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sale_invoices as $sale_invoice)
                        <tr>
                            <td>{{ $sale_invoice->user->name }}</td>
                            <td>{{ $sale_invoice->lines_count }}</td>
                            <td>{{ $sale_invoice->subtotal }}$</td>
                            <td>{{ $sale_invoice->vat }}$</td>
                            <td>{{ $sale_invoice->total }}$</td>
                            <td>
                                @if($sale_invoice->is_draft)
                                    <span class="badge badge-secondary">Draft</span>
                                @else
                                    <span class="badge badge-success">Saved</span>
                                @endif
                            </td>
                            <td>{{ $sale_invoice->created_at->format('Y-m-d h:i A') }}</td>
                            <td class="text-right">
                                <a href="{{ route('sale-invoices.show', $sale_invoice->id) }}" class="btn btn-primary btn-sm">View</a>
                                @if($sale_invoice->is_draft)
                                    <form action="{{ route('sale-invoices.destroy', $sale_invoice->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No Sale Invoices Found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
