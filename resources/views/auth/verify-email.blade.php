@extends('layouts.adminlte-guest')
@section('title', 'Verify Email')

@section('content')
<p class="text-muted small mb-3">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>

@if (session('status') == 'verification-link-sent')
    <x-ui.alert type="success" message="A new verification link has been sent to the email address you provided during registration." />
@endif

<div class="d-flex align-items-center justify-content-between">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-muted small text-decoration-underline">Log Out</button>
    </form>
</div>
@endsection
