@extends('layouts.adminlte-guest')
@section('title', 'Log in')

@section('content')
<p class="login-box-msg">Sign in to start your session</p>

<x-ui.alert type="success" :message="session('status')" />

<form method="POST" action="{{ route('login') }}">
    @csrf

    <x-ui.form-field name="email" label="Email" type="email" :value="old('email')" required autofocus autocomplete="username" />
    <x-ui.form-field name="password" label="Password" type="password" required autocomplete="current-password" />

    <div class="form-check mb-3">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
        <label for="remember_me" class="form-check-label">Remember me</label>
    </div>

    <div class="d-flex align-items-center justify-content-between">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-muted small">Forgot your password?</a>
        @endif
        <button type="submit" class="btn btn-primary">Log in</button>
    </div>
</form>
@endsection
