{{-- ==================== SIDEBAR HR / ADMIN ==================== --}}
<aside class="w-72 lg:w-64 bg-white border-r border-slate-200 flex flex-col flex-shrink-0 z-30 h-full">
    
    {{-- Header / Logo --}}
    <div class="px-4 py-4 border-b border-slate-100">
        <div class="flex items-center gap-2.5 mb-2">
            <img src="{{ asset('img/bingxue.png') }}" alt="Bingxue" class="h-12 w-12 object-contain rounded-xl">
            <div class="h-8 w-px bg-slate-200 flex-shrink-0"></div>
            <img src="{{ asset('img/mixue1.jpg') }}" alt="Mixue" class="h-12 w-12 object-contain rounded-xl">
        </div>
        <h1 class="font-bold text-[14px] text-slate-900 leading-tight">Bingxue & Mixue</h1>
        <p class="text-[11px] text-slate-500 font-medium">HR Management System</p>
    </div>

    {{-- Nav Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
        
        {{-- OVERVIEW --}}
        <div class="sidebar-section-title">Overview</div>
        <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fi fi-ts-people-poll"></i>
            <span>Dashboard</span>
        </a>

        {{-- MONITORING & REKAP --}}
        <div class="sidebar-section-title">Monitoring</div>
        <a href="{{ route('monitoring.harian') }}" class="sidebar-item {{ request()->routeIs('monitoring.harian') ? 'active' : '' }}">
            <i class="fi fi-rr-calendar-lines"></i>
            <span>Rekap Harian</span>
        </a>
        <a href="{{ route('monitoring.bulanan') }}" class="sidebar-item {{ request()->routeIs('monitoring.bulanan') ? 'active' : '' }}">
            <i class="fi fi-rr-bars-progress"></i>
            <span>Rekap Bulanan</span>
        </a>

        {{-- MANAJEMEN KARYAWAN --}}
        <div class="sidebar-section-title">Manajemen SDM</div>
        <a href="{{ route('karyawan.index') }}" class="sidebar-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <i class="fi fi-ts-people-poll"></i>
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('payroll.index') }}" class="sidebar-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <i class="fi fi-rr-money-bill-wave"></i>
            <span>Payroll & Gaji</span>
        </a>

        {{-- PERSETUJUAN CUTI / IZIN --}}
        <div class="sidebar-section-title">Izin & Cuti</div>
        <a href="{{ route('cuti.riwayat') }}" class="sidebar-item {{ request()->routeIs('cuti.riwayat') ? 'active' : '' }}">
            <i class="fi fi-rr-document"></i>
            <span>Kelola & Riwayat Cuti</span>
        </a>

        {{-- LAPORAN --}}
        <div class="sidebar-section-title">Laporan</div>
        <a href="{{ route('monitoring.export') }}" class="sidebar-item {{ request()->routeIs('monitoring.export') ? 'active' : '' }}">
            <i class="fi fi-rr-file-csv"></i>
            <span>Export Data CSV</span>
        </a>

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
            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700' }}">
                    {{ strtoupper(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </div>

</aside>
