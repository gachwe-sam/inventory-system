@props(['ajaxUrl', 'columns'])
<div class="card">
    <div class="card-body p-0">
        <div data-tabulator data-ajax-url="{{ $ajaxUrl }}" data-columns="{{ json_encode($columns) }}"></div>
    </div>
</div>
