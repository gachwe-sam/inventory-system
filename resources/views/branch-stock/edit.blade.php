@extends('layouts.adminlte')
@section('title', 'Reorder Level')

@section('content')
<x-ui.card>
    <h4>{{ $stock->item->name }} @ {{ $stock->branch->name }}</h4>

    <form action="{{ route('branch-stock.update', $stock) }}" method="POST">
        @csrf @method('PATCH')

        <x-ui.form-field name="reorder_level" label="Reorder Level" type="number" step="any" min="0" :value="old('reorder_level', $stock->reorder_level)" required />

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ $back }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-ui.card>
@endsection
