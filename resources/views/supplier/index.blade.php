@extends('layouts.adminlte')
@section('title', 'Suppliers')

@section('content')
<x-ui.page-header title="Suppliers" actionLabel="Add Supplier" :actionRoute="route('suppliers.create')" />

<x-ui.data-table
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
