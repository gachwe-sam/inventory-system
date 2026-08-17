@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Enter OTP</h2>
    <p>We emailed you a 6-digit verification code. Enter it below to finish logging in.</p>

    @if(session('debug_otp'))
        <div class="alert alert-warning">
            <strong>DEV MODE (no SMTP configured yet):</strong> Your OTP is <code>{{ session('debug_otp') }}</code>
        </div>
    @endif

    <form action="{{ route('otp.verify') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>6-digit OTP</label>
            <input type="text" name="otp" class="form-control" maxlength="6" required>
        </div>
        <button type="submit" class="btn btn-success">Verify OTP</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>
@endsection