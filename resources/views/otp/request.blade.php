@extends('layouts.adminlte')
@section('title', 'Login with OTP')

@section('content')
<div class="card mx-auto" style="max-width: 420px;">
    <div class="card-body">
        <h3 class="card-title">Login with OTP</h3>
        <form action="{{ route('otp.send') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>
    </div>
</div>
@endsection