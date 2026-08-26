@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Receive stock - {{ $stock->item->name }} @{{ $stock->branch->name }}</h2>
    <p class="text-muted">Current balance:{{ $stock->quantity }}</p>
    <form action="{{ route('branch-stock.receive',$stock) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Quantity to receive</label>
            <input type="number" step="any" min="0.01" name="quantity" class="form-control" value="{{ old('quantity') }}" required autofocus>
        </div>
        <div class="mb-3">
            <label>Notes(optional)</label>
            <input type="text" name="notes" class="form-control" value="{{ old('notes') }}">
        </div>

        @if ($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
            <p>{{ $error}}</p>
            @endforeach
        </div>
    @endif
    
    <button type="submit" class="btn btn-success">Receive</button>
    <a href="{{ $back }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection