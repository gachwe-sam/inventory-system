<?php

namespace App\Services\Otp;

use App\Models\OtpChannel;
use App\Models\User;
use App\Services\Otp\Channels\EmailOtpChannel;
use App\Services\Otp\Channels\SmsOtpChannel;
use App\Services\Otp\Channels\WhatsAppOtpChannel;
use App\Services\Otp\Contracts\OtpChannelHandler;
use Carbon\Carbon;
use InvalidArgumentException;

class OtpService
{
    private const CHANNELS = [
        'email' => EmailOtpChannel::class,
        'sms' => SmsOtpChannel::class,
        'whatsapp' => WhatsAppOtpChannel::class,
    ];

    public function availableChannelsFor(User $user): array
    {
        return collect(self::CHANNELS)
            ->filter(fn (string $class, string $key) => OtpChannel::isEnabled($key) && $this->resolve($key)->isAvailableFor($user))
            ->keys()
            ->all();
    }

    public function issue(User $user, string $channel): void
    {
        if (! in_array($channel, $this->availableChannelsFor($user), true)) {
            throw new InvalidArgumentException("Channel [{$channel}] is not available.");
        }

        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp' => $code,
            'otp_expires_at' => Carbon::now()->addMinutes(10)->toDateTimeString(),
        ])->save();

        $this->resolve($channel)->send($user, $code);
    }

    public function verify(User $user, string $code): bool
    {
        $valid = $user->otp
            && hash_equals($user->otp, $code)
            && $user->otp_expires_at
            && ! $user->otp_expires_at->isPast();

        if ($valid) {
            $user->forceFill(['otp' => null, 'otp_expires_at' => null])->save();
        }

        return $valid;
    }

    private function resolve(string $channel): OtpChannelHandler
    {
        return app(self::CHANNELS[$channel]);
    }
}
