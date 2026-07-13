@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Add New Category</h1>
    <p class="mb-4">Add New Category To Your Store.</p>

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
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="Category Name">
                <div class="text-right">
                    <button class="btn btn-primary mt-3">Add Category</button>
                </div>
            </form>
        </div>
    </div>
@endsection
