@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Item</h2>
    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control">
                <option value="">-- Select Subcategory --</option>
                @foreach($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" {{ $item->subcategory_id == $subCategory->id ? 'selected' : '' }}>
                        {{ $subCategory->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" step="any" name="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}" required>
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $item->expiry_date?->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label>Unit Price</label>
            <input type="number" step="any" name="unit_price" class="form-control" value="{{ old('unit_price', $item->unit_price) }}">
        </div>
        <div class="mb-3">
            <label>Reorder Level</label>
            <input type="number" step="any" name="reorder_level" class="form-control" value="{{ old('reorder_level', $item->reorder_level) }}">
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