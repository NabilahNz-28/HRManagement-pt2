<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — HR Management System</title>
    <meta name="description" content="Sistem Absensi Karyawan & Manajemen SDM Bingxue & Mixue">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Flaticon Uicons --}}
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Leaflet Map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Vite Assets --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Tailwind CDN fallback --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] }
                    }
                }
            }
        </script>
        <style>
            /* Fallback styles jika Vite belum di-build */
            *, *::before, *::after { box-sizing: border-box; }
            body { font-family: 'Inter', sans-serif; background: #F0F4FA; color: #0F1F3D; font-size: 14px; -webkit-font-smoothing: antialiased; }
            .sidebar-wrap { display:flex;flex-direction:column;width:260px;flex-shrink:0;height:100%;background:linear-gradient(180deg,#0B1628,#0F1F3D);border-right:1px solid rgba(255,255,255,0.06);position:relative;z-index:30; }
            .sidebar-logo-area { padding:20px 20px 16px;border-bottom:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;gap:12px; }
            .sidebar-logo-area img { height:40px;width:40px;object-fit:contain;border-radius:10px;background:white;padding:2px; }
            .sidebar-logo-divider { width:1px;height:32px;background:rgba(255,255,255,0.15);flex-shrink:0; }
            .sidebar-brand-text h1 { font-size:13px;font-weight:700;color:#FFFFFF;line-height:1.2; }
            .sidebar-brand-text p { font-size:10.5px;color:rgba(255,255,255,0.45);font-weight:500;margin-top:2px; }
            .sidebar-nav { flex:1;overflow-y:auto;padding:12px 12px 8px;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,0.1) transparent; }
            .sidebar-section-title { font-size:10px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.3);padding:16px 10px 6px; }
            .sidebar-item { display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;font-size:13.5px;font-weight:500;color:rgba(255,255,255,0.6);text-decoration:none;transition:all 0.18s ease;margin-bottom:2px;position:relative;cursor:pointer;width:100%;border:none;background:none;text-align:left; }
            .sidebar-item i { font-size:16px;display:flex;align-items:center;justify-content:center;width:20px;flex-shrink:0;color:rgba(255,255,255,0.45);transition:color 0.18s; }
            .sidebar-item:hover { background:rgba(255,255,255,0.08);color:#FFFFFF; }
            .sidebar-item:hover i { color:#F5A623; }
            .sidebar-item.active { background:rgba(37,99,235,0.25);color:#FFFFFF;font-weight:600; }
            .sidebar-item.active i { color:#F5A623; }
            .sidebar-item.active::before { content:'';position:absolute;left:0;top:6px;bottom:6px;width:3px;background:#F5A623;border-radius:0 3px 3px 0; }
            .sidebar-logout-area { padding:10px 12px 14px;border-top:1px solid rgba(255,255,255,0.08); }
            .btn-sidebar-logout { display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px 16px;background:rgba(220,38,38,0.15);border:1.5px solid rgba(220,38,38,0.35);border-radius:10px;color:#FCA5A5;font-size:13.5px;font-weight:700;cursor:pointer;transition:all 0.18s ease;letter-spacing:0.01em;text-align:center;font-family:'Inter',sans-serif; }
            .btn-sidebar-logout i { font-size:16px;flex-shrink:0; }
            .btn-sidebar-logout:hover { background:rgba(220,38,38,0.9);border-color:#DC2626;color:#FFFFFF;box-shadow:0 4px 16px rgba(220,38,38,0.35);transform:translateY(-1px); }
            .sidebar-user-card { padding:12px 16px 16px;display:flex;align-items:center;gap:12px; }
            .sidebar-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#F5A623,#F7B944);color:#0B1628;font-weight:800;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
            .sidebar-user-info { flex:1;min-width:0; }
            .sidebar-user-name { font-size:12.5px;font-weight:700;color:#FFFFFF;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
            .sidebar-user-role { display:inline-block;margin-top:2px;padding:1.5px 8px;border-radius:4px;font-size:9.5px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase; }
            .role-hr { background:rgba(245,166,35,0.2);color:#F5A623; }
            .role-admin { background:rgba(168,85,247,0.2);color:#C084FC; }
            .role-kary { background:rgba(59,130,246,0.2);color:#93C5FD; }
            .topbar { height:60px;background:#FFFFFF;border-bottom:1.5px solid #DDE4EF;padding:0 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;z-index:20; }
            .topbar-title { font-size:15px;font-weight:800;color:#0F1F3D;letter-spacing:-0.01em; }
            .topbar-subtitle { font-size:11px;color:#7C8DAB;font-weight:500;margin-top:1px; }
            .btn-topbar-logout { display:inline-flex;align-items:center;gap:7px;padding:7px 14px;background:#FEF2F2;border:1.5px solid #FECACA;border-radius:8px;color:#DC2626;font-size:12.5px;font-weight:700;cursor:pointer;transition:all 0.18s;text-decoration:none; }
            .btn-topbar-logout i { font-size:14px; }
            .btn-topbar-logout:hover { background:#DC2626;border-color:#DC2626;color:#FFFFFF; }
            .topbar-clock { display:flex;align-items:center;gap:6px;padding:5px 12px;background:#F0F4FA;border:1px solid #DDE4EF;border-radius:8px;font-size:11.5px;font-weight:600;color:#3B4C6E; }
            .live-dot { width:7px;height:7px;border-radius:50%;background:#22C55E;animation:pulseDot 2s infinite ease-in-out; }
            @keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1);}50%{opacity:.5;transform:scale(1.4);} }
            .topbar-user-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1E3A6E,#2563EB);color:white;font-weight:800;font-size:12px;display:flex;align-items:center;justify-content:center; }
            .logout-modal-overlay { position:fixed;inset:0;background:rgba(5,13,26,0.65);backdrop-filter:blur(4px);z-index:999;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity 0.2s ease; }
            .logout-modal-overlay.show { opacity:1;pointer-events:auto; }
            .logout-modal { background:#FFFFFF;border-radius:16px;padding:32px;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(5,13,26,0.25);transform:scale(0.95);transition:transform 0.2s ease; }
            .logout-modal-overlay.show .logout-modal { transform:scale(1); }
            .logout-modal-icon { width:60px;height:60px;border-radius:50%;background:#FEF2F2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;color:#DC2626; }
            .logout-modal h3 { font-size:17px;font-weight:800;color:#0F1F3D;margin-bottom:8px; }
            .logout-modal p { font-size:13px;color:#7C8DAB;margin-bottom:24px; }
            .btn { display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:9px;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.18s;text-decoration:none;letter-spacing:0.01em;font-family:'Inter',sans-serif; }
            .btn-danger { background:#DC2626;color:white; }
            .btn-danger:hover { background:#B91C1C; }
            .btn-secondary { background:#F0F4FA;color:#3B4C6E;border:1.5px solid #DDE4EF; }
            .btn-secondary:hover { background:#DDE4EF; }
            .alert { display:flex;align-items:flex-start;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:500;margin-bottom:20px; }
            .alert-success { background:#F0FDF4;border:1px solid #BBF7D0;color:#15803D; }
            .alert-error { background:#FEF2F2;border:1px solid #FECACA;color:#DC2626; }
            .alert-close { margin-left:auto;background:none;border:none;cursor:pointer;font-size:16px;line-height:1;opacity:0.6;color:inherit;flex-shrink:0; }
            #sidebar-overlay { display:none;position:fixed;inset:0;background:rgba(5,13,26,0.55);z-index:40;backdrop-filter:blur(3px); }
            #sidebar-overlay.show { display:block; }
            #main-sidebar { transition:transform 0.3s cubic-bezier(0.4,0,0.2,1); }
            @media (max-width:1023px) { #main-sidebar { position:fixed;left:0;top:0;height:100%;z-index:50;transform:translateX(-100%); } #main-sidebar.open { transform:translateX(0); } }
            @media (min-width:1024px) { #main-sidebar { position:relative;transform:none !important; } }
        </style>
    @endif

    @yield('styles')
</head>
<body class="flex h-screen overflow-hidden" style="background:#F0F4FA;">

    {{-- ========== LOGOUT CONFIRMATION MODAL ========== --}}
    <div id="logoutModal" class="logout-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="logoutModalTitle">
        <div class="logout-modal">
            <div class="logout-modal-icon">
                <i class="fi fi-sr-sign-out-alt"></i>
            </div>
            <h3 id="logoutModalTitle">Konfirmasi Keluar</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem HR Management?</p>
            <div style="display:flex;gap:10px;justify-content:center;">
                <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()" id="cancelLogoutBtn">
                    <i class="fi fi-rr-cross-small"></i> Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" id="logoutFormModal" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" id="confirmLogoutBtn">
                        <i class="fi fi-sr-sign-out-alt"></i> Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- ==================== SIDEBAR ==================== --}}
    <div id="main-sidebar">
        @if(Auth::user()->isHR() || Auth::user()->isAdmin())
            @include('layouts.sidebar-hr')
        @else
            @include('layouts.sidebar-karyawan')
        @endif
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden min-w-0">

        {{-- ===== TOPBAR ===== --}}
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Hamburger (Mobile) --}}
                <button onclick="toggleSidebar()" id="hamburgerBtn"
                    style="display:none;padding:8px;border-radius:8px;border:1.5px solid #DDE4EF;background:#F0F4FA;cursor:pointer;color:#3B4C6E;transition:all 0.18s;"
                    class="lg-hidden">
                    <i class="fi fi-rr-bars-sort" style="font-size:18px;display:flex;"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('header-title', 'HR Management System')</div>
                    <div class="topbar-subtitle">@yield('header-subtitle', 'Sistem Terpadu Presensi & Manajemen SDM')</div>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:12px;">
                {{-- Live Clock --}}
                <div class="topbar-clock" id="topbarClockWrap">
                    <span class="live-dot"></span>
                    <i class="fi fi-rr-clock" style="font-size:13px;"></i>
                    <span id="topbarClock">--:--:-- WIB</span>
                </div>
                <div class="flex items-center gap-2 pl-3 md:pl-4 border-l border-slate-200">
                    @if(Auth::user()->getProfilePhotoUrl())
                        <img src="{{ Auth::user()->getProfilePhotoUrl() }}" alt="Foto" class="w-9 h-9 rounded-full object-cover shadow-sm">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="text-left hidden md:block">
                        <div class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-slate-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                {{-- LOGOUT BUTTON TOPBAR — Jelas & Menonjol --}}
                <button type="button" onclick="openLogoutModal()" class="btn-topbar-logout" id="topbarLogoutBtn" title="Keluar dari sistem">
                    <i class="fi fi-sr-sign-out-alt"></i>
                    <span style="display:none;" class="md-show">Keluar</span>
                </button>
            </div>
        </header>

        {{-- Main View Body --}}
        <main style="flex:1;overflow-y:auto;padding:24px 28px;background:#F0F4FA;">

            @if(session('success'))
                <div class="alert alert-success" id="alertSuccess">
                    <i class="fi fi-rr-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" id="alertError">
                    <i class="fi fi-rr-exclamation"></i>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" id="alertValidation">
                    <i class="fi fi-rr-exclamation"></i>
                    <div>
                        <strong>Ada kesalahan input:</strong>
                        <ul style="margin-top:4px;padding-left:16px;font-size:12.5px;">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="alert-close">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Logout Confirmation Modal --}}
    <div id="logoutModal" class="fixed inset-0 z-[9999] hidden items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300" onclick="hideLogoutModal()" id="logoutBackdrop"></div>
        {{-- Modal Card --}}
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 sm:p-8 w-[90%] max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="logoutCard">
            <div class="flex flex-col items-center text-center">
                {{-- Icon --}}
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4">
                    <i class="fi fi-rr-sign-out-alt text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Yakin ingin keluar?</h3>
                <p class="text-sm text-slate-500 mb-6">Sesi login Anda akan diakhiri dan harus login ulang untuk mengakses sistem.</p>
                {{-- Buttons --}}
                <div class="flex items-center gap-3 w-full">
                    <button type="button" onclick="hideLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-sm font-semibold text-slate-600 transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="button" onclick="confirmLogout()" class="flex-1 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all cursor-pointer">
                        Ya, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== CLOCK =====
        function updateLiveClock() {
            const el = document.getElementById('topbarClock');
            if (!el) return;
            const now = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const s = String(now.getSeconds()).padStart(2,'0');
            el.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} — ${h}:${m}:${s} WIB`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // ===== SIDEBAR =====
        function toggleSidebar() {
            document.getElementById('main-sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('main-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
        }

        // ===== LOGOUT MODAL =====
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('show');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');
        }
        // Close on backdrop click
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
        });
        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogoutModal();
        });

        // ===== RESPONSIVE =====
        function handleResize() {
            const hamburger = document.getElementById('hamburgerBtn');
            const mdShows = document.querySelectorAll('.md-show');
            if (window.innerWidth < 1024) {
                hamburger.style.display = 'flex';
            } else {
                hamburger.style.display = 'none';
            }
            if (window.innerWidth >= 768) {
                mdShows.forEach(el => el.style.display = 'block');
            } else {
                mdShows.forEach(el => el.style.display = 'none');
            }
        }
        window.addEventListener('resize', handleResize);
        handleResize();

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#main-sidebar .sidebar-item').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) closeSidebar();
                });
            });
        });

        // Logout confirmation modal
        let _logoutFormId = null;

        function showLogoutModal(formId) {
            _logoutFormId = formId;
            const modal = document.getElementById('logoutModal');
            const card = document.getElementById('logoutCard');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Animate in
            requestAnimationFrame(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const card = document.getElementById('logoutCard');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
            _logoutFormId = null;
        }

        function confirmLogout() {
            if (_logoutFormId) {
                document.getElementById(_logoutFormId).submit();
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
