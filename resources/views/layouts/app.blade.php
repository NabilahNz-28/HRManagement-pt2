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
                            blue: '#1E40AF', 'blue-light': '#3B82F6', 'blue-bg': '#EFF6FF',
                            orange: '#FF6B00', 'orange-light': '#FF8C40', 'orange-bg': '#FFF4EC',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; color: #0F172A; font-size: 15px; }

        /* ---- Sidebar Items ---- */
        .sidebar-item {
            display: flex; align-items: center; gap: 14px;
            padding: 11px 16px; border-radius: 12px;
            font-size: 14.5px; font-weight: 500; color: #475569;
            transition: all 0.2s ease; position: relative;
            text-decoration: none; margin-bottom: 2px;
        }
        .sidebar-item:hover { color: #1E40AF; background-color: #F1F5F9; }
        .sidebar-item.active { background-color: #EFF6FF; color: #1D4ED8; font-weight: 600; }
        .sidebar-item.active::before {
            content: ''; position: absolute; left: 0; top: 6px; bottom: 6px;
            width: 4.5px; background-color: #2563EB; border-radius: 0 4px 4px 0;
        }
        .sidebar-item i { font-size: 18px; display: flex; align-items: center; justify-content: center; width: 22px; }
        .sidebar-item.active i { color: #2563EB; }
        .sidebar-section-title {
            font-size: 11.5px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #94A3B8; padding: 14px 16px 6px;
        }

        /* ---- Cards & Inputs ---- */
        .card-white { background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.04); }
        .form-input {
            width: 100%; padding: 11px 14px; border: 1.5px solid #CBD5E1;
            border-radius: 10px; font-size: 14px; background: #FFFFFF; color: #0F172A;
            outline: none; transition: all 0.2s;
        }
        .form-input:focus { border-color: #2563EB; box-shadow: 0 0 0 3.5px rgba(37,99,235,0.12); }
        .form-label { display: block; font-size: 13.5px; font-weight: 600; color: #334155; margin-bottom: 6px; }

        /* ---- Buttons ---- */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; background: #2563EB; color: white;
            font-weight: 700; font-size: 13.5px; border-radius: 12px;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-primary:hover { background: #1D4ED8; }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 18px; background: #F1F5F9; color: #475569;
            font-weight: 600; font-size: 13.5px; border-radius: 12px;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-secondary:hover { background: #E2E8F0; }

        /* ---- Badges ---- */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 999px; font-size: 12.5px; font-weight: 600; }
        .badge-success { background-color: #DCFCE7; color: #15803D; }
        .badge-warning { background-color: #FEF3C7; color: #B45309; }
        .badge-danger  { background-color: #FEE2E2; color: #B91C1C; }
        .badge-info    { background-color: #E0E7FF; color: #4338CA; }
        .badge-purple  { background-color: #F3E8FF; color: #7E22CE; }

        /* ---- Pulse ---- */
        @keyframes pulseDot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.4;transform:scale(1.3);} }
        .live-dot { animation: pulseDot 2s infinite ease-in-out; }

        /* ---- Mobile Sidebar ---- */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); z-index: 40; backdrop-filter: blur(2px);
        }
        #sidebar-overlay.show { display: block; }

        #main-sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1); }

        @media (max-width: 1023px) {
            #main-sidebar {
                position: fixed; left: 0; top: 0; height: 100%;
                z-index: 50; transform: translateX(-100%);
            }
            #main-sidebar.open { transform: translateX(0); }
        }
        @media (min-width: 1024px) {
            #main-sidebar { position: relative; transform: none !important; }
        }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    </style>
    @yield('styles')
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden antialiased text-slate-800">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- ==================== SIDEBAR ==================== --}}
    <div id="main-sidebar" class="w-72 lg:w-64 flex-shrink-0 h-full">
        @if(Auth::user()->isHR() || Auth::user()->isAdmin())
            @include('layouts.sidebar-hr')
        @else
            @include('layouts.sidebar-karyawan')
        @endif
    </div>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden min-w-0">
        
        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 h-16 px-4 md:px-8 flex items-center justify-between flex-shrink-0 z-20">
            <div class="flex items-center gap-3">
                {{-- Hamburger (Mobile only) --}}
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                    <i class="fi fi-rr-bars-sort text-xl" style="display:flex"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-base md:text-lg font-bold text-slate-900">@yield('header-title', 'HR & Absensi System')</h2>
                    <p class="text-xs text-slate-500">@yield('header-subtitle', 'Sistem Terpadu Presensi & Manajemen SDM')</p>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
                <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 live-dot"></span>
                    <i class="fi fi-rr-clock text-slate-400"></i>
                    <span id="topbarClock">--:--:-- WIB</span>
                </div>
                <div class="flex items-center gap-2 pl-3 md:pl-4 border-l border-slate-200">
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
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5"><i class="fi fi-rr-check text-emerald-600 font-bold"></i><span class="font-medium">{{ session('success') }}</span></div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg leading-none cursor-pointer">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5"><i class="fi fi-rr-exclamation text-red-600 font-bold"></i><span class="font-medium">{{ session('error') }}</span></div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 text-lg leading-none cursor-pointer">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm">
                    <div class="font-semibold mb-1 flex items-center gap-2"><i class="fi fi-rr-exclamation text-red-600"></i> Ada beberapa kesalahan input:</div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 ml-1">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
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

        function toggleSidebar() {
            document.getElementById('main-sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('main-sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('show');
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#main-sidebar .sidebar-item').forEach(link => {
                link.addEventListener('click', function() { if (window.innerWidth < 1024) closeSidebar(); });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
