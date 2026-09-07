@extends('layouts.adminlte')
@section('title', 'Adjust Stock')

@section('content')
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
    <div class="mb-4">
        <x-ui.data-table
            :ajax-url="route('stock.data', ['branch_id' => $branch->id])"
            :columns="[
                ['title' => 'Item', 'field' => 'item'],
                ['title' => 'Quantity', 'field' => 'quantity', 'hozAlign' => 'center'],
                ['title' => 'Reorder Level', 'field' => 'reorder_level', 'hozAlign' => 'center'],
                ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
            ]"
        />
    </div>

    @can('create', [App\Models\Branchstock::class, $branch])
        <h4>Not yet stocked here</h4>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
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
                            <tr><td colspan="4" class="text-center text-muted py-3">Every item is already stocked at this branch.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endcan
@endif
@endsection
