@extends('layouts.adminlte-guest')
@section('title', 'Register')

@section('content')
<p class="login-box-msg">Create a new account</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <x-ui.form-field name="name" label="Name" :value="old('name')" required autofocus autocomplete="name" />
    <x-ui.form-field name="email" label="Email" type="email" :value="old('email')" required autocomplete="username" />
    <x-ui.form-field name="password" label="Password" type="password" required autocomplete="new-password" />
    <x-ui.form-field name="password_confirmation" label="Confirm Password" type="password" required autocomplete="new-password" />

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ route('login') }}" class="text-muted small">Already registered?</a>
        <button type="submit" class="btn btn-primary">Register</button>
    </div>
</form>
@endsection
