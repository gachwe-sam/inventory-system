@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $purchase->name }}</li>
        </ol>
    </nav>

    <h2>{{ $purchase->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <tr><th>Description</th><td>{{ $purchase->description ?? '—' }}</td></tr>
        <tr><th>Email</th><td>{{ $purchase->email ?? '—' }}</td></tr>
        <tr><th>Item</th><td>{{ $purchase->item?->name ?? '—' }}</td></tr>
    </table>

    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back to Purchases</a>
</div>
@endsection