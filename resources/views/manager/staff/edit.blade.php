@extends('layouts.adminlte')
@section('title', 'Edit Permissions')

@section('content')
<x-ui.card>
    <h4>{{ $user->name }}</h4>

    <form action="{{ route('manager.staff.update', $user) }}" method="POST">
        @csrf @method('PATCH')

        <div class="mb-3">
            @php $currentPermissions = old('permissions', $user->permissions->pluck('name')->toArray()); @endphp
            @foreach($permissions as $permission)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="form-check-input" id="perm-{{ $permission }}"
                        {{ in_array($permission, $currentPermissions) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm-{{ $permission }}">{{ $permission }}</label>
                </div>
            @endforeach
        </div>

        <x-ui.error-list :errors="$errors" />

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('manager.staff.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</x-ui.card>
@endsection
