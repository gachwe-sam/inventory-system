@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Items</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
        </ol>
    </nav>
    <h2>{{ $item->name}}</h2>
    <p class="text-muted">
        {{ $item->category->name }}
        &middot; Total across all branches:<strong>{{ $item->totalQuantity() }}</strong>
    </p>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
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
                    <a href="{{ route('branch-stock.history',$row) }}"class="btn btn-sm btn-outline-secondary">History</a>
                </td>
            </tr>
            @empty<tr><td colspan="4">No stock recorder for this item yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to items</a>
</div>
@endsection