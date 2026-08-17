@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Subcategory</h2>
    <form action="{{ route('subcategories.update', $subCategory) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $subCategory->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
