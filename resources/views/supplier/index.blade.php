@extends('layouts.adminlte')

@section('content')
<x-ui.page-header title="Suppliers" actionLabel="Add Supplier" :actionRoute="route('suppliers.create')" />

<form method="GET" action="{{ route('suppliers.index') }}" class="row g-2 my-3">
    <x-ui.search-bar name="search" :value="request('search')" placeholder="Search name or email" target="suppliers-table" />

    <div class="col-auto">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </div>
</form>

<x-ui.data-table
    id="suppliers-table"
    :ajax-url="route('suppliers.data', request()->query())"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name'],
        ['title' => 'Email', 'field' => 'email'],
        ['title' => 'Item', 'field' => 'item_name'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
