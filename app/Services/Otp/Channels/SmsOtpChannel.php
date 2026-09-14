<?php

namespace App\Services\Otp\Channels;

use App\Models\User;
use App\Services\Otp\Contracts\OtpChannelHandler;
use RuntimeException;

class SmsOtpChannel implements OtpChannelHandler
{
    public function send(User $user, string $code): void
    {
        // TODO: wire a real SMS provider here (e.g. Twilio's Client::messages->create()).
        throw new RuntimeException('SMS provider is not configured yet.');
    }

    public function isAvailableFor(User $user): bool
    {
        return filled($user->phone);
    }
}
