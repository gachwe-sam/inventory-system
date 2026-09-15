@extends('layouts.adminlte-guest')
@section('title', "Verify it's you")

@section('content')
<p class="login-box-msg">Choose how you'd like to receive your verification code.</p>

<form method="POST" action="{{ route('otp.send') }}">
    @csrf

    @foreach ($channels as $channel)
        <div class="form-check mb-2">
            <input type="radio" name="channel" value="{{ $channel }}" id="channel-{{ $channel }}"
                   class="form-check-input" {{ $loop->first ? 'checked' : '' }} required>
            <label for="channel-{{ $channel }}" class="form-check-label text-capitalize">{{ $channel }}</label>
        </div>
    @endforeach

    @error('channel')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <button type="submit" class="btn btn-primary w-100 mt-3">
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        <i class="bi bi-send"></i> Send code
    </button>
</form>
@endsection
