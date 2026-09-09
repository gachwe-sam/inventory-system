@props(['ajaxUrl', 'columns','id'=> null])
@php 
    $tableId = $id ?? 'data-table-' . uniqid();
@endphp
<div class="card">
    <div class="card-body p-0">
        <div id="{{ $tableId }}" data-tabulator data-ajax-url="{{ $ajaxUrl }}" data-columns="{{ json_encode($columns) }}"></div>
    </div>
</div>

