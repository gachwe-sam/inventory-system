<?php

namespace App\Services\Otp\Channels;

use App\Models\User;
use App\Services\Otp\Contracts\OtpChannelHandler;
use RuntimeException;

class WhatsAppOtpChannel implements OtpChannelHandler
{
    public function send(User $user, string $code): void
    {
        // TODO: wire a real WhatsApp Business API provider here (e.g. Twilio's WhatsApp channel).
        throw new RuntimeException('WhatsApp provider is not configured yet.');
    }

    public function isAvailableFor(User $user): bool
    {
        return filled($user->phone);
    }
}
