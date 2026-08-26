@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('branches.index') }}">branches</a></li>
            @foreach($branch->ancestors() as $ancestor)
                <li class="breadcrumb-item"><a href="{{ route('branches.show', $ancestor) }}">{{ $ancestor->name }}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $branch->name }}</li>
        </ol>
    </nav>

    <h2>{{ $branch->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($subBranches !== null)
        <p class="text-muted">Choose a subbranch to drill in further.</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Subbranch</th>
                    <th>Stock rows</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subBranches as $subbranch)
                <tr>
                    <td><a href="{{ route('branches.show', $subbranch) }}">{{ $subbranch->name }}</a></td>
                    <td>{{ $subbranch->stock()->count() }}</td>
                    <td>
                        <a href="{{ route('branches.create', ['parent_id' => $subbranch->id]) }}" class="btn btn-sm btn-outline-primary">Add Subbranch</a>
                        <a href="{{ route('branches.edit', $subbranch) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">Stock at "{{ $branch->name }}".</p>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th><th>Quantity</th><th>Reorder Level</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stock as $row)
                @continue(! $row->item)
                <tr>
       
                    <td><a href="{{ route('items.show', $row->item) }}">{{ $row->item->name }}</a></td>
                    <td>{{ $row->quantity }}</td>
                    <td>{{ $row->reorder_level }}</td>
                    <td>
                        <a href="{{ route('branch-stock.receive.form', $row) }}" class="btn btn-sm btn-success">Receive</a>
                        <a href="{{ route('branch-stock.issue.form', $row) }}" class="btn btn-sm btn-warning">Issue</a>
                        <a href="{{ route('branch-stock.transfer.form', $row) }}" class="btn btn-sm btn-primary">Transfer</a>
                        <a href="{{ route('branch-stock.history', $row) }}" class="btn btn-sm btn-outline-secondary">History</a>
                        <a href="{{ route('branch-stock.edit', $row) }}" class="btn btn-sm btn-outline-warning">Reorder Level</a>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="4">No stock recorded in this branch yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <a href="{{ route('branches.index') }}" class="btn btn-secondary">Back to branches</a>
</div>
@endsection
