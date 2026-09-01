@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Purchase</h2>
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label>Item ID</label>
            <input type="number" name="item_id" class="form-control" value="{{ old('item_id') }}">
            <small class="form-text text-muted">Optional — internal ID of the related item.</small>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
@endsection