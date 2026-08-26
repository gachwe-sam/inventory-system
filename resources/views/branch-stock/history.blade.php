@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stock History — {{ $stock->item->name }} @ {{ $stock->branch->name }}</h2>
    <p class="text-muted">Current balance: {{ $stock->quantity }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Change</th>
                <th>Notes</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $movement)
            <tr>
                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $movement->type)) }}</td>
                <td class="{{ $movement->quantity_change >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $movement->quantity_change >= 0 ? '+' : '' }}{{ $movement->quantity_change }}
                </td>
                <td>{{ $movement->notes }}</td>
                <td>{{ $movement->user->name ?? '—' }}</td>
            </tr>
            @empty
                <tr><td colspan="5">No movements recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $movements->links() }}

   <a href="{{ $back }}" class="btn btn-secondary">Back to branch</a>
</div>
@endsection
