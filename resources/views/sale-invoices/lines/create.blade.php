@extends('layouts.app')

@section('title', 'Add Item to Sale Invoice')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Add New Line</h1>
    <p class="mb-4">Add product items to your sale invoice draft.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
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

            <form action="{{ route('sale-invoices.lines.store', $sale_invoice->id) }}" method="POST">
                @csrf
                <select name="product_id" class="form-control mb-3" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (In Stock: {{ $product->quantity }})</option>
                    @endforeach
                </select>

                <input type="number" name="quantity" class="form-control mb-3" placeholder="Quantity" min="1" required>

                <div class="text-right">
                    <button class="btn btn-primary mt-3">Add Line</button>
                </div>
            </form>
        </div>
    </div>
@endsection
