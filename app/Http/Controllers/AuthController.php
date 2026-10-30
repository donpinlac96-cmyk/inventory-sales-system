<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    private function verifyCaptcha(Request $request): bool
    {
        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);

            return $response->successful()
                && $response->json('success') === true;

        } catch (\Throwable $e) {
            return false;
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required',
        ]);

        if (!$this->verifyCaptcha($request)) {
            return back()
                ->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'])
                ->withInput($request->only('name', 'email'));
        }

        // Create the new account as a Cashier.
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $user->role = 'cashier';

        // Generate a six-digit OTP.
        $otp = (string) random_int(100000, 999999);

        $user->otp_code_hash = Hash::make($otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->otp_sent_at = now();
        $user->otp_attempts = 0;
        $user->email_verified_at = null;
        $user->save();

        // Store the pending verification account in the session.
        $request->session()->put('otp_user_id', $user->id);

        // Send the OTP email.
        try {
            Mail::raw(
                "Hello {$user->name},\n\nYour Inventory Sales System verification code is: {$otp}\n\nThis code expires in 5 minutes. Do not share it with anyone.\n\nThank you.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Inventory Sales System - Email Verification');
                }
            );
        } catch (\Throwable $e) {
            return redirect()->route('otp.show')
                ->with('error', 'Your account was created, but the OTP email could not be sent. Please use Resend OTP.');
        }

        return redirect()->route('otp.show')
            ->with('success', 'Your account was created! Please check your email for the verification code.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        if (!$this->verifyCaptcha($request)) {
            return back()
                ->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'])
                ->onlyInput('email');
        }

        // Validate the password without logging in yet.
        if (!Auth::validate([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            return back()
                ->withErrors(['email' => 'The provided email or password is incorrect.'])
                ->onlyInput('email');
        }

        $user = User::where('email', $credentials['email'])->first();

        // Unverified accounts must complete OTP verification.
        if (!$user->email_verified_at) {
            $request->session()->put('otp_user_id', $user->id);

            return redirect()->route('otp.show')
                ->with('error', 'Please verify your email before logging in.');
        }

        Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Welcome back!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}

