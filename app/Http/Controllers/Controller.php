<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
abstract class Controller
{
    use AuthorizesRequests;
    /**
     * Log a user in and regenerate the session to prevent session fixation.
     */ 
    protected function logUserIn(Request $request, User $user): void
    {
        auth()->login($user);
        $request->session()->regenerate();
    }
}
