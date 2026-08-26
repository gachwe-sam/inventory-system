@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Branch Staff</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($staff as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->permissions->pluck('name')->join(', ') ?: 'None' }}</td>
                <td>
                    <a href="{{ route('manager.staff.edit', $user) }}" class="btn btn-sm btn-warning">Edit Permissions</a>
                </td>
            </tr>
            @empty
                <tr><td colspan="4">No other staff at your branch yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
