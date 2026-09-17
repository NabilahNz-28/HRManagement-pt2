{{-- ==================== SIDEBAR HR / ADMIN ==================== --}}
<aside class="sidebar-wrap">

    {{-- Header / Logo --}}
    <div class="sidebar-logo-area">
        <img src="{{ asset('img/bingxue.png') }}" alt="Bingxue">
        <div class="sidebar-logo-divider"></div>
        <img src="{{ asset('img/mixue1.jpg') }}" alt="Mixue">
        <div class="sidebar-brand-text" style="margin-left:2px;">
            <h1>Bingxue & Mixue</h1>
            <p>HR Management System</p>
        </div>
    </div>

    {{-- Nav Menu --}}
    <nav class="sidebar-nav">

        {{-- OVERVIEW --}}
        <div class="sidebar-section-title">Overview</div>
        <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fi fi-rr-home"></i>
            <span>Dashboard</span>
        </a>

        {{-- MONITORING & REKAP --}}
        <div class="sidebar-section-title">Monitoring</div>
        <a href="{{ route('monitoring.harian') }}" class="sidebar-item {{ request()->routeIs('monitoring.harian') ? 'active' : '' }}">
            <i class="fi fi-rr-calendar-lines"></i>
            <span>Rekap Harian</span>
        </a>
        <a href="{{ route('monitoring.bulanan') }}" class="sidebar-item {{ request()->routeIs('monitoring.bulanan') ? 'active' : '' }}">
            <i class="fi fi-rr-chart-histogram"></i>
            <span>Rekap Bulanan</span>
        </a>

        {{-- MANAJEMEN KARYAWAN --}}
        <div class="sidebar-section-title">Manajemen SDM</div>
        <a href="{{ route('karyawan.index') }}" class="sidebar-item {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
            <i class="fi fi-rr-users"></i>
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('payroll.index') }}" class="sidebar-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
            <i class="fi fi-rr-money-bill-wave"></i>
            <span>Payroll & Gaji</span>
        </a>

        {{-- PERSETUJUAN CUTI / IZIN --}}
        <div class="sidebar-section-title">Izin & Cuti</div>
        <a href="{{ route('cuti.riwayat') }}" class="sidebar-item {{ request()->routeIs('cuti.riwayat') ? 'active' : '' }}">
            <i class="fi fi-rr-file-check"></i>
            <span>Kelola & Riwayat Cuti</span>
        </a>

        {{-- LAPORAN --}}
        <div class="sidebar-section-title">Laporan</div>
        <a href="{{ route('monitoring.export') }}" class="sidebar-item {{ request()->routeIs('monitoring.export') ? 'active' : '' }}">
            <i class="fi fi-rr-file-export"></i>
            <span>Export Data CSV</span>
        </a>

        {{-- PENGATURAN --}}
        <div class="sidebar-section-title">Pengaturan</div>
        <a href="{{ route('profile') }}" class="sidebar-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <i class="fi fi-rr-user"></i>
            <span>Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" id="logoutFormHR">
            @csrf
        </form>
        <button type="button" onclick="showLogoutModal('logoutFormHR')" class="sidebar-item text-red-500 hover:bg-red-50 hover:text-red-600 w-full text-left cursor-pointer">
            <i class="fi fi-rr-sign-out-alt text-red-500"></i>
            <span>Logout</span>
        </button>

    </nav>

    {{-- User info footer --}}
    <div class="p-3 border-t border-slate-100 bg-slate-50/70">
        <div class="flex items-center gap-3 px-2 py-1">
            @if(Auth::user()->getProfilePhotoUrl())
                <img src="{{ Auth::user()->getProfilePhotoUrl() }}" alt="Foto" class="w-9 h-9 rounded-full object-cover shadow-sm">
            @else
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700' }}">
                    {{ strtoupper(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </div>

</aside>
