@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Users</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>N.O</th>
                <th>Name</th>
                <th>Email</th>
                <th>Branch</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ ($users->currentpage() - 1) * $users->perpage() + $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->branch->name ?? 'Head Office / Unassigned' }}</td>
                <td>{{ $user->roles->pluck('name')->join(', ') ?: 'No role' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
            @empty
                <tr><td colspan="6">No users yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
