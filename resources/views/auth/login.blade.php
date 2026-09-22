<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TaskManager — Sign in or Register</title>
    <style>
        :root {
            --bg-color: #fbfbfa;
            --card-bg: #ffffff;
            --text-primary: #191919;
            --text-secondary: #6e6e6e;
            --text-muted: #999999;
            --border: #e6e6e4;
            --primary: #c25e38; /* Claude signature terracotta accent or rich indigo */
            --primary-btn: #191919;
            --primary-btn-hover: #000000;
            --radius-lg: 16px;
            --radius-md: 10px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 40px 36px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .brand-symbol {
            width: 44px;
            height: 44px;
            background: #f4ede4;
            color: #c25e38;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 28px;
        }

        .btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-google {
            background: #ffffff;
            color: var(--text-primary);
            border-color: var(--border);
            font-weight: 600;
        }

        .btn-google:hover {
            background: #f7f7f6;
            border-color: #d0d0ce;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 22px 0;
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border);
        }

        .divider span {
            padding: 0 12px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            color: var(--text-primary);
            background: #ffffff;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #191919;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .btn-continue {
            background: var(--primary-btn);
            color: #ffffff;
            border: none;
            margin-top: 4px;
        }

        .btn-continue:hover {
            background: var(--primary-btn-hover);
            transform: translateY(-1px);
        }

        .alert {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 0.88rem;
            margin-bottom: 20px;
            text-align: left;
        }

        .alert-danger {
            background: #fdf2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .terms-note {
            margin-top: 24px;
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .footer-info {
            text-align: center;
            margin-top: 24px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-symbol">✳</div>
        <h1 class="auth-title">Welcome to TaskManager</h1>
        <p class="auth-subtitle">Sign in or create your account</p>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- 1. Continue with Google --}}
        <a href="{{ route('auth.google') }}" class="btn btn-google">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
            </svg>
            Continue with Google
        </a>

        <div class="divider">
            <span>OR</span>
        </div>

        {{-- 2. Email Form --}}
        <form action="{{ route('login.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-input" placeholder="Enter your email address..." value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn btn-continue">
                Continue with email →
            </button>
        </form>

        <p class="terms-note">
            By continuing, you acknowledge that you will receive a temporary 6-digit login verification code.
        </p>
    </div>

    <div class="footer-info">
        WST21-PM-2026-SF &bull; Christian Romano &bull; BSIT 2nd Year
    </div>
</div>

</body>
</html>
