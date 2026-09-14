<?php

namespace App\Services\Otp\Contracts;

use App\Models\User;

interface OtpChannelHandler
{
    public function send(User $user, string $code): void;

    public function isAvailableFor(User $user): bool;
}
