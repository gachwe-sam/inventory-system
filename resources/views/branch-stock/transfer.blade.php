@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transfer Stock — {{ $stock->item->name }} from {{ $stock->branch->name }}</h2>
    <p class="text-muted">Current balance: {{ $stock->quantity }}</p>

    <form action="{{ route('branch-stock.transfer', $stock) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Destination branch</label>
            <select name="to_branch_id" class="form-control" required>
                <option value="">-- Select branch --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(old('to_branch_id') == $branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantity to transfer</label>
            <input type="number" step="any" min="0.01" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
        </div>

        <div class="mb-3">
            <label>Notes (optional)</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Transfer</button>
        <a href="{{ $back }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
