<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — HR Management System</title>
    <meta name="description" content="Login ke sistem HR Management. Absensi karyawan, manajemen cuti, dan laporan kehadiran.">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Flaticon Uicons --}}
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">

    {{-- Tailwind via CDN (fallback jika vite belum build) --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                        colors: {
                            brand: {
                                orange: '#FF6B00',
                                'orange-light': '#FF8C40',
                                'orange-bg': '#FFF4EC',
                                blue: '#1E40AF',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }

        /* ---- Background gradient ---- */
        .login-bg {
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0ff 30%, #fef3ec 70%, #fff8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .login-bg::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,107,0,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .login-bg::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(30,64,175,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* ---- Card ---- */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px 36px;
            position: relative;
            z-index: 10;
        }

        /* ---- Logo container ---- */
        .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 28px;
        }
        .logo-divider {
            width: 1.5px;
            height: 44px;
            background: linear-gradient(to bottom, transparent, #e5e7eb, transparent);
        }
        .logo-img {
            object-fit: contain;
            transition: transform 0.2s;
        }
        .logo-img:hover { transform: scale(1.04); }

        /* ---- Input group ---- */
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            font-size: 16px;
            pointer-events: none;
            display: flex; align-items: center;
        }
        .input-field {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s;
            outline: none;
        }
        .input-field:focus {
            border-color: #FF6B00;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(255,107,0,0.10);
        }
        .input-field::placeholder { color: #9ca3af; }
        .input-field.has-right { padding-right: 42px; }
        .input-right-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 4px;
            display: flex; align-items: center;
            transition: color 0.2s;
            font-size: 16px;
        }
        .input-right-btn:hover { color: #FF6B00; }

        /* ---- Label ---- */
        .field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        /* ---- Submit Button ---- */
        .btn-login {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: white;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 16px rgba(29,78,216,0.25);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            box-shadow: 0 6px 24px rgba(29,78,216,0.35);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(29,78,216,0.2); }

        /* ---- Checkbox custom ---- */
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.15s;
            background: #f9fafb;
        }
        .custom-checkbox:checked {
            background: #FF6B00;
            border-color: #FF6B00;
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 2px; left: 4px;
            width: 5px; height: 8px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(45deg);
        }

        /* ---- Alert error ---- */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        /* ---- Decorative badge ---- */
        .hr-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #fff4ec, #fff);
            border: 1px solid #fed7aa;
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #FF6B00;
            margin-bottom: 16px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* ---- Features strip ---- */
        .feature-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }
        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }
        .feature-chip i { font-size: 12px; color: #FF6B00; }

        /* ---- Floating decoration ---- */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
            pointer-events: none;
        }

        /* ---- Password strength bar ---- */
        .form-section { margin-bottom: 18px; }

        /* ---- Animated arrow ---- */
        @keyframes arrowSlide { 0%,100%{transform:translateX(0)} 50%{transform:translateX(4px)} }
        .arrow-anim { display: inline-block; animation: arrowSlide 1.5s ease-in-out infinite; }
    </style>
</head>
<body>
    <div class="login-bg flex items-center justify-center px-4 py-8">

        {{-- Decorative circles --}}
        <div class="deco-circle" style="width:300px;height:300px;background:radial-gradient(circle,rgba(255,107,0,0.05),transparent);top:-60px;right:10%;"></div>
        <div class="deco-circle" style="width:200px;height:200px;background:radial-gradient(circle,rgba(29,78,216,0.05),transparent);bottom:20px;left:5%;"></div>

        <div class="login-card">

            {{-- Top brand badge --}}
            <div class="text-center">
                <div class="hr-badge">
                    <i class="fi fi-ts-people-poll" style="font-size:11px;"></i>
                    HR Management System
                </div>
            </div>

            {{-- Logo Row: Bingxue + Mixue --}}
            <div class="logo-row">
                <img src="{{ asset('img/bingxue.png') }}"
                     alt="Bingxue Logo"
                     class="logo-img"
                     style="height:52px; width:auto;">
                <div class="logo-divider"></div>
                <img src="{{ asset('img/mixue.png') }}"
                     alt="Mixue Logo"
                     class="logo-img"
                     style="height:52px; width:auto;">
            </div>

            {{-- Title --}}
            <div class="text-center mb-7">
                <h1 class="text-2xl font-bold text-gray-900 mb-1" style="letter-spacing:-0.02em;">Selamat Datang</h1>
                <p class="text-sm text-gray-500">Silakan login ke akun Anda</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert-error mb-4" style="background:#f0fdf4;border-color:#bbf7d0;color:#15803d;">
                    <i class="fi fi-rr-check" style="margin-top:1px;"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert-error mb-4">
                    <i class="fi fi-rr-exclamation" style="margin-top:1px;flex-shrink:0;"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="form-section">
                    <label for="email" class="field-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fi fi-rr-envelope" style="font-size:15px;"></i>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="karyawan@bingxue.id"
                            autocomplete="username"
                            autofocus
                            required
                            class="input-field"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-section">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="field-label" style="margin-bottom:0;">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs font-medium"
                               style="color:#FF6B00; text-decoration:none;"
                               onmouseover="this.style.textDecoration='underline'"
                               onmouseout="this.style.textDecoration='none'">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fi fi-rr-lock" style="font-size:15px;"></i>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••••"
                            autocomplete="current-password"
                            required
                            class="input-field has-right"
                        >
                        <button type="button" class="input-right-btn" id="togglePwd" onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                            <i class="fi fi-rr-eye" id="eyeIcon" style="font-size:15px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" id="remember_me" name="remember" class="custom-checkbox">
                    <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none" style="font-size:13px;">
                        Ingat Saya
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login" id="btnLogin">
                    Login Akses
                    <span class="arrow-anim">→</span>
                </button>
            </form>

            {{-- Footer --}}
            <div class="text-center mt-5">
                <p class="text-xs text-gray-400">
                    Akun karyawan belum terdaftar?
                </p>
                <a href="mailto:hr@bingxue.id"
                   class="text-sm font-semibold mt-0.5 inline-block"
                   style="color:#1d4ed8;"
                   onmouseover="this.style.textDecoration='underline'"
                   onmouseout="this.style.textDecoration='none'">
                    Hubungi HRD di sini
                </a>
            </div>

            {{-- Feature chips --}}
            <div class="feature-chips">
                <span class="feature-chip">
                    <i class="fi fi-ts-chart-line-up"></i> Laporan Realtime
                </span>
                <span class="feature-chip">
                    <i class="fi fi-tr-inbox-in"></i> Absen Masuk
                </span>
                <span class="feature-chip">
                    <i class="fi fi-tr-inbox-out"></i> Absen Pulang
                </span>
                <span class="feature-chip">
                    <i class="fi fi-tr-file-spreadsheet"></i> Export Excel
                </span>
                <span class="feature-chip">
                    <i class="fi fi-ts-people-poll"></i> Manajemen SDM
                </span>
                <span class="feature-chip">
                    <i class="fi fi-tr-rotate-left"></i> Riwayat Absensi
                </span>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fi fi-rr-eye-crossed';
            } else {
                input.type = 'password';
                icon.className = 'fi fi-rr-eye';
            }
        }

        // Loading state on form submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.innerHTML = '<svg class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Memproses...';
            btn.disabled = true;
            btn.style.opacity = '0.85';
        });

        // Input focus animation
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('.input-icon').style.color = '#FF6B00';
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('.input-icon').style.color = '#9ca3af';
            });
        });
    </script>
</body>
</html>
