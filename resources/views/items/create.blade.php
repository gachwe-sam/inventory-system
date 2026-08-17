@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Item</h2>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                @foreach($categoryOptions as $option)
                    <option value="{{ $option['id'] }}" {{ (string) old('category_id') === (string) $option['id'] ? 'selected' : '' }}>
                        {{ $option['label'] }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Pick the most specific (lowest) category that applies, e.g. "Cold Beverage" rather than "Beverages".</small>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" step="any" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
        </div>
        <div class="mb-3">
            <label>Unit Price</label>
            <input type="number" step="any" name="unit_price" class="form-control" value="{{ old('unit_price') }}">
        </div>
        <div class="mb-3">
            <label>Reorder Level</label>
            <input type="number" step="any" name="reorder_level" class="form-control" value="{{ old('reorder_level') }}">
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