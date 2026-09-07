@extends('layouts.adminlte')
@section('title', 'Users')

@section('content')
<x-ui.page-header title="Users" />

<x-ui.data-table
    :ajax-url="route('users.data')"
    :columns="[
        ['title' => '#', 'formatter' => 'rownum', 'hozAlign' => 'center', 'width' => 60],
        ['title' => 'Name', 'field' => 'name'],
        ['title' => 'Email', 'field' => 'email'],
        ['title' => 'Branch', 'field' => 'branch'],
        ['title' => 'Role', 'field' => 'role'],
        ['title' => 'Actions', 'field' => 'actions_html', 'formatter' => 'html', 'hozAlign' => 'center', 'headerSort' => false],
    ]"
/>
@endsection
