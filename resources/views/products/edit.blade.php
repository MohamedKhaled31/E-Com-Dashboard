@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Product</h1>
    <p class="mb-4">Edit Product That Belong To Your Store.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
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
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="text" name="name" class="form-control mb-3" placeholder="Product Name"
                       value="{{ old('name', $product->name) }}">
                <select name="category_id" class="form-control mb-3">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}" {{ $product->category_id == $category->id?'selected':'' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="quantity" class="form-control mb-3" placeholder="Quantity"
                       value="{{ old('quantity', $product->quantity) }}">
                <input type="number" name="price" step="0.01" class="form-control mb-3" placeholder="Price"
                       value="{{ old('price', $product->price) }}">
                    <input type="file" name="image" class="form-control mb-3">
                    <div class="text-right">
                        <button class="btn btn-primary mt-3">Edit Product</button>
                    </div>
                </form>
        </div>
    </div>
@endsection
