<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Personal Task Manager</title>
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

        .auth-container { width: 100%; max-width: 440px; }

        .auth-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            padding: 36px 32px;
        }

        .brand-header { text-align: center; margin-bottom: 24px; }
        .brand-icon {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #fff; margin-bottom: 12px;
        }
        .brand-title { font-size: 1.35rem; font-weight: 800; }
        .brand-subtitle { font-size: 0.88rem; color: var(--text-muted); margin-top: 6px; }

        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border-color);
            border-radius: 8px; font-size: 0.95rem;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }

        .btn {
            width: 100%; padding: 11px; font-weight: 600;
            border-radius: 8px; border: none; cursor: pointer;
            background-color: var(--primary); color: #fff; font-size: 0.95rem;
        }
        .btn:hover { background-color: var(--primary-hover); }

        .alert { padding: 12px 14px; border-radius: 8px; font-size: 0.88rem; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        .auth-footer { text-align: center; margin-top: 24px; font-size: 0.9rem; color: var(--text-muted); }
        .auth-footer a { color: var(--primary); font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">🔑</div>
            <h1 class="brand-title">Reset Password</h1>
            <p class="brand-subtitle">Enter your registered email address to receive password reset instructions.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Your Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn">Send Password Reset Link</button>
        </form>

        <div class="auth-footer">
            Remember your password? <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>
