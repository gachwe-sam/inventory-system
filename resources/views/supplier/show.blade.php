@extends('layouts.adminlte')
@section('title', $supplier->name)

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title mb-0">{{ $supplier->name }}</h3></div>
    <div class="card-body">
        <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
        <p><strong>Description:</strong> {{ $supplier->description ?? '—' }}</p>
        <p><strong>Item:</strong> {{ $supplier->item?->name ?? '—' }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">Edit</a>
        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
</div>
@endsection