@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subcategories</h2>
    <a href="{{ route('subcategories.create') }}" class="btn btn-primary mb-3">Add Subcategory</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr><th>Name</th><th>Items Count</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($subcategories as $subCategory)
            <tr>
                <td>{{ $subCategory->name }}</td>
                <td>{{ $subCategory->items_count }}</td>
                <td>
                    <a href="{{ route('subcategories.edit', $subCategory) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('subcategories.destroy', $subCategory) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subcategory?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
