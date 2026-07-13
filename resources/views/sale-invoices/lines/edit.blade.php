@extends('layouts.app')

@section('title', 'Edit Sale Invoice Item')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Edit Invoice Line</h1>
    <p class="mb-4">Update the quantity for the selected product in this invoice.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product: {{ $line->product->name }}</h6>
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

            <form action="{{ route('sale-invoices.lines.update', ['sale_invoice' => $sale_invoice->id, 'line' => $line->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control mb-3" placeholder="Enter Quantity" value="{{ old('quantity', $line->quantity) }}" min="1" required>
                </div>

                <div class="text-right">
                    <a href="{{ route('sale-invoices.show', $sale_invoice->id) }}" class="btn btn-secondary mt-3">Cancel</a>
                    <button type="submit" class="btn btn-primary mt-3">Update Quantity</button>
                </div>
            </form>
        </div>
    </div>
@endsection
