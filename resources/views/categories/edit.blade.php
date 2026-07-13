@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Category</h1>
    <p class="mb-4">Edit Category That Belong To Your Store.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Category Details</h6>
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
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" class="form-control" placeholder="Category Name" value="{{ $category->name }}">
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Save Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection
