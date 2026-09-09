@extends('layouts.adminlte')
@section('title', 'Add Branch')

@section('content')
<x-ui.card>
    <form action="{{ route('branches.store') }}" method="POST">
        @csrf
        <x-ui.form-field name="name" label="Name" required />
        <x-ui.form-field name="location" label="Location" />
        <x-ui.form-field name="address" label="Address" />
        <x-ui.form-field name="phone" label="Phone" />

        <div class="mb-3">
            <label class="form-label">Parent Branch</label>
            <select name="parent_id" class="form-select">
                <option value="">-- None (top-level Branch) --</option>
                @foreach($parentOptions as $option)
                    <option value="{{ $option->id }}" {{ (string) old('parent_id', request('parent_id')) === (string) $option->id ? 'selected' : '' }}>
                        {{ $option->parent ? $option->parent->name . ' > ' : '' }}{{ $option->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Leave blank for a top-level Branch.</small>
        </div>

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save</button>
    </form>
</x-ui.card>
@endsection
