@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Categories</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add Category</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
        <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn btn-outline-secondary">Export Excel</a>
        <a href="{{ route('categories.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('categories.export.pdf', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn btn-outline-secondary">Export PDF</a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="POST" action="{{ route('categories.import') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center flex-wrap">
                @csrf
                <input type="file" name="spreadsheet" accept=".xlsx,.csv" class="form-control" style="max-width: 320px;" required>
                <button type="submit" class="btn btn-outline-primary">Import</button>
                <small class="text-muted">Column: Path (e.g. "Fertilizer &gt; CAN &gt; 25 KG BAG") &mdash; one row per category, missing segments are created automatically.</small>
            </form>

            @if(session()->has('last_category_import_ids'))
                <form method="POST" action="{{ route('categories.import.undo') }}" class="mt-2" onsubmit="return confirm('Remove the categories created by the last import?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Undo Last Import</button>
                </form>
            @endif
        </div>
    </div>


    {{-- resources/views/items/category.blade.php --}}
<form method="GET" action="{{ route('categories.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search name or description">
    </div>
    <div class="col-auto">
        <select name="category_id" class="form-select">
            <option value="">All categories</option>
            @foreach($categoryOptions as $option)
                <option value="{{ $option['id'] }}" {{ (int) request('category_id') === $option['id'] ? 'selected' : '' }}>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>    
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>



    <table class="table">
        <thead>
            <tr><th>N.O</th><th>Name</th><th>Items Count</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td> {{ ($categories->currentpage() -1)*$categories->perpage() +$loop->iteration }}</td>
                    <td>
                        {{ $category->parent ? $category->parent->name . ' > ' : '' }}
                        <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
                    </td>
                    <td>{{ $category->items()->count() }}</td>
                    <td>
                        <a href="{{ route('categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary">Add Subcategory</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category and all of its subcategories?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection
