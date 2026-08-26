@extends('layouts.app')

@section('content')
<div class="container">
    <h2>branches</h2>
    <a href="{{ route('branches.create') }}" class="btn btn-primary mb-3">Add branch</a>

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

<form method="GET" action="{{ route('branches.index') }}" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search branch name">
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
            @forelse($branches as $branch)
                <tr>
                    <td> {{ ($branches->currentpage() -1)*$branches->perpage() +$loop->iteration }}</td>
                    <td>
                        {{ $branch->parent ? $branch->parent->name . ' > ' : '' }}
                        <a href="{{ route('branches.show', $branch) }}">{{ $branch->name }}</a>
                    </td>
                    <td>{{ $branch->stock()->count() }}</td>
                    <td>
                        <a href="{{ route('branches.create', ['parent_id' => $branch->id]) }}" class="btn btn-sm btn-outline-primary">Add Subbranch</a>
                        <a href="{{ route('branches.edit', $branch) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this branch and all of its subbranches?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No branches yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $branches->links() }}
</div>
@endsection
