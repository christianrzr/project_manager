<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AccountChange;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AccountSettingsController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('settings.account', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
        ]);

        $user->update([
            'name'     => $validated['name'],
            'username' => $validated['username'],
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function requestUsernameChange(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'new_username' => 'required|string|max:50|alpha_dash|unique:users,username',
        ]);

        $change = AccountChange::create([
            'user_id'    => $user->id,
            'type'       => 'username',
            'new_value'  => $validated['new_username'],
            'token'      => Str::random(40),
            'expires_at' => now()->addHours(2),
        ]);

        // Auto apply or generate confirmation URL
        $user->update(['username' => $validated['new_username']]);

        return back()->with('success', 'Username successfully changed to @' . $validated['new_username'] . '!');
    }

    public function requestPasswordChange(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required_with:password|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function confirm(Request $request, $change)
    {
        $accountChange = AccountChange::where('token', $change)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = $accountChange->user;

        if ($accountChange->type === 'username') {
            $user->update(['username' => $accountChange->new_value]);
        } elseif ($accountChange->type === 'password') {
            $user->update(['password' => $accountChange->new_value]);
        }

        $accountChange->delete();

        return redirect()->route('settings.account')
            ->with('success', 'Account change has been confirmed and applied.');
    }
}
