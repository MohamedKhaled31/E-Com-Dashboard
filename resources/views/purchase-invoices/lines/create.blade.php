@extends('layouts.app')

@section('title', 'Add Line to Purchase Invoice')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Product to Invoice</h6>
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

            <form action="{{ route('purchase-invoices.lines.store', $purchase_invoice->id) }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="product_id">Select Product</label>
                    <select name="product_id" id="product_id" class="form-control">
                        <option value="">-- Choose Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} (Available: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" step="0.01" class="form-control" placeholder="0.00">
                </div>

                <div class="form-group mb-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1">
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary mt-3">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#product_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');

            var productPrice = selectedOption.data('price');

            if (productPrice) {
                $('#price').val(productPrice);
            } else {
                $('#price').val(''); 
            }
        });
    });
</script>
@endsection
