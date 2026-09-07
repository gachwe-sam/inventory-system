@extends('layouts.adminlte')
@section('title', $item->name)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Items</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
</nav>
<p class="text-muted">
    {{ $item->category->name }}
    &middot; Total across all branches: <strong>{{ $item->totalQuantity() }}</strong>
</p>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Branch</th>
                    <th>Quantity</th>
                    <th>Reorder Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockRows as $row)
                @continue(! $row->branch)
                <tr>
                    <td>{{ $row->branch->name }}</td>
                    <td>{{ $row->quantity }}</td>
                    <td>{{ $row->reorder_level }}</td>
                    <td>
                        <a href="{{ route('branch-stock.history', $row) }}" class="btn btn-sm btn-outline-secondary">History</a>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No stock recorded for this item yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<a href="{{ route('items.index') }}" class="btn btn-secondary mt-3">Back to items</a>
@endsection
