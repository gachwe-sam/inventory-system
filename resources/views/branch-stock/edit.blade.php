@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reorder level— {{ $stock->item->name }} @ {{ $stock->branch->name }}</h2>

    <form action="{{ route('branch-stock.update', $stock) }}" method="POST">
        @csrf @method('PATCH')

        <div class="mb-3">
            <label>Reorder Level</label>
            <input type="number" step="any" min="0" name="reorder_level" class="form-control" value="{{ old('reorder_level', $stock->reorder_level) }}" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ $back }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
