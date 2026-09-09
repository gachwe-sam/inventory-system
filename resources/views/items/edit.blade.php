@extends('layouts.adminlte')
@section('title', 'Edit Item')

@section('content')
<x-ui.card>
    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf @method('PUT')
        <x-ui.form-field name="name" label="Name" :value="$item->name" required />

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            @php $selectedCategoryId = old('category_id', $item->category_id); @endphp
            <select name="category_id" class="form-select" required>
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

        <x-ui.form-field name="expiry_date" label="Expiry Date" type="date" :value="old('expiry_date', $item->expiry_date?->format('Y-m-d'))" />
        <x-ui.form-field name="unit_price" label="Unit Price" type="number" step="any" :value="old('unit_price', $item->unit_price)" />

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update</button>
    </form>
</x-ui.card>
@endsection
