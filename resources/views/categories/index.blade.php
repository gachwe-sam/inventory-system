@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Categories</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add Category</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr><th>Name</th><th>Items Count</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                @include('categories._branch', ['category' => $category, 'depth' => 0])
            @empty
                <tr><td colspan="3">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
