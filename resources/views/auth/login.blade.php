<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — BlockCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
        input[type=email], input[type=password] {
            width:100%; box-sizing:border-box;
            border:1.5px solid #e5e7eb; border-radius:10px;
            padding:11px 14px; font-size:15px; color:#111827;
            outline:none; transition:border-color .15s,box-shadow .15s;
            font-family:inherit;
        }
        input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.15); }
        input.error { border-color:#ef4444; }
        .field { margin-bottom:20px; }
        .error-msg { font-size:12px; color:#ef4444; margin-top:5px; }
        .btn {
            width:100%; padding:13px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            color:#fff; font-size:15px; font-weight:700;
            border:none; border-radius:10px; cursor:pointer;
            box-shadow:0 4px 16px rgba(99,102,241,.4);
            transition:transform .15s,box-shadow .15s;
        }
        .btn:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(99,102,241,.5); }
        .btn:active { transform:translateY(0); }
        .remember { display:flex; align-items:center; gap:8px; margin-bottom:24px; font-size:13px; color:#6b7280; }
        .remember input[type=checkbox] { width:16px; height:16px; accent-color:#6366f1; }
    </style>
</head>
<body>
<div class="card">
    {{-- Logo --}}
    <div class="logo-ring">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
    </div>

    <h1 class="text-center text-2xl font-extrabold text-gray-900 mb-1">BlockCraft</h1>
    <p class="text-center text-sm text-gray-400 mb-8">Sign in to manage your pages</p>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-5 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="admin@blockcraft.test"
                   autocomplete="email" autofocus
                   class="{{ $errors->has('email') ? 'error' : '' }}">
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   autocomplete="current-password">
        </div>

        <div class="remember">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0;font-weight:400">Remember me for 30 days</label>
        </div>

        <button type="submit" class="btn">Sign in →</button>
    </form>

    <p class="text-center text-xs text-gray-400 mt-6">
        Demo credentials: <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">admin@blockcraft.test</code> / <code class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">password</code>
    </p>
</div>
</body>
</html>
