<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — Personal Task Manager</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius: 12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
        }

        .auth-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            padding: 36px 32px;
            text-align: center;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .brand-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .brand-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 6px;
            line-height: 1.4;
        }

        .otp-banner {
            background: #eff6ff;
            border: 1.5px dashed #3b82f6;
            border-radius: 10px;
            padding: 14px 16px;
            margin: 20px 0;
            text-align: center;
        }

        .otp-banner-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #1d4ed8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .otp-banner-code {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e40af;
            letter-spacing: 4px;
            margin-top: 4px;
            font-family: monospace;
        }

        .form-group {
            margin: 20px 0;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
            text-align: center;
        }

        .otp-input {
            width: 100%;
            padding: 14px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 8px;
            text-align: center;
            font-family: monospace;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .otp-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-resend {
            background: transparent;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.88rem;
            border: none;
            cursor: pointer;
            padding: 8px;
            margin-top: 14px;
        }

        .btn-resend:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 0.88rem;
            margin-bottom: 18px;
            text-align: left;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .auth-footer {
            margin-top: 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="brand-icon">✉️</div>
        <h1 class="brand-title">Verify Your Email</h1>
        <p class="brand-subtitle">
            We sent a 6-digit verification code to<br>
            <strong>{{ $email }}</strong>
        </p>

        @if(session('success'))
            <div class="alert alert-success" style="margin-top: 16px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('otp_preview'))
            <div class="otp-banner">
                <div class="otp-banner-label">🔑 Demo Verification OTP</div>
                <div class="otp-banner-code">{{ session('otp_preview') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="margin-top: 16px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.verify.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label class="form-label" for="otp">Enter 6-Digit Code</label>
                <input type="text" name="otp" id="otp" class="otp-input" maxlength="6" pattern="[0-9]{6}" placeholder="000000" autofocus required>
            </div>

            <button type="submit" class="btn btn-primary">✓ Verify & Complete Registration</button>
        </form>

        <form action="{{ route('register.resend.otp') }}" method="POST" style="margin-top: 8px;">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="btn-resend">Didn't receive code? Click to resend</button>
        </form>

        <div class="auth-footer">
            Incorrect email? <a href="{{ route('register') }}">Back to Register</a>
        </div>
    </div>
</div>

</body>
</html>
