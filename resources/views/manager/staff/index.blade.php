@extends('layouts.adminlte')


@section('content')
<x-ui.page-header title="My Branch Staff" />

<x-ui.data-table
    :ajax-url="route('manager.staff.data')"
    :columns="[
        ['title' => 'Name', 'field' => 'name'],
        ['title' => 'Email', 'field' => 'email'],
        ['title' => 'Permissions', 'field' => 'permissions'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
