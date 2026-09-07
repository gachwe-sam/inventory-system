@extends('layouts.adminlte')
@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <x-ui.card title="Profile Information">
            @include('profile.partials.update-profile-information-form')
        </x-ui.card>

        <x-ui.card title="Update Password">
            @include('profile.partials.update-password-form')
        </x-ui.card>

        <x-ui.card title="Delete Account">
            @include('profile.partials.delete-user-form')
        </x-ui.card>
    </div>
</div>
@endsection
