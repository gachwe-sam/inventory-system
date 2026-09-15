@extends('layouts.adminlte-guest')
@section('title', 'Reset Password')

@section('content')
<p class="login-box-msg">Choose a new password</p>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <x-ui.form-field name="email" label="Email" type="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
    <x-ui.form-field name="password" label="Password" type="password" required autocomplete="new-password" />
    <x-ui.form-field name="password_confirmation" label="Confirm Password" type="password" required autocomplete="new-password" />

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <i class="bi bi-key"></i> Reset Password</button>
    </div>
</form>
@endsection
