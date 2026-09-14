<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class OtpController extends Controller
{
        public function showChooseChannelForm(Request $request, OtpService $otpService)
    {
        $user = $this->pendingUser($request);

        $channels = $otpService->availableChannelsFor($user);

        if (empty($channels)) {
            $this->logUserIn($request, $user, (bool) $request->session()->get('2fa_remember'));
            $request->session()->forget(['2fa_user_id', '2fa_remember']);

            return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
        }

        return view('otp.request', ['channels' => $channels]);
    }


    public function send(Request $request, OtpService $otpService): RedirectResponse
    {
        $user = $this->pendingUser($request);

        $request->validate([
            'channel' => ['required', Rule::in($otpService->availableChannelsFor($user))],
        ]);

        try {
            $otpService->issue($user, $request->input('channel'));
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['channel' => 'That delivery method is not available right now.']);
        }

        // MAIL_MAILER=log for now, so surface the code on the page too.
        return redirect()->route('otp.verify.form')->with('debug_otp', $user->otp);
    }

    public function showVerifyForm(Request $request)
    {
        $this->pendingUser($request);

        return view('otp.verify');
    }

    public function verify(Request $request, OtpService $otpService): RedirectResponse
    {
        $request->validate(['code' => 'required|digits:6']);

        $user = $this->pendingUser($request);

        if (! $otpService->verify($user, $request->input('code'))) {
            return back()->withErrors(['code' => 'Invalid or expired code. Please try again.']);
        }

        $this->logUserIn($request, $user, (bool) $request->session()->get('2fa_remember'));

        $request->session()->forget(['2fa_user_id', '2fa_remember']);

        return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
    }

    private function pendingUser(Request $request): User
    {
        $userId = $request->session()->get('2fa_user_id');

        if (! $userId) {
            throw new HttpResponseException(
                redirect()->route('login')->withErrors(['email' => 'Please log in again.'])
            );
        }

        return User::findOrFail($userId);
    }
}
