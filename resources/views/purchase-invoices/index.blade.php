@extends('layouts.app')

@section('title', 'Purchase Invoices')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Purchase Invoices</h1>
    <p class="mb-4">Your Store Purchase Invoices.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Invoices</h6>
            <a href="{{ route('purchase-invoices.create') }}" class="btn btn-primary btn-sm float-right">Add Purchase Invoice</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Items Count</th>
                        <th>Subtotal</th>
                        <th>Vat</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($purchase_invoices as $purchase_invoice)
                        <tr>
                            <td>
                                <div>
                                    {{ $purchase_invoice->supplier->name }}
                                </div>
                            </td>
                            <td>{{ $purchase_invoice->lines_count }}</td>
                            <td>{{ $purchase_invoice->subtotal }}$</td>
                            <td>{{ $purchase_invoice->vat }}$</td>
                            <td>{{ $purchase_invoice->total }}$</td>
                            <td>
                                @if($purchase_invoice->is_draft)
                                    <div class="badge badge-secondary">Draft</div>
                                    @else
                                    <div class="badge badge-success">Saved</div>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('purchase-invoices.show', $purchase_invoice->id) }}"
                                   class="btn btn-primary btn-sm">View</a>
                                @if($purchase_invoice->is_draft)
                                    <form action="{{ route('purchase-invoices.destroy', $purchase_invoice->id) }}" method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No Purchase Invoices Founded.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
