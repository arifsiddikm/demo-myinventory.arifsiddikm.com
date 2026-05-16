<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MyInventory Login - Sistem Informasi Manajemen Inventaris">
    <meta property="og:title" content="Login — MyInventory">
    <title>Login — MyInventory</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }
        body {
            background: #04051a;
            min-height: 100vh;
            overflow: hidden;
        }
        /* Animated grid bg */
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);
            background-size: 44px 44px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0%   { background-position: 0 0; }
            100% { background-position: 44px 44px; }
        }
        /* Glow orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(99,102,241,0.15); top: -150px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: rgba(139,92,246,0.12); bottom: -100px; right: -80px; animation-delay: -4s; }
        .orb-3 { width: 300px; height: 300px; background: rgba(59,130,246,0.10); top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: -2s; }
        @keyframes orbFloat {
            0%, 100% { transform: scale(1) translate(0,0); }
            50%       { transform: scale(1.1) translate(20px, -20px); }
        }
        /* Card */
        .login-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 0 0 1px rgba(99,102,241,0.15), 0 32px 64px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.06);
        }
        /* Input */
        .inp {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
            transition: all 0.3s;
        }
        .inp:focus {
            outline: none;
            border-color: rgba(99,102,241,0.6);
            background: rgba(99,102,241,0.08);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15), 0 0 20px rgba(99,102,241,0.1);
        }
        .inp::placeholder { color: rgba(148,163,184,0.5); }
        /* Button */
        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 0 30px rgba(99,102,241,0.4), 0 4px 15px rgba(99,102,241,0.3);
            transition: all 0.3s;
        }
        .btn-login:hover {
            box-shadow: 0 0 50px rgba(99,102,241,0.6), 0 4px 20px rgba(99,102,241,0.5);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }
        /* Scan line */
        .scan-line {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.8), transparent);
            animation: scanDown 3s linear infinite;
        }
        @keyframes scanDown {
            0%   { top: 0; opacity: 1; }
            80%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        /* Logo pulse */
        .logo-glow {
            box-shadow: 0 0 0 0 rgba(99,102,241,0.4);
            animation: logoPulse 2s ease-out infinite;
        }
        @keyframes logoPulse {
            0%   { box-shadow: 0 0 0 0 rgba(99,102,241,0.5); }
            70%  { box-shadow: 0 0 0 14px rgba(99,102,241,0); }
            100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
        }
        /* Mono text */
        .mono { font-family: 'JetBrains Mono', monospace; }
        /* Fade in */
        .fade-up { animation: fadeUp 0.6s ease both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="grid-bg"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Card -->
        <div class="login-card rounded-2xl p-8 relative overflow-hidden fade-up">
            <div class="scan-line"></div>

            <!-- Header -->
            <div class="text-center mb-8 fade-up delay-1">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-600 logo-glow mb-4" style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="none">
                        <path d="M3 3h6v6H3zM11 3h6v6h-6zM3 11h6v6H3zM11 11h6v6h-6z" fill="white" opacity="0.9"/>
                        <path d="M5 5h2v2H5zM13 5h2v2h-2zM5 13h2v2H5zM13 13h2v2h-2z" fill="white" opacity="0.5"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">MyInventory</h1>
                <p class="mono text-xs text-indigo-400 mt-1 tracking-widest">INVENTORY MANAGEMENT SYSTEM</p>
            </div>

            <!-- Errors -->
            @if($errors->any())
            <div class="mb-5 px-4 py-3 bg-red-500/10 border border-red-500/20 rounded-xl fade-up delay-1">
                <p class="text-sm text-red-400 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $errors->first() }}
                </p>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4 fade-up delay-2">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}"
                           placeholder="admin@mail.com"
                           class="inp w-full px-4 py-3 rounded-xl text-sm"
                           required autocomplete="email">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               placeholder="••••••••"
                               class="inp w-full px-4 py-3 rounded-xl text-sm pr-11"
                               required>
                        <button type="button" onclick="togglePwd()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-indigo-500 focus:ring-indigo-500">
                        <span class="text-sm text-slate-400">Ingat saya</span>
                    </label>
                </div>
                <button type="submit"
                        class="btn-login w-full py-3 px-6 rounded-xl text-white font-semibold text-sm tracking-wide mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Auto-fill admin button -->
            <div class="mt-5 fade-up delay-3">
                <button onclick="autofillAdmin()"
                        class="w-full py-2.5 px-4 rounded-xl border border-indigo-500/30 text-indigo-400 text-xs font-medium hover:bg-indigo-500/10 hover:border-indigo-500/50 transition-all duration-200 mono tracking-wider flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/></svg>
                    AUTO FILL ADMIN
                </button>
            </div>

            <!-- Footer info -->
            <p class="mono text-center text-xs text-slate-600 mt-6">
                v1.0.0 · © {{ date('Y') }} MyInventory
            </p>
        </div>
    </div>

    <script>
        function autofillAdmin() {
            document.getElementById('email').value = 'admin@mail.com';
            document.getElementById('password').value = 'password';
            // Flash effect
            [document.getElementById('email'), document.getElementById('password')].forEach(el => {
                el.style.boxShadow = '0 0 20px rgba(99,102,241,0.5)';
                el.style.borderColor = 'rgba(99,102,241,0.8)';
                setTimeout(() => { el.style.boxShadow = ''; el.style.borderColor = ''; }, 800);
            });
        }

        function togglePwd() {
            const inp = document.getElementById('password');
            inp.type = inp.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>