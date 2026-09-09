@extends('layouts.adminlte')
@section('title', 'Stock History')

@section('content')
<h4>{{ $stock->item->name }} @ {{ $stock->branch->name }}</h4>
<p class="text-muted">Current balance: {{ $stock->quantity }}</p>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
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
                    <tr><td colspan="5" class="text-center text-muted py-3">No movements recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $movements->links() }}</div>
</div>

<a href="{{ $back }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Back to branch</a>
@endsection
