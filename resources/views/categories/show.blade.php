@extends('layouts.app')

@section('content')
<div class="container">
    <h2>
        {{ $category->parent ? $category->parent->name . ' > ' : '' }}{{ $category->name }}
    </h2>
    <p class="text-muted">
        All items filed under "{{ $category->name }}" or any of its subcategories, fetched with a single recursive query.
    </p>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">Back to Categories</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name ?? 'N/A' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit_price }}</td>
            </tr>
            @empty
                <tr><td colspan="4">No items in this category or its subcategories.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
