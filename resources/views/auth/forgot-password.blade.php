@extends('layouts.adminlte-guest')
@section('title', 'Forgot Password')

@section('content')
<p class="text-muted small mb-3">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>

<x-ui.alert type="success" :message="session('status')" />

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <x-ui.form-field name="email" label="Email" type="email" :value="old('email')" required autofocus />

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Email Password Reset Link</button>
    </div>
</form>
@endsection
