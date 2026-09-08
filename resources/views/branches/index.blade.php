@extends('layouts.adminlte')


@section('content')
<x-ui.page-header title="Branches" actionLabel="Add Branch" :actionRoute="route('branches.create')" />

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

<form method="GET" action="{{ route('branches.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search branch name">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<x-ui.data-table
    :ajax-url="route('branches.data', request()->query())"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name_html', 'formatter' => 'html'],
        ['title' => 'Items Count', 'field' => 'items_count', 'hozAlign' => 'center', 'width' => 140],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
