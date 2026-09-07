@extends('layouts.adminlte')
@section('title', 'Categories')

@section('content')
<x-ui.page-header title="Categories" actionLabel="Add Category" :actionRoute="route('categories.create')" />

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

<div class="btn-group mb-3 ms-2" role="group">
    <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-outline-secondary">Export Excel</a>
    <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline-secondary">Export CSV</a>
    <a href="{{ route('categories.export.pdf', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-outline-secondary">Export PDF</a>
</div>

<x-ui.card title="Import Categories">
    <form method="POST" action="{{ route('categories.import') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
        @csrf
        <input type="file" name="spreadsheet" accept=".xlsx,.csv" class="form-control" style="max-width: 320px;" required>
        <button type="submit" class="btn btn-outline-primary">Import</button>
        <small class="text-muted">Column: Path (e.g. "Fertilizer &gt; CAN &gt; 25 KG BAG") &mdash; one row per category, missing segments are created automatically.</small>
    </form>

    @if(session()->has('last_category_import_ids'))
        <form method="POST" action="{{ route('categories.import.undo') }}" class="mt-2" onsubmit="return confirm('Remove the categories created by the last import?')">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">Undo Last Import</button>
        </form>
    @endif
</x-ui.card>

<form method="GET" action="{{ route('categories.index') }}" class="row g-2 my-3">
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
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<x-ui.data-table
    :ajax-url="route('categories.data', request()->query())"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name_html', 'formatter' => 'html'],
        ['title' => 'Items Count', 'field' => 'items_count', 'hozAlign' => 'center', 'width' => 140],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
