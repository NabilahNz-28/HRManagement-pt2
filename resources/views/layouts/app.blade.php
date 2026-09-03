<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — Absensi Karyawan</title>
    <meta name="description" content="Sistem Absensi Karyawan & Manajemen SDM Bingxue & Mixue">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Flaticon Uicons --}}
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css">

    {{-- Tailwind CSS CDN & Chart.js --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Leaflet Map CSS/JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            blue: '#1E40AF',
                            'blue-light': '#3B82F6',
                            'blue-bg': '#EFF6FF',
                            orange: '#FF6B00',
                            'orange-light': '#FF8C40',
                            'orange-bg': '#FFF4EC',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #F8FAFC; 
            color: #0F172A; 
            font-size: 15px; /* Font agak dibesarkan */
        }

        /* ---- Sidebar Item Custom ---- */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 14.5px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
            margin-bottom: 2px;
        }
        .sidebar-item:hover {
            color: #1E40AF;
            background-color: #F1F5F9;
        }
        .sidebar-item.active {
            background-color: #EFF6FF;
            color: #1D4ED8;
            font-weight: 600;
        }
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 4.5px;
            background-color: #2563EB;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-item i {
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
        }
        .sidebar-item.active i {
            color: #2563EB;
        }

        .sidebar-section-title {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94A3B8;
            padding: 14px 16px 6px;
        }

        /* ---- Cards & Inputs ---- */
        .card-white {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
        }
        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #CBD5E1;
            border-radius: 10px;
            font-size: 14px;
            background: #FFFFFF;
            color: #0F172A;
            outline: none;
            transition: all 0.2s;
        }
        .form-input:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
        }
        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        /* ---- Badges ---- */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
        }
        .badge-success { background-color: #DCFCE7; color: #15803D; }
        .badge-warning { background-color: #FEF3C7; color: #B45309; }
        .badge-danger  { background-color: #FEE2E2; color: #B91C1C; }
        .badge-info    { background-color: #E0E7FF; color: #4338CA; }
        .badge-purple  { background-color: #F3E8FF; color: #7E22CE; }

        /* ---- Pulse Animation ---- */
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.3); }
        }
        .live-dot {
            animation: pulseDot 2s infinite ease-in-out;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden antialiased text-slate-800">

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col flex-shrink-0 z-30 h-full">
        
        {{-- Header / Logo --}}
        <div class="px-5 py-5 border-b border-slate-100 flex items-center gap-3.5">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/bingxue.png') }}" alt="Bingxue" class="h-9 w-auto object-contain">
                <span class="h-5 w-px bg-slate-200"></span>
                <img src="{{ asset('img/mixue.png') }}" alt="Mixue" class="h-9 w-auto object-contain">
            </div>
            <div>
                <h1 class="font-bold text-[15px] text-slate-900 leading-tight">Absensi Karyawan</h1>
                <p class="text-xs text-slate-400 font-medium">Bingxue & Mixue</p>
            </div>
        </div>

        {{-- Nav Menu --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            
            {{-- DASHBOARD --}}
            <div class="sidebar-section-title">Dashboard</div>
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fi fi-ts-people-poll"></i>
                <span>Dashboard</span>
            </a>

            {{-- ABSENSI --}}
            <div class="sidebar-section-title">Absensi</div>
            <a href="{{ route('absensi.masuk') }}" class="sidebar-item {{ request()->routeIs('absensi.masuk') ? 'active' : '' }}">
                <i class="fi fi-rr-clock"></i>
                <span>Absensi Masuk</span>
            </a>
            <a href="{{ route('absensi.pulang') }}" class="sidebar-item {{ request()->routeIs('absensi.pulang') ? 'active' : '' }}">
                <i class="fi fi-rr-time-forward"></i>
                <span>Absensi Pulang</span>
            </a>
            <a href="{{ route('cuti.izin') }}" class="sidebar-item {{ request()->routeIs('cuti.izin') ? 'active' : '' }}">
                <i class="fi fi-rr-calendar"></i>
                <span>Pengajuan Izin</span>
            </a>
            <a href="{{ route('cuti.cuti') }}" class="sidebar-item {{ request()->routeIs('cuti.cuti') ? 'active' : '' }}">
                <i class="fi fi-rr-plus"></i>
                <span>Pengajuan Cuti</span>
            </a>

            {{-- MONITORING --}}
            <div class="sidebar-section-title">Monitoring</div>
            <a href="{{ route('monitoring.harian') }}" class="sidebar-item {{ request()->routeIs('monitoring.harian') ? 'active' : '' }}">
                <i class="fi fi-rr-calendar-lines"></i>
                <span>Rekap Harian</span>
            </a>
            <a href="{{ route('monitoring.bulanan') }}" class="sidebar-item {{ request()->routeIs('monitoring.bulanan') ? 'active' : '' }}">
                <i class="fi fi-rr-bars-progress"></i>
                <span>Rekap Bulanan</span>
            </a>

            {{-- LAPORAN --}}
            <div class="sidebar-section-title">Laporan</div>
            <a href="{{ route('cuti.riwayat') }}" class="sidebar-item {{ request()->routeIs('cuti.riwayat') ? 'active' : '' }}">
                <i class="fi fi-rr-document"></i>
                <span>Riwayat Izin & Cuti</span>
            </a>

            {{-- MANAJEMEN HR (Khusus HR & Admin) --}}
            @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                <div class="sidebar-section-title text-orange-600">Manajemen HR</div>
                <a href="{{ route('karyawan.index') }}" class="sidebar-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                    <i class="fi fi-ts-people-poll"></i>
                    <span>Data Karyawan</span>
                </a>
            @endif

            {{-- PENGATURAN --}}
            <div class="sidebar-section-title">Pengaturan</div>
            <a href="{{ route('profile') }}" class="sidebar-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="fi fi-rr-user"></i>
                <span>Profile</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                @csrf
                <button type="submit" class="sidebar-item text-red-500 hover:bg-red-50 hover:text-red-600 w-full text-left cursor-pointer">
                    <i class="fi fi-rr-sign-out-alt text-red-500"></i>
                    <span>Logout</span>
                </button>
            </form>

        </nav>

        {{-- User info footer --}}
        <div class="p-3 border-t border-slate-100 bg-slate-50/70">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700' : (Auth::user()->isHR() ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ strtoupper(Auth::user()->role) }}
                    </span>
                </div>
            </div>
        </div>

    </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 h-16 px-8 flex items-center justify-between flex-shrink-0 z-20">
            <div>
                <h2 class="text-lg font-bold text-slate-900">@yield('header-title', 'HR & Absensi System')</h2>
                <p class="text-xs text-slate-500">@yield('header-subtitle', 'Sistem Terpadu Presensi & Manajemen SDM')</p>
            </div>

            <div class="flex items-center gap-5">
                {{-- Live Time & Date --}}
                <div class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 live-dot"></span>
                    <i class="fi fi-rr-clock text-slate-400"></i>
                    <span id="topbarClock">--:--:-- WIB</span>
                </div>

                {{-- User Badge --}}
                <div class="flex items-center gap-2 pl-4 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="text-left hidden md:block">
                        <div class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-slate-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main View Body --}}
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <i class="fi fi-rr-check text-emerald-600 font-bold"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg leading-none cursor-pointer">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5">
                        <i class="fi fi-rr-exclamation text-red-600 font-bold"></i>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-lg leading-none cursor-pointer">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm">
                    <div class="font-semibold mb-1 flex items-center gap-2">
                        <i class="fi fi-rr-exclamation text-red-600"></i> Ada beberapa kesalahan input:
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 ml-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

    {{-- Live Clock Script --}}
    <script>
        function updateLiveClock() {
            const el = document.getElementById('topbarClock');
            if (!el) return;
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');

            el.textContent = `${dayName}, ${date} ${monthName} ${year} — ${h}:${m}:${s} WIB`;
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
    @yield('scripts')
</body>
</html>
