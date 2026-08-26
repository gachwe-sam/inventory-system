@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Adjust Stock</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->hasRole('admin'))
        <form method="GET" action="{{ route('stock.index') }}" class="row g-2 mb-3">
            <div class="col-auto">
                <select name="branch_id" class="form-select" onchange="this.form.submit()">
                    @foreach($branches as $option)
                        <option value="{{ $option->id }}" {{ $branch && $branch->id === $option->id ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    @endif

    @if(! $branch)
        <p class="text-muted">No branches available yet.</p>
    @else
        <h4>Stock at "{{ $branch->name }}"</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th><th>Quantity</th><th>Reorder Level</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockedItems as $row)
                @continue(! $row->item)
                <tr>
                    <td>{{ $row->item->name }}</td>
                    <td>{{ $row->quantity }}</td>
                    <td>{{ $row->reorder_level }}</td>
                    <td>
                        @can('receive', $row)
                            <a href="{{ route('branch-stock.receive.form', $row) }}" class="btn btn-sm btn-success">Receive</a>
                        @endcan
                        @can('issue', $row)
                            <a href="{{ route('branch-stock.issue.form', $row) }}" class="btn btn-sm btn-warning">Issue</a>
                        @endcan
                        @can('transfer', $row)
                            <a href="{{ route('branch-stock.transfer.form', $row) }}" class="btn btn-sm btn-primary">Transfer</a>
                        @endcan
                        @can('view', $row)
                            <a href="{{ route('branch-stock.history', $row) }}" class="btn btn-sm btn-outline-secondary">History</a>
                        @endcan
                        @can('update', $row)
                            <a href="{{ route('branch-stock.edit', $row) }}" class="btn btn-sm btn-outline-warning">Reorder Level</a>
                        @endcan
                    </td>
                </tr>
                @empty
                    <tr><td colspan="4">No stock recorded at this branch yet.</td></tr>
                @endforelse
            </tbody>
        </table>

        @can('create', [App\Models\Branchstock::class, $branch])
            <h4 class="mt-4">Not yet stocked here</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th><th>Initial Quantity</th><th>Reorder Level</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unstockedItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td><input type="number" step="any" min="0" name="quantity" form="add-stock-{{ $item->id }}" class="form-control form-control-sm" value="0"></td>
                        <td><input type="number" step="any" min="0" name="reorder_level" form="add-stock-{{ $item->id }}" class="form-control form-control-sm" value="0"></td>
                        <td>
                            <form id="add-stock-{{ $item->id }}" action="{{ route('stock.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                <button type="submit" class="btn btn-sm btn-primary">Add to my branch stock</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4">Every item is already stocked at this branch.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endcan
    @endif
</div>
@endsection
