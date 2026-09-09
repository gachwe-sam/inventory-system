@extends('layouts.adminlte')
@section('title', $purchase->name)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $purchase->name }}</li>
    </ol>
</nav>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Item</th><th>Quantity</th><th>Unit Price</th></tr></thead>
            <tbody>
                @forelse ($purchase->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->pivot->quantity }}</td>
                        <td>{{ $item->pivot->unit_price !== null ? number_format($item->pivot->unit_price, 2) : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No items recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning mt-3"><i class="bi bi-pencil"></i> Edit</a>
<a href="{{ route('purchases.index') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-left"></i> Back to Purchases</a>
@endsection
