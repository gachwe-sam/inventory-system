@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User — {{ $user->name }}</h2>

    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PATCH')

        <div class="mb-3">
            <label>Branch</label>
            <select name="branch_id" class="form-control">
                <option value="">-- None (Head Office / Admin) --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ (string) old('branch_id', $user->branch_id) === (string) $branch->id ? 'selected' : '' }}>
                        {{ $branch->parent ? $branch->parent->name . ' > ' : '' }}{{ $branch->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Leave blank for admins/HQ users who should see every branch.</small>
        </div>

        <div class="mb-3">
            <label>Role</label>
            @php $currentRole = old('role', $user->roles->first()?->name); @endphp
            <select name="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Stock Permissions</label>
                @php $currentPermissions = old('permissions', $user->permissions->pluck('name')->toArray()); @endphp
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="form-check-input" id="perm-{{ $permission }}"
                            {{ in_array($permission, $currentPermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm-{{ $permission }}">{{ $permission }}</label>
                    </div>
                @endforeach
        </div>


        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
