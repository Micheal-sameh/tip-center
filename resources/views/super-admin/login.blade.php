<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login — Crafando</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #0F172A; min-height: 100vh;
            display: flex; align-items: center; justify-content: center; }
        .login-card { background: #1E293B; border-radius: 16px; padding: 42px 40px;
            width: 100%; max-width: 420px; box-shadow: 0 25px 50px rgba(0,0,0,.5); }
        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 32px; }
        .brand-icon { width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, #2563EB, #1D4ED8);
            display: flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; }
        .brand-text h1 { font-size: 1.1rem; font-weight: 800; color: #fff; }
        .brand-text p { font-size: .78rem; color: rgba(255,255,255,.4); margin-top: 2px; }
        h2 { font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .subtitle { color: rgba(255,255,255,.45); font-size: .88rem; margin-bottom: 28px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.65);
            margin-bottom: 7px; }
        input { width: 100%; padding: 11px 14px; background: #0F172A; border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; color: #fff; font-size: .9rem; font-family: inherit;
            transition: border-color .2s; }
        input:focus { outline: none; border-color: #2563EB; }
        input::placeholder { color: rgba(255,255,255,.25); }
        .error-msg { color: #F87171; font-size: .78rem; margin-top: 5px; }
        .btn-login { width: 100%; padding: 12px; background: linear-gradient(135deg, #2563EB, #1D4ED8);
            color: #fff; border: none; border-radius: 10px; font-size: .92rem; font-weight: 700;
            cursor: pointer; margin-top: 8px; transition: opacity .2s; font-family: inherit; }
        .btn-login:hover { opacity: .9; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: rgba(255,255,255,.35); font-size: .8rem; text-decoration: none; }
        .back-link a:hover { color: rgba(255,255,255,.6); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-crown"></i></div>
            <div class="brand-text">
                <h1>Crafando Admin</h1>
                <p>Tenant Management System</p>
            </div>
        </div>

        <h2>Sign In</h2>
        <p class="subtitle">Access your super admin panel</p>

        <form action="{{ route('super-admin.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@crafando.com" required autofocus>
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Sign In</button>
        </form>

        <div class="back-link">
            <a href="{{ url('/') }}"><i class="fas fa-arrow-left me-1"></i>Back to main site</a>
        </div>
    </div>
</body>
</html>
