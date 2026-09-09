@extends('layouts.adminlte')
@section('title', 'Add Category')

@section('content')

<x-ui.card title="Import Categories">
    <form method="POST" action="{{ route('categories.import') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
        @csrf
        <input type="file" name="spreadsheet" accept=".xlsx,.csv" class="form-control" style="max-width: 320px;" required>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-upload"></i> Import</button>
        <small class="text-muted">Column: Path (e.g. "Fertilizer &gt; CAN &gt; 25 KG BAG") &mdash; one row per category, missing segments are created automatically.</small>
    </form>

    @if(session()->has('last_category_import_ids'))
        <form method="POST" action="{{ route('categories.import.undo') }}" class="mt-2" onsubmit="return confirm('Remove the categories created by the last import?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-arrow-counterclockwise"></i> Undo Last Import</button>
        </form>
    @endif
</x-ui.card>
<x-ui.card>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <x-ui.form-field name="name" label="Name" :value="old('name')" required />

        <div class="mb-3">
            <label class="form-label">Parent Category</label>
            <select name="parent_id" class="form-select">
                <option value="">-- None (top-level category) --</option>
                @foreach($parentOptions as $option)
                    <option value="{{ $option->id }}" {{ (string) old('parent_id', request('parent_id')) === (string) $option->id ? 'selected' : '' }}>
                        {{ $option->parent ? $option->parent->name . ' > ' : '' }}{{ $option->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Leave blank for a top-level category like "Beverages". Pick a parent to create a subcategory like "Cold Beverage" under "Beverages".</small>
        </div>

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
    </form>
</x-ui.card>
@endsection
