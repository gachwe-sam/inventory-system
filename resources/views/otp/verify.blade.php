@extends('layouts.adminlte')
@section('title', 'Enter OTP')

@section('content')
<div class="card mx-auto" style="max-width: 420px;">
    <div class="card-body">
        <h3 class="card-title">Enter OTP</h3>
        @if(session('debug_otp'))
            <div class="alert alert-warning">
                <strong>DEV MODE:</strong> Your OTP is <code>{{ session('debug_otp') }}</code>
            </div>
        @endif
        <form action="{{ route('otp.verify') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">6-digit OTP</label>
                <input type="text" name="otp" class="form-control" maxlength="6" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Verify OTP</button>
        </form>
        @error('otp') <div class="alert alert-danger mt-3">{{ $message }}</div> @enderror
    </div>
</div>
@endsection