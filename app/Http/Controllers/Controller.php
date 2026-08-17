<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Log a user in and regenerate the session to prevent session fixation.
     */
    protected function logUserIn(Request $request, User $user): void
    {
        auth()->login($user);
        $request->session()->regenerate();
    }
}
