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
    SMS and WhatsApp are stubbed in — turning one on before a real provider is wired
    (<code>app/Services/Otp/Channels/SmsOtpChannel.php</code> / <code>WhatsAppOtpChannel.php</code>)
    makes it show up as a choice on the login page but fail when someone actually picks it.
</p>
@endsection