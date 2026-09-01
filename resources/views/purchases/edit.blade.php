@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Purchase</h2>
    <form action="{{ route('purchases.update', $purchase) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $purchase->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $purchase->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $purchase->email) }}">
        </div>
        <div class="mb-3">
            <label>Item ID</label>
            <input type="number" name="item_id" class="form-control" value="{{ old('item_id', $purchase->item_id) }}">
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection