<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OtpChannel;
use Illuminate\Http\RedirectResponse;

class OtpChannelController extends Controller
{
    public function index(Request $request)
    {
        $channels = OtpChannel::all();

                return view('admin.otp-channels.index', compact('channels'));
    }

    public function update(OtpChannel $otpChannel, Request $request): RedirectResponse
    {
        $otpChannel->update(['enabled' => ! $otpChannel->enabled]);

        return back()->with(
            'success',
            ucfirst($otpChannel->channel) . ' is now ' . ($otpChannel->enabled ? 'enabled' : 'disabled') . '.'
        );
    }
}
