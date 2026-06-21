<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — BlockCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        .card {
            background: rgba(255,255,255,.96);
            border-radius: 24px;
            padding: 48px 44px;
            width: 100%; max-width: 420px;
            box-shadow: 0 32px 80px rgba(0,0,0,.35);
        }
        .logo-ring {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(99,102,241,.4);
        }
        .btn-custom {
            width:100%; padding:13px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            color:#fff; font-size:15px; font-weight:700;
            border:none; border-radius:10px; cursor:pointer;
            box-shadow:0 4px 16px rgba(99,102,241,.4);
            transition:transform .15s,box-shadow .15s;
        }
        .btn-custom:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(99,102,241,.5); color:#fff; }
        .btn-custom:active { transform:translateY(0); }
    </style>
</head>
<body>
<div class="card">
    {{-- Logo --}}
    <div class="logo-ring">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" class="text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
    </div>

    <h1 class="text-center fs-4 fw-bold text-dark mb-1">BlockCraft</h1>
    <p class="text-center small text-secondary mb-4">Sign in to manage your pages</p>

    @if($errors->any())
        <div class="alert alert-danger p-3 mb-4 small">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small mb-1">Email address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="admin@blockcraft.test"
                   autocomplete="email" autofocus
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold small mb-1">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   autocomplete="current-password"
                   class="form-control">
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label text-secondary small" for="remember">
                Remember me for 30 days
            </label>
        </div>

        <button type="submit" class="btn-custom">Sign in &rarr;</button>
    </form>

    <p class="text-center small text-secondary mt-4 mb-0">
        Demo credentials: <code class="bg-light px-1 py-1 rounded text-secondary">admin@blockcraft.test</code> / <code class="bg-light px-1 py-1 rounded text-secondary">password</code>
    </p>
</div>
</body>
</html>
