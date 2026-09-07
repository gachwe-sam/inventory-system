@extends('layouts.adminlte')
@section('title', 'Add Category')

@section('content')
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

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</x-ui.card>
@endsection
