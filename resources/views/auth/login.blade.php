<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — HR Management System</title>
    <meta name="description" content="Login ke sistem HR Management. Absensi karyawan, manajemen cuti, dan laporan kehadiran.">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Flaticon Uicons --}}
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ====== LEFT PANEL — Corporate Navy ====== */
        .login-left {
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(160deg, #050D1A 0%, #0B1628 50%, #0F1F3D 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px 40px 36px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles */
        .login-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245,166,35,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Logo area */
        .left-logo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }
        .left-logo-row img {
            height: 48px;
            width: 48px;
            object-fit: contain;
            border-radius: 12px;
            background: white;
            padding: 3px;
        }
        .left-logo-divider {
            width: 1px;
            height: 36px;
            background: rgba(255,255,255,0.2);
        }
        .left-brand h1 {
            font-size: 16px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.01em;
        }
        .left-brand p {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
            margin-top: 2px;
        }

        /* Middle content */
        .left-middle {
            position: relative;
            z-index: 1;
        }
        .left-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(245,166,35,0.15);
            border: 1px solid rgba(245,166,35,0.3);
            border-radius: 999px;
            padding: 6px 14px;
            font-size: 11.5px;
            font-weight: 700;
            color: #F5A623;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .left-badge i { font-size: 12px; }
        .left-headline {
            font-size: 30px;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 14px;
        }
        .left-headline span {
            color: #F5A623;
        }
        .left-desc {
            font-size: 13.5px;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
            max-width: 300px;
        }

        /* Feature list */
        .left-features {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 28px;
        }
        .left-feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12.5px;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
        }
        .feature-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #F5A623;
            flex-shrink: 0;
        }

        /* Bottom watermark */
        .left-bottom {
            position: relative;
            z-index: 1;
        }
        .left-bottom-line {
            width: 40px;
            height: 3px;
            background: rgba(245,166,35,0.4);
            border-radius: 2px;
            margin-bottom: 10px;
        }
        .left-copyright {
            font-size: 11px;
            color: rgba(255,255,255,0.25);
            font-weight: 500;
        }

        /* ====== RIGHT PANEL — Login Form ====== */
        .login-right {
            flex: 1;
            background: #F0F4FA;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            position: relative;
        }

        .login-card {
            background: #FFFFFF;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(15,31,61,0.08), 0 1px 6px rgba(15,31,61,0.04);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
            border: 1px solid #DDE4EF;
        }

        /* Card header */
        .login-card-header {
            margin-bottom: 28px;
        }
        .login-card-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #7C8DAB;
            margin-bottom: 8px;
        }
        .login-card-title {
            font-size: 24px;
            font-weight: 900;
            color: #0B1628;
            letter-spacing: -0.02em;
            line-height: 1.1;
            margin-bottom: 6px;
        }
        .login-card-subtitle {
            font-size: 13px;
            color: #7C8DAB;
            font-weight: 400;
        }

        /* Divider */
        .form-divider {
            height: 1.5px;
            background: #EEF2F8;
            margin-bottom: 24px;
        }

        /* Input group */
        .field-group {
            margin-bottom: 18px;
        }
        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #1E3A6E;
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: #9BADC8;
            font-size: 15px;
            display: flex;
            align-items: center;
            pointer-events: none;
            transition: color 0.18s;
        }
        .input-field {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1.5px solid #D1D9E6;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #0F1F3D;
            background: #F8FAFD;
            outline: none;
            transition: all 0.18s;
        }
        .input-field:focus {
            border-color: #1E3A6E;
            background: #FFFFFF;
            box-shadow: 0 0 0 3.5px rgba(30,58,110,0.10);
        }
        .input-field:focus + .input-icon-focus,
        .input-wrap:focus-within .input-icon {
            color: #1E3A6E;
        }
        .input-field::placeholder { color: #A0AEC0; }
        .input-field.has-right { padding-right: 44px; }
        .input-right-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            color: #9BADC8;
            padding: 4px;
            display: flex;
            align-items: center;
            font-size: 15px;
            transition: color 0.18s;
        }
        .input-right-btn:hover { color: #1E3A6E; }

        /* Forgot link */
        .forgot-link {
            font-size: 12px;
            font-weight: 600;
            color: #2563EB;
            text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Remember checkbox */
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 1.5px solid #D1D9E6;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.15s;
            background: #F8FAFD;
        }
        .custom-checkbox:checked {
            background: #1E3A6E;
            border-color: #1E3A6E;
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

        /* ===== LOGIN BUTTON — Sangat Jelas & Formal ===== */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #0B1628, #1E3A6E);
            color: #FFFFFF;
            font-size: 14.5px;
            font-weight: 800;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Inter', sans-serif;
            transition: all 0.22s ease;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 18px rgba(11,22,40,0.3);
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245,166,35,0.18), transparent);
            opacity: 0;
            transition: opacity 0.22s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #0F1F3D, #162849);
            box-shadow: 0 8px 28px rgba(11,22,40,0.4);
            transform: translateY(-2px);
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 3px 10px rgba(11,22,40,0.25);
        }
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .btn-login i { font-size: 16px; }

        /* Gold accent line */
        .gold-accent {
            height: 3px;
            background: linear-gradient(90deg, #F5A623, #FAD07A, transparent);
            border-radius: 2px;
            margin-bottom: 28px;
        }

        /* Alert */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 9px;
            font-size: 12.5px;
            margin-bottom: 18px;
        }
        .alert-box i { font-size: 14px; flex-shrink: 0; margin-top: 1px; }
        .alert-error  { background: #FEF2F2; border: 1px solid #FECACA; color: #DC2626; }
        .alert-success { background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D; }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid #EEF2F8;
        }
        .login-footer p {
            font-size: 12px;
            color: #9BADC8;
        }
        .login-footer a {
            font-size: 12.5px;
            font-weight: 700;
            color: #1E3A6E;
            text-decoration: none;
        }
        .login-footer a:hover { text-decoration: underline; }

        /* Spinner animation */
        @keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
        .spin { animation: spin 0.8s linear infinite; }

        /* Responsive: mobile */
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { padding: 20px 16px; }
            .login-card { padding: 28px 22px; }
        }
    </style>
</head>
<body>

    {{-- LEFT: Corporate Panel --}}
    <div class="login-left">
        {{-- Top logo --}}
        <div class="left-logo-row">
            <img src="{{ asset('img/bingxue.png') }}" alt="Bingxue">
            <div class="left-logo-divider"></div>
            <img src="{{ asset('img/mixue1.jpg') }}" alt="Mixue">
            <div class="left-brand">
                <h1>Bingxue & Mixue</h1>
                <p>HR Management System</p>
            </div>
        </div>

        {{-- Middle: headline --}}
        <div class="left-middle">
            <div class="left-badge">
                <i class="fi fi-sr-shield-check"></i>
                Sistem Terintegrasi
            </div>
            <h2 class="left-headline">
                Kelola SDM<br>Lebih <span>Efisien</span><br>& Profesional
            </h2>
            <p class="left-desc">
                Platform manajemen sumber daya manusia terpadu untuk absensi, payroll, cuti, dan laporan kehadiran karyawan.
            </p>
            <div class="left-features">
                <div class="left-feature-item">
                    <div class="feature-dot"></div>
                    Absensi berbasis lokasi & waktu real-time
                </div>
                <div class="left-feature-item">
                    <div class="feature-dot"></div>
                    Manajemen payroll & penggajian otomatis
                </div>
                <div class="left-feature-item">
                    <div class="feature-dot"></div>
                    Pengajuan & persetujuan cuti digital
                </div>
                <div class="left-feature-item">
                    <div class="feature-dot"></div>
                    Laporan & export data CSV lengkap
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="left-bottom">
            <div class="left-bottom-line"></div>
            <div class="left-copyright">&copy; {{ date('Y') }} Bingxue & Mixue. All rights reserved.</div>
        </div>
    </div>

    {{-- RIGHT: Login Form --}}
    <div class="login-right">
        <div class="login-card">

            {{-- Card Header --}}
            <div class="login-card-header">
                <div class="login-card-eyebrow">
                    <i class="fi fi-sr-shield-check" style="margin-right:5px;color:#F5A623;"></i>
                    Akses Aman
                </div>
                <h1 class="login-card-title">Masuk ke Sistem</h1>
                <p class="login-card-subtitle">Gunakan kredensial akun yang telah diberikan oleh tim HRD.</p>
            </div>

            <div class="gold-accent"></div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="alert-box alert-success">
                    <i class="fi fi-sr-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert-box alert-error">
                    <i class="fi fi-sr-exclamation"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="error-text" style="margin-bottom:2px;">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <label for="email" class="field-label">
                        <i class="fi fi-rr-envelope" style="margin-right:6px;"></i>Alamat Email
                    </label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <i class="fi fi-rr-envelope" id="emailIcon"></i>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@bingxue.id"
                            autocomplete="username"
                            autofocus
                            required
                            class="input-field"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:7px;">
                        <label for="password" class="field-label" style="margin-bottom:0;">
                            <i class="fi fi-rr-lock" style="margin-right:6px;"></i>Password
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Lupa password?
                        </a>
                    </div>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <i class="fi fi-rr-lock" id="passIcon"></i>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="••••••••••••"
                            autocomplete="current-password"
                            required
                            class="input-field has-right"
                        >
                        <button type="button" class="input-right-btn" onclick="togglePassword()" id="togglePwdBtn" title="Tampilkan/sembunyikan password">
                            <i class="fi fi-rr-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div style="display:flex;align-items:center;gap:9px;margin-bottom:24px;">
                    <input type="checkbox" id="remember_me" name="remember" class="custom-checkbox">
                    <label for="remember_me" style="font-size:12.5px;color:#5A6B88;cursor:pointer;font-weight:500;">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                {{-- ===== LOGIN BUTTON — VERY PROMINENT ===== --}}
                <button type="submit" class="btn-login" id="btnLogin">
                    <i class="fi fi-sr-sign-in-alt" id="loginBtnIcon"></i>
                    <span id="loginBtnText">Masuk ke Sistem</span>
                </button>

            </form>

            {{-- Footer --}}
            <div class="login-footer">
                <p>Belum punya akun? Hubungi tim HRD untuk pendaftaran.</p>
                <a href="mailto:hr@bingxue.id" style="margin-top:4px;display:inline-block;">
                    <i class="fi fi-rr-envelope" style="margin-right:4px;"></i>hr@bingxue.id
                </a>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fi fi-rr-eye-crossed';
            } else {
                input.type = 'password';
                icon.className = 'fi fi-rr-eye';
            }
        }

        // Loading state on submit
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn     = document.getElementById('btnLogin');
            const icon    = document.getElementById('loginBtnIcon');
            const text    = document.getElementById('loginBtnText');
            icon.className = 'fi fi-rr-spinner spin';
            text.textContent = 'Memproses...';
            btn.disabled = true;
            btn.style.opacity = '0.8';
        });

        // Input focus: highlight icon
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', () => {
                const icon = input.closest('.input-wrap').querySelector('.input-icon i');
                if (icon) icon.style.color = '#1E3A6E';
            });
            input.addEventListener('blur', () => {
                const icon = input.closest('.input-wrap').querySelector('.input-icon i');
                if (icon) icon.style.color = '#9BADC8';
            });
        });

        // Dynamic Lockout Timer
        @if(session('lockout_seconds'))
            let seconds = {{ session('lockout_seconds') }};
            const errorEl = document.querySelector('.error-text');
            const loginBtn = document.getElementById('btnLogin');
            if (errorEl && loginBtn) {
                loginBtn.disabled = true;
                loginBtn.style.opacity = '0.55';
                const interval = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        errorEl.textContent = "Waktu tunggu selesai. Silakan coba login kembali.";
                        loginBtn.disabled = false;
                        loginBtn.style.opacity = '1';
                        document.getElementById('loginBtnText').textContent = 'Masuk ke Sistem';
                    } else {
                        errorEl.textContent = `Terlalu banyak percobaan. Coba lagi dalam ${seconds} detik.`;
                    }
                }, 1000);
            }
        @endif
    </script>
</body>
</html>
