@extends('layouts.adminlte')
@section ('title', 'OTP Channels')

@section('content')
<u-ui.page-header title="OTP Delivery Channels" />

<x-ui.alert type="success" :message="session('success')" />
<div class="card">
    <div class="table mb-o">
        <thead>
            <tr>
                <th> Channel </th>
                <th class="text-center">Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($channels as $channel)
            <tr>
                <td class="text-capitalize">{{ $channel->channel }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $channel->enabled ? 'success' : 'secondary' }}">
                                {{ $channel->enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.otp-channels.update', $channel) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                    Turn {{ $channel->enabled ? 'off' : 'on' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    <i class="bi bi-info-circle"></i>
    This is just a list of channels that can be used to send OTPs. You can enable or disable them as needed.
    and the actual sending of OTPs will depend on the configuration of your application and the availability of these channels.
    NOTE: I will rewire them later on for now this is a just a rerun of what it will look like.
</p>
@endsection