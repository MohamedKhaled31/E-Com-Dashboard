@extends('layouts.app')

@section('title', 'Add New Purchase Invoice')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Add New Purchase Invoice</h1>
    <p class="mb-4">Add New Purchase Invoice To Your Store.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Purchase Invoice Details</h6>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('purchase-invoices.store') }}" method="POST">
                @csrf
                <select name="supplier_id" class="form-control mb-3">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Add Purchase Invoice</button>
                </div>
            </form>
        </div>
    </div>
@endsection
