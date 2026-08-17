@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Login with OTP</h2>
    <form action="{{ route('otp.send') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send OTP</button>
    </form>
</div>
@endsection