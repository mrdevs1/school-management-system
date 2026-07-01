<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন — বিদ্যাপীঠ IMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Hind Siliguri', sans-serif; }
        body { background: linear-gradient(135deg, #0a2e22 0%, #0f7a55 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 16px; padding: 40px; width: 100%; max-width: 420px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); }
        .brand-icon { width: 64px; height: 64px; background: linear-gradient(135deg, #0f7a55, #059669); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 16px; }
        .brand-title { font-size: 22px; font-weight: 700; color: #0a2e22; text-align: center; margin-bottom: 4px; }
        .brand-sub { font-size: 13px; color: #6b7f78; text-align: center; margin-bottom: 28px; }
        .form-label { font-size: 13px; font-weight: 600; color: #1a2e28; margin-bottom: 6px; }
        .form-control { border: 1.5px solid #e0ebe7; border-radius: 8px; padding: 10px 14px; font-size: 14px; font-family: 'Hind Siliguri', sans-serif; }
        .form-control:focus { border-color: #0f7a55; box-shadow: 0 0 0 3px rgba(15,122,85,0.1); }
        .btn-login { background: linear-gradient(135deg, #0f7a55, #059669); color: #fff; border: none; border-radius: 8px; padding: 11px; font-size: 15px; font-weight: 600; width: 100%; font-family: 'Hind Siliguri', sans-serif; transition: all 0.2s; }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(15,122,85,0.4); }
        .alert-danger { background: #fee2e2; border: none; border-left: 4px solid #dc2626; color: #dc2626; border-radius: 8px; font-size: 13.5px; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="brand-icon">🕌</div>
    <div class="brand-title">{{ config('school.name', 'বিদ্যাপীঠ IMS') }}</div>
    <div class="brand-sub">Institutional Management System</div>

    @if($errors->any())
    <div class="alert alert-danger mb-3">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        ইমেইল বা পাসওয়ার্ড সঠিক নয়!
    </div>
    @endif

    @if (session('status'))
    <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">ইমেইল ঠিকানা</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@school.com">
        </div>
        <div class="mb-4">
            <label class="form-label">পাসওয়ার্ড</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember" style="font-size:13px;">মনে রাখুন</label>
        </div>
        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i> লগইন করুন
        </button>
    </form>

    <div style="text-align:center; margin-top:20px; font-size:12px; color:#aaa;">
        © {{ date('Y') }} বিদ্যাপীঠ IMS — সর্বস্বত্ব সংরক্ষিত
    </div>
</div>
</body>
</html>
