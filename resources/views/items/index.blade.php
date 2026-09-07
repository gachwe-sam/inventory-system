@extends('layouts.adminlte')
@section('title', 'Items')

@section('content')
<x-ui.page-header title="Items" actionLabel="Add Item" :actionRoute="route('items.create')">
    <div class="btn-group" role="group">
        <a href="{{ route('items.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-outline-secondary">Export Excel</a>
        <a href="{{ route('items.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('items.export.pdf', request()->query()) }}" class="btn btn-outline-secondary">Export PDF</a>
    </div>
</x-ui.page-header>

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

<x-ui.card title="Import Items">
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
</x-ui.card>

<form method="GET" action="{{ route('items.index') }}" class="row g-2 my-3">
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

<x-ui.data-table
    :ajax-url="route('items.data', request()->query())"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name'],
        ['title' => 'Description', 'field' => 'description'],
        ['title' => 'Category', 'field' => 'category'],
        ['title' => 'Total stock', 'field' => 'total_stock', 'hozAlign' => 'center'],
        ['title' => 'Expiry Date', 'field' => 'expiry_date'],
        ['title' => 'Unit Price', 'field' => 'unit_price'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
