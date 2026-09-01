@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Purchases</h2>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary mb-3">Add Purchase</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr><th>N.O</th><th>Name</th><th>Email</th><th>Item</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
                <tr>
                    <td>{{ ($purchases->currentpage() - 1) * $purchases->perpage() + $loop->iteration }}</td>
                    <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->name }}</a></td>
                    <td>{{ $purchase->email ?? '—' }}</td>
                    <td>{{ $purchase->item?->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this purchase?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No purchases yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $purchases->links() }}
</div>
@endsection