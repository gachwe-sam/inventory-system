@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Items</h2>
    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add Item</a>

    <div class="btn-group mb-3 ms-2" role="group">
        <a href="{{ route('items.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-outline-secondary">Export Excel</a>
        <a href="{{ route('items.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('items.export.pdf', request()->query()) }}" class="btn btn-outline-secondary">Export PDF</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('import_skipped') && count(session('import_skipped')) > 0)
        <div class="alert alert-warning">
            <strong>{{ count(session('import_skipped')) }} row(s) skipped:</strong>
            <ul class="mb-0">
                @foreach(session('import_skipped') as $skip)
                    <li>Row {{ $skip['row'] }}: {{ $skip['reason'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="POST" action="{{ route('items.import') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
                @csrf
                <input type="file" name="spreadsheet" accept=".xlsx,.csv" class="form-control" style="max-width: 320px;" required>
                <button type="submit" class="btn btn-outline-primary">Import</button>
                <small class="text-muted">Columns: Name, Description, Category (e.g. "Fertilizer &gt; CAN &gt; 25 KG BAG"), Quantity, Expiry Date, Unit Price, Reorder Level</small>
            </form>

            @if(session()->has('last_import_ids'))
                <form id="undoImportForm" method="POST" action="{{ route('items.import.undo') }}" class="mt-2" onsubmit="return confirm('Remove the items from the last import?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Undo Last Import</button>
                </form>
            @endif
        </div>
    </div>

    {{-- resources/views/items/index.blade.php --}}
<form method="GET" action="{{ route('items.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search name or description">
    </div>
    <div class="col-auto">
        <select name="category_id" class="form-select">
            <option value="">All categories</option>
            @foreach($categoryOptions as $option)
                <option value="{{ $option['id'] }}" {{ (int) request('category_id') === $option['id'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-auto form-check mt-2">
        <input type="checkbox" name="low_stock" value="1" class="form-check-input" id="lowStock"
               {{ request()->boolean('low_stock') ? 'checked' : '' }}>
        <label class="form-check-label" for="lowStock">Low stock only</label>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>


    <table class="table">
        <thead>
            <tr>
                <th>N.O</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Total stock</th>
                <th>Expiry Date</th>
                <th>Unit Price</th>
                <th>Actions</th>
               

            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td> {{ ($items->currentpage() -1) * $items->perpage() + $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td>
                    @if($item->category)
                        {{ $item->category->parent ? $item->category->parent->name . ' > ' : '' }}{{ $item->category->name }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $item->totalQuantity() }}</td>
                <td>{{ $item->expiry_date?->format('Y-m-d') }}</td>
                <td>{{ $item->unit_price }}</td>
                <td>
                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $items->links() }}
</div>
@endsection