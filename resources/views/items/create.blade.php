@extends('layouts.adminlte')
@section('title', 'Add Item')

@section('content')

<x-ui.card title="Import Items">
    <form method="POST" action="{{ route('items.import') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
        @csrf
        <input type="file" name="spreadsheet" accept=".xlsx,.csv" class="form-control" style="max-width: 320px;" required>
        <button type="submit" class="btn btn-outline-primary">Import</button>
        <small class="text-muted">Columns: Name, Description, Category (e.g. "Fertilizer &gt; CAN &gt; 25 KG BAG"), Quantity, Expiry Date, Unit Price, Reorder Level</small>
    </form>

    @if(session()->has('last_import_ids'))
        <form id="undoImportForm" method="POST" action="{{ route('items.import.undo') }}" class="mt-2" onsubmit="return confirm('Remove the items from the last import?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Undo Last Import</button>
        </form>
    @endif

    @if(Route::has('items.import.template'))
        <a href="{{ route('items.import.template') }}" class="btn btn-outline-secondary">Download Template</a>
    @endif

</x-ui.card>
<x-ui.card>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <x-ui.form-field name="name" label="Name" required />

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            @php $selectedCategoryId = old('category_id'); @endphp
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

        <x-ui.form-field name="expiry_date" label="Expiry Date" type="date" />
        <x-ui.form-field name="unit_price" label="Unit Price" type="number" step="any" />

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</x-ui.card>
@endsection
