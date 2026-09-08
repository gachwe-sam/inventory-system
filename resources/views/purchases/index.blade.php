@extends('layouts.adminlte')


@section('content')
<x-ui.page-header title="Purchases" actionLabel="Add Purchase" :actionRoute="route('purchases.create')" />

<x-ui.data-table
    :ajax-url="route('purchases.data')"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name_html', 'formatter' => 'html'],
        ['title' => 'Items', 'field' => 'items'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
