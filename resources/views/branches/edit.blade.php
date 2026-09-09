@extends('layouts.adminlte')
@section('title', 'Edit Branch')

@section('content')
<x-ui.card>
    <form action="{{ route('branches.update', $branch) }}" method="POST">
        @csrf @method('PUT')
        <x-ui.form-field name="name" label="Name" :value="old('name', $branch->name)" required />
        <x-ui.form-field name="location" label="Location" :value="old('location', $branch->location)" />
        <x-ui.form-field name="address" label="Address" :value="old('address', $branch->address)" />
        <x-ui.form-field name="phone" label="Phone" :value="old('phone', $branch->phone)" />

        <div class="mb-3">
            <label class="form-label">Parent Branch</label>
            <select name="parent_id" class="form-select">
                <option value="">-- None (top-level branch) --</option>
                @foreach($parentOptions as $option)
                    <option value="{{ $option->id }}" {{ (string) old('parent_id', $branch->parent_id) === (string) $option->id ? 'selected' : '' }}>
                        {{ $option->parent ? $option->parent->name . ' > ' : '' }}{{ $option->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">A branch can't be moved under itself or one of its own subbranches.</small>
        </div>

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update</button>
        <a href="{{ route('branches.show', $branch) }}" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancel</a>
    </form>
</x-ui.card>
@endsection
