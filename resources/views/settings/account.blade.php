@extends('layouts.app')

@section('title', 'Account Settings')
@section('header_title', 'Account Settings & Profile')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; align-items: flex-start;">
        
        <!-- Left Profile Summary Card -->
        <div class="card" style="text-align: center; padding: 32px 24px;">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #818cf8); color: #fff; font-size: 2rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; box-shadow: 0 4px 12px rgba(79,70,229,0.3);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a;">{{ $user->name }}</h2>
            <p style="color: #64748b; font-size: 0.9rem; margin-top: 2px;">{{ '@' . ($user->username ?? 'student') }}</p>

            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-color); text-align: left; font-size: 0.85rem; color: #64748b; display: flex; flex-direction: column; gap: 10px;">
                <div>
                    <strong>Email:</strong> {{ $user->email }}
                </div>
                <div>
                    <strong>Status:</strong> 
                    @if($user->email_verified_at)
                        <span style="color: #059669; font-weight: 600;"><i data-lucide="badge-check" aria-hidden="true" style="height: 15px; vertical-align: -3px; width: 15px;"></i> Verified</span>
                    @else
                        <span style="color: #d97706; font-weight: 600;">Unverified</span>
                    @endif
                </div>
                <div>
                    <strong>Google Linked:</strong>
                    @if($user->google_id)
                        <span style="color: #3b82f6; font-weight: 600;"><i data-lucide="link" aria-hidden="true" style="height: 15px; vertical-align: -3px; width: 15px;"></i> Connected</span>
                    @else
                        <span style="color: #94a3b8;">Not linked</span>
                    @endif
                </div>
                <div>
                    <strong>Member Since:</strong> {{ $user->created_at->format('M Y') }}
                </div>
            </div>
        </div>

        <!-- Right Forms Column -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            {{-- 1. Edit Profile Info --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i data-lucide="user-round" aria-hidden="true"></i> Edit Profile Information</h3>
                </div>

                <form action="{{ route('settings.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Username (@handle)</label>
                        <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </form>
            </div>

            {{-- 2. Change Password --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i data-lucide="lock-keyhole" aria-hidden="true"></i> Change Password</h3>
                </div>

                <form action="{{ route('settings.password.request') }}" method="POST">
                    @csrf

                    @if($user->password)
                        <div class="form-group">
                            <label class="form-label" for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label" for="password">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-enter new password" required>
                    </div>

                    <button type="submit" class="btn btn-secondary">Update Password</button>
                </form>
            </div>

        </div>

    </div>
@endsection
