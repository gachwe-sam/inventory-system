<p class="text-muted">Update your account's profile information and email address.</p>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <x-ui.form-field name="name" label="Name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
    <x-ui.form-field name="email" label="Email" type="email" :value="old('email', $user->email)" required autocomplete="username" />

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <p class="text-muted small">
            Your email address is unverified.
            <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Click here to re-send the verification email.</button>
        </p>
        @if (session('status') === 'verification-link-sent')
            <x-ui.alert type="success" message="A new verification link has been sent to your email address." />
        @endif
    @endif

    <div class="d-flex align-items-center gap-3 mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        @if (session('status') === 'profile-updated')
            <span class="text-muted small">Saved.</span>
        @endif
    </div>
</form>
