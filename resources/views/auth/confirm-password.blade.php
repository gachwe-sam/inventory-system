@extends('layouts.adminlte-guest')
@section('title', 'Confirm Password')

@section('content')
<p class="text-muted small mb-3">This is a secure area of the application. Please confirm your password before continuing.</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <x-ui.form-field name="password" label="Password" type="password" required autocomplete="current-password" />

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Confirm</button>
    </div>
</form>
@endsection
