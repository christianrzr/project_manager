<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check your email for a code — TaskManager</title>
    <style>
        :root {
            --bg-color: #fbfbfa;
            --card-bg: #ffffff;
            --text-primary: #191919;
            --text-secondary: #6e6e6e;
            --text-muted: #999999;
            --border: #e6e6e4;
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
            font-size: 1.55rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .auth-subtitle {
            font-size: 0.92rem;
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .auth-subtitle strong {
            color: var(--text-primary);
        }

        .code-input {
            width: 100%;
            padding: 14px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: 10px;
            text-align: center;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: var(--text-primary);
            margin-bottom: 18px;
            transition: all 0.2s;
        }

        .code-input:focus {
            outline: none;
            border-color: #191919;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .btn-continue {
            width: 100%;
            padding: 13px;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 600;
            background: var(--primary-btn);
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-continue:hover {
            background: var(--primary-btn-hover);
            transform: translateY(-1px);
        }

        .demo-otp-banner {
            background: #fdf6ec;
            border: 1px dashed #e6a23c;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        .demo-otp-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #b88230;
        }

        .demo-otp-code {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 6px;
            color: #8c5b16;
            margin-top: 2px;
            font-family: monospace;
        }

        .alert {
            padding: 12px 14px;
            border-radius: var(--radius-md);
            font-size: 0.88rem;
            margin-bottom: 18px;
            text-align: left;
        }

        .alert-danger {
            background: #fdf2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .actions-row {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            font-size: 0.88rem;
        }

        .btn-link {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 0.88rem;
            text-decoration: underline;
            padding: 0;
        }

        .btn-link:hover {
            color: var(--text-primary);
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="brand-symbol">✳</div>
        <h1 class="auth-title">Check your email</h1>
        <p class="auth-subtitle">
            We've sent a 6-digit code to<br>
            <strong>{{ $email }}</strong>
        </p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif



        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('auth.verify.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <input type="text" name="code" class="code-input" maxlength="6" pattern="[0-9]{6}" placeholder="000000" autofocus required>

            <button type="submit" class="btn-continue">
                Continue →
            </button>
        </form>

        <div class="actions-row">
            <form action="{{ route('auth.verify.resend') }}" method="POST" style="display:inline;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="btn-link">Resend code</button>
            </form>
            <span style="color: var(--border);">&bull;</span>
            <a href="{{ route('login') }}" class="btn-link">Use a different email</a>
        </div>
    </div>
</div>

</body>
</html>
