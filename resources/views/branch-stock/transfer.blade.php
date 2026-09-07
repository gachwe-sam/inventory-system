@extends('layouts.adminlte')
@section('title', 'Transfer Stock')

@section('content')
<x-ui.card>
    <h4>{{ $stock->item->name }} from {{ $stock->branch->name }}</h4>
    <p class="text-muted">Current balance: {{ $stock->quantity }}</p>

    <form action="{{ route('branch-stock.transfer', $stock) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Destination branch</label>
            <select name="to_branch_id" class="form-select" required>
                <option value="">-- Select branch --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(old('to_branch_id') == $branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <x-ui.form-field name="quantity" label="Quantity to transfer" type="number" step="any" min="0.01" required />
        <x-ui.form-field name="notes" label="Notes (optional)" />

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-primary">Transfer</button>
        <a href="{{ $back }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-ui.card>
@endsection
