@extends('layouts.adminlte')
@section('title', $category->name)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
        @foreach($category->ancestors() as $ancestor)
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $ancestor) }}">{{ $ancestor->name }}</a></li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
    </ol>
</nav>

@if($subcategories !== null)
    <p class="text-muted">Choose a subcategory to drill in further.</p>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Subcategory</th>
                        <th>Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subcategories as $subcategory)
                    <tr>
                        <td><a href="{{ route('categories.show', $subcategory) }}">{{ $subcategory->name }}</a></td>
                        <td>{{ $subcategory->items_count }}</td>
                        <td>
                            <a href="{{ route('categories.create', ['parent_id' => $subcategory->id]) }}" class="btn btn-sm btn-outline-primary">Add Subcategory</a>
                            <a href="{{ route('categories.edit', $subcategory) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <p class="text-muted">Items filed under "{{ $category->name }}".</p>

    <div class="btn-group mb-3" role="group">
        <a href="{{ route('items.export', ['format' => 'xlsx', 'category_id' => $category->id]) }}" class="btn btn-outline-secondary">Export Excel</a>
        <a href="{{ route('items.export', ['format' => 'csv', 'category_id' => $category->id]) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('items.export.pdf', ['category_id' => $category->id]) }}" class="btn btn-outline-secondary">Export PDF</a>
        <a href="{{ route('items.index', ['category_id' => $category->id]) }}" class="btn btn-outline-primary">Manage in Items (import here)</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No items in this category yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">Back to Categories</a>
@endsection
