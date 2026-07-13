@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Supplier</h1>
    <p class="mb-4">Edit Supplier That Belong To Your Store.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Supplier Details</h6>
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
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" class="form-control" placeholder="Supplier Name" value="{{ $supplier->name }}">
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
@endsection
