@extends('layouts.adminlte')
@section('title', 'Issue Stock')

@section('content')
<x-ui.card>
    <h4>{{ $stock->item->name }} @ {{ $stock->branch->name }}</h4>
    <p class="text-muted">Current balance: {{ $stock->quantity }}</p>

    <form action="{{ route('branch-stock.issue', $stock) }}" method="POST">
        @csrf

        <x-ui.form-field name="quantity" label="Quantity to issue" type="number" step="any" min="0.01" required autofocus />
        <x-ui.form-field name="notes" label="Notes (optional)" />

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-warning"><i class="bi bi-box-arrow-up"></i> Issue</button>
        <a href="{{ $back }}" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Cancel</a>
    </form>
</x-ui.card>
@endsection
