<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    // Display OTP verification page
    public function show()
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Please register or log in first.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');

            return redirect()->route('register')
                ->with('error', 'Account not found. Please register again.');
        }

        if ($user->email_verified_at) {
            session()->forget('otp_user_id');

            return redirect()->route('login')
                ->with('success', 'Your email is already verified. Please log in.');
        }

        return view('auth.verify-otp', compact('user'));
    }

    // Verify OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Your verification session has expired.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');

            return redirect()->route('register')
                ->with('error', 'Account not found.');
        }

        if ($user->email_verified_at) {
            session()->forget('otp_user_id');

            return redirect()->route('login')
                ->with('success', 'Your email is already verified.');
        }

        if (!$user->otp_code_hash || !$user->getRawOriginal('otp_expires_at')) {
            return back()->withErrors([
                'otp' => 'No active OTP found. Please request a new one.'
            ]);
        }

        // Read expiration timestamp consistently as UTC
        $expiresAt = Carbon::parse(
            $user->getRawOriginal('otp_expires_at'),
            'UTC'
        );

        if (now('UTC')->greaterThan($expiresAt)) {
            return back()->withErrors([
                'otp' => 'Your OTP has expired. Please request a new one.'
            ]);
        }

        // Maximum five attempts
        if ($user->otp_attempts >= 5) {
            return back()->withErrors([
                'otp' => 'Maximum attempts reached. Please request a new OTP.'
            ]);
        }

        $user->increment('otp_attempts');
        $user->refresh();

        if (!Hash::check($request->otp, $user->otp_code_hash)) {
            return back()->withErrors([
                'otp' => 'Incorrect OTP. Please try again.'
            ]);
        }

        // Verify email and clear OTP information
        $user->update([
            'email_verified_at' => now('UTC'),
            'otp_code_hash' => null,
            'otp_expires_at' => null,
            'otp_sent_at' => null,
            'otp_attempts' => 0,
        ]);

        Auth::login($user);

        $request->session()->regenerate();
        $request->session()->forget('otp_user_id');

        return redirect()->route('dashboard')
            ->with('success', 'Email verified successfully! Welcome to the system.');
    }

    // Resend OTP
    public function resend()
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('register')
                ->with('error', 'Your verification session has expired.');
        }

        $user = User::find($userId);

        if (!$user) {
            session()->forget('otp_user_id');

            return redirect()->route('register')
                ->with('error', 'Account not found.');
        }

        if ($user->email_verified_at) {
            session()->forget('otp_user_id');

            return redirect()->route('login')
                ->with('success', 'Your email is already verified.');
        }

        // Check the 60-second cooldown using UTC timestamps
        if ($user->getRawOriginal('otp_sent_at')) {

            $sentAt = Carbon::parse(
                $user->getRawOriginal('otp_sent_at'),
                'UTC'
            );

            $secondsElapsed = abs(
                now('UTC')->timestamp - $sentAt->timestamp
            );

            if ($secondsElapsed < 60) {
                $remaining = 60 - $secondsElapsed;

                return back()->withErrors([
                    'otp' => "Please wait {$remaining} seconds before requesting another OTP."
                ]);
            }
        }

        // Generate a new six-digit OTP
        $otp = (string) random_int(100000, 999999);

        // Send OTP email
        try {
            Mail::raw(
                "Hello {$user->name},\n\n"
                . "Your Inventory Sales System verification code is: {$otp}\n\n"
                . "This code expires in 5 minutes.\n"
                . "Do not share this code with anyone.\n\n"
                . "Thank you.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Inventory Sales System - Email Verification');
                }
            );

                } catch (\Throwable $e) {

            Log::error('OTP EMAIL SENDING FAILED', [
                'user_id' => $user->id,
                'email' => $user->email,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'otp' => 'Unable to send OTP email. Error: '
                    . $e->getMessage()
            ]);
        }

        // Save OTP after successful email sending
        $user->update([
            'otp_code_hash' => Hash::make($otp),
            'otp_expires_at' => now('UTC')->addMinutes(5),
            'otp_sent_at' => now('UTC'),
            'otp_attempts' => 0,
        ]);

        return back()->with(
            'success',
            'A new OTP has been sent to your email address.'
        );
    }
}
