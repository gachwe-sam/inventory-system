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
            @php $selectedCategoryId = old('category_id'); @endphp
            <select name="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                @foreach($categoryOptions as $option)
                    @php $isSelected = (string) $selectedCategoryId === (string) $option['id']; @endphp
                    <option
                        value="{{ $option['id'] }}"
                        {{ $isSelected ? 'selected' : '' }}
                        {{ (! $option['is_leaf'] && ! $isSelected) ? 'disabled' : '' }}
                    >
                        {{ $option['label'] }}{{ ! $option['is_leaf'] ? ' (has subcategories)' : '' }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Items can only be filed under the lowest-level subcategory — categories with subcategories of their own are grayed out.</small>
        </div>
        <div class="mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}">
        </div>
        <div class="mb-3">
            <label>Unit Price</label>
            <input type="number" step="any" name="unit_price" class="form-control" value="{{ old('unit_price') }}">
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