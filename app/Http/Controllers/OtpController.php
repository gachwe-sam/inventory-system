<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function showRequestForm()
    {
        return view('otp.request');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        return $this->issueOtp($request, $user);
    }

    
    public function issueOtp(Request $request, User $user): RedirectResponse
    {
        $otp = random_int(100000, 999999);

        $request->session()->regenerate();
        session([
            'otp' => $otp,
            'otp_user_id' => $user->id,
        ]);

        Mail::raw("Your login verification code is: {$otp}", function ($message) use ($user) {
            $message->to($user->email)->subject('Your login verification code');
        });

        // SMTP isn't configured yet, so surface the code on the page too.
        return redirect()->route('otp.verify.form')->with('debug_otp', $otp);
    }

    public function showVerifyForm()
    {
        if (!session('otp')) {
            return redirect('/')->withErrors(['email' => 'Please log in again.']);
        }
        return view('otp.verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $storedOtp = session('otp');
        $userId = session('otp_user_id');

        if (!$storedOtp || !$userId) {
            return back()->withErrors(['otp' => 'OTP expired or missing. Please log in again.']);
        }

        if ($request->otp != $storedOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        $user = User::findOrFail($userId);

        $this->logUserIn($request, $user);

        session()->forget(['otp', 'otp_user_id']);

        return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
    }
}