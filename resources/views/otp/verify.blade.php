@extends('layouts.adminlte-guest')
@section('title', 'Enter your code')

@section('content')
<p class="login-box-msg">Enter the 6-digit code we sent you.</p>

<x-ui.alert type="warning" :message="session('debug_otp') ? 'DEV MODE: your code is ' . session('debug_otp') : null" />

<form method="POST" action="{{ route('otp.verify') }}">
    @csrf

    <x-ui.form-field name="code" label="6-digit code" maxlength="6" required autofocus />

    <button type="submit" class="btn btn-success w-100">
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        <i class="bi bi-check2"></i> Verify code
    </button>
</form>
@endsection
