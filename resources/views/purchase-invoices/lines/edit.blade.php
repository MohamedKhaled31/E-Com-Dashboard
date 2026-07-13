@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Line</h1>
    <p class="mb-4">Edit Line That Belongs To Purchase Invoice.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Line Details</h6>
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
            <form action="{{ route('purchase-invoices.lines.update', ['purchase_invoice' => $purchase_invoice->id, 'line' => $line->id]) }}" method="POST">
                @method('PUT')
                @csrf
                <input type="number" name="price" step="0.01" class="form-control mb-3" placeholder="Price" value="{{ old('price', $line->price) }}">
                <input type="number" name="quantity" class="form-control mb-3" placeholder="Quantity" value="{{ old('quantity', $line->quantity) }}">
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Edit Line</button>
                </div>
            </form>
        </div>
    </div>
@endsection
