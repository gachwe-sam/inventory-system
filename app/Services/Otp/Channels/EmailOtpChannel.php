<?php

namespace App\Services\Otp\Channels;

use App\Models\User;
use App\Services\Otp\Contracts\OtpChannelHandler;
use Illuminate\Support\Facades\Mail;

class EmailOtpChannel implements OtpChannelHandler
{
    public function send(User $user, string $code): void
    {
        Mail::raw("Your verification code is: {$code}", function ($message) use ($user) {
            $message->to($user->email)->subject('Your verification code');
        });
    }

    public function isAvailableFor(User $user): bool
    {
        return true;
    }
}
