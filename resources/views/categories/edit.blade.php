@extends('layouts.adminlte')
@section('title', 'Edit Category')

@section('content')
<x-ui.card>
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <x-ui.form-field name="name" label="Name" :value="old('name', $category->name)" required />

        <div class="mb-3">
            <label class="form-label">Parent Category</label>
            <select name="parent_id" class="form-select">
                <option value="">-- None (top-level category) --</option>
                @foreach($parentOptions as $option)
                    <option value="{{ $option->id }}" {{ (string) old('parent_id', $category->parent_id) === (string) $option->id ? 'selected' : '' }}>
                        {{ $option->parent ? $option->parent->name . ' > ' : '' }}{{ $option->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">A category can't be moved under itself or one of its own subcategories.</small>
        </div>

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update</button>
    </form>
</x-ui.card>
@endsection
