<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show the Claude-style unified login/signup screen
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Step 1: Send 6-digit verification code to the entered email
     */
    public function sendCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower(trim($validated['email']));

        // Generate 6-digit verification code
        $otp = sprintf('%06d', mt_rand(100000, 999999));

        // Remove old codes for this email
        EmailOtp::where('email', $email)->delete();

        // Create new verification record (valid for 10 mins)
        EmailOtp::create([
            'email'      => $email,
            'otp'        => $otp,
            'type'       => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send real email via Laravel Mail
        try {
            Mail::raw("Your TaskManager verification code is: {$otp}\n\nThis code expires in 10 minutes. If you did not request this, you can safely ignore this email.", function ($message) use ($email, $otp) {
                $message->to($email)
                    ->subject("Your TaskManager verification code: {$otp}");
            });
        } catch (\Exception $e) {
            Log::error("Mail send error: " . $e->getMessage());
        }

        Log::info("Login Code sent to [{$email}]: {$otp}");

        return redirect()->route('auth.verify', ['email' => $email])
            ->with('success', 'A 6-digit verification code has been sent to ' . $email);
    }

    /**
     * Step 2: Show the Claude-style verification code input screen
     */
    public function showVerify(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Please enter your email first.']);
        }

        $otpRecord = EmailOtp::where('email', $email)
            ->latest()
            ->first();

        if (!$otpRecord || $otpRecord->isExpired()) {
            return redirect()->route('login')->withErrors(['email' => 'Your verification session has expired. Please enter your email again.']);
        }

        return view('auth.verify-code', compact('email'));
    }

    /**
     * Step 3: Verify the 6-digit code and log in / create user
     */
    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string|size:6',
        ]);

        $email = strtolower(trim($validated['email']));
        $code  = trim($validated['code']);

        $otpRecord = EmailOtp::where('email', $email)
            ->where('otp', $code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors([
                'code' => 'That code is incorrect or expired. Please check your inbox or request a new code.'
            ])->withInput();
        }

        // Check if user already exists or create new user
        $user = User::where('email', $email)->first();

        if (!$user) {
            // New user registration
            $usernameBase = explode('@', $email)[0];
            $usernameBase = Str::slug($usernameBase, '_');
            $username = $usernameBase;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $usernameBase . '_' . $counter++;
            }

            $user = User::create([
                'name'              => ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $email)[0])),
                'username'          => $username,
                'email'             => $email,
                'email_verified_at' => now(),
                'password'          => Hash::make(Str::random(24)),
            ]);

            // Create initial default category for the new user
            Category::create([
                'user_id'     => $user->id,
                'name'        => 'My Tasks',
                'color'       => '#4f46e5',
                'icon'        => '📝',
                'description' => 'General tasks and to-dos',
            ]);
        }

        // Delete used OTP
        $otpRecord->delete();

        // Log the user in
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Welcome, ' . $user->name . '!');
    }

    /**
     * Resend code
     */
    public function resendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = strtolower(trim($request->email));

        $newOtp = sprintf('%06d', mt_rand(100000, 999999));

        EmailOtp::updateOrCreate(
            ['email' => $email],
            [
                'otp'        => $newOtp,
                'type'       => 'login',
                'expires_at' => now()->addMinutes(10),
            ]
        );

        try {
            Mail::raw("Your new TaskManager verification code is: {$newOtp}\n\nThis code expires in 10 minutes.", function ($message) use ($email, $newOtp) {
                $message->to($email)
                    ->subject("Your new TaskManager verification code: {$newOtp}");
            });
        } catch (\Exception $e) {
            Log::error("Mail send error: " . $e->getMessage());
        }

        Log::info("Resent Login Code to [{$email}]: {$newOtp}");

        return back()
            ->with('success', 'A new verification code has been sent to your inbox!');
    }

    /**
     * Google OAuth
     */
    public function redirectToGoogle()
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google Sign-In is not configured in .env yet.'
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', strtolower($googleUser->getEmail()))
                ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                $usernameBase = Str::slug($googleUser->getName(), '_');
                $username = $usernameBase;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $usernameBase . '_' . $counter++;
                }

                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'username'          => $username,
                    'email'             => strtolower($googleUser->getEmail()),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'password'          => Hash::make(Str::random(24)),
                ]);

                Category::create([
                    'user_id'     => $user->id,
                    'name'        => 'My Tasks',
                    'color'       => '#4f46e5',
                    'icon'        => '📝',
                    'description' => 'General tasks and to-dos',
                ]);
            }

            Auth::login($user, true);

            return redirect()->route('dashboard')
                ->with('success', 'Welcome, ' . $user->name . '!');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been signed out.');
    }
}
