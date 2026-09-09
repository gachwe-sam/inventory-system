@extends('layouts.adminlte')


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
    <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
    <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-csv"></i> Export CSV</a>
    <a href="{{ route('categories.export.pdf', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
</div>



<form method="GET" action="{{ route('categories.index') }}" class="row g-2 my-3">
    <x-ui.search-bar name="search" :value="request('search')" placeholder="Search name or description" target="categories-table" />
    
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel"></i>Filter
        </button>
    </div>
</form>

<x-ui.data-table
    id="categories-table"
    :ajax-url="route('categories.data', request()->query())"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name_html', 'formatter' => 'html'],
        ['title' => 'Items Count', 'field' => 'items_count', 'hozAlign' => 'center', 'width' => 140],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
