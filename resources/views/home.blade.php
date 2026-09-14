@extends('layouts.adminlte-guest')
@section('title', 'Welcome')

@section('content')
<p class="login-box-msg">Track stock across every branch, live.</p>

<x-ui.alert type="success" :message="session('success')" />

@auth
    <p class="text-center mb-3">Welcome back, {{ auth()->user()->name }}.</p>

    <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-speedometer2"></i> Go to Dashboard
    </a>

    <form action="{{ route('logout') }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="btn btn-link text-muted small">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
@else
    <div class="d-grid gap-2">
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right"></i> Log in
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-primary">
            <i class="bi bi-person-plus"></i> Register
        </a>
    </div>
@endauth
@endsection
