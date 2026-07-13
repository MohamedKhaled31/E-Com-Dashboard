@extends('layouts.app')

@section('title', 'Add New Sale Invoice')

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Add New Sale Invoice</h1>
    <p class="mb-4">Create a new sale invoice by providing the customer name.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Sale Invoice Details</h6>
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

            <form action="{{ route('sale-invoices.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="customer_name" class="form-control mb-3" placeholder="Enter Customer Name" value="{{ old('customer_name') }}" required>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Create Invoice Draft</button>
                </div>
            </form>
        </div>
    </div>
@endsection
