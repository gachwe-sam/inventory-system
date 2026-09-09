@extends('layouts.adminlte')


@section('content')
<x-ui.page-header title="Suppliers" actionLabel="Add Supplier" :actionRoute="route('suppliers.create')" />
<x-ui.search-bar name="search" :value="request('search')" placeholder="Search name or description" />

<x-ui.data-table
    id="suppliers-table"
    :ajax-url="route('suppliers.data')"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name'],
        ['title' => 'Email', 'field' => 'email'],
        ['title' => 'Item', 'field' => 'item_name'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
