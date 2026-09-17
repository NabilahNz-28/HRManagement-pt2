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
            <i class="fi fi-rr-user-pen"></i>
            <span>Profil Saya</span>
        </a>

    </nav>

    {{-- User Info Card --}}
    <div class="sidebar-user-card">
        <div class="sidebar-avatar"
            @if(Auth::user()->isSuperAdmin())
                style="background:linear-gradient(135deg,#7C3AED,#A855F7);"
            @elseif(Auth::user()->isAdmin())
                style="background:linear-gradient(135deg,#F5A623,#F7B944);color:#0B1628;"
            @else
                style="background:linear-gradient(135deg,#F5A623,#F7B944);color:#0B1628;"
            @endif
        >
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <span class="sidebar-user-role"
                @if(Auth::user()->isSuperAdmin())
                    style="background:rgba(167,139,250,0.2);color:#C4B5FD;"
                @elseif(Auth::user()->isAdmin())
                    style="background:rgba(168,85,247,0.2);color:#C084FC;"
                @else
                    style="background:rgba(245,166,35,0.2);color:#F5A623;"
                @endif
            >
                {{ strtoupper(Auth::user()->role) }}
            </span>
        </div>
    </div>

    {{-- ===== LOGOUT BUTTON — PROMINENT & CLEAR ===== --}}
    <div class="sidebar-logout-area">
        <button
            type="button"
            onclick="openLogoutModal()"
            class="btn-sidebar-logout"
            id="sidebarLogoutBtn"
            title="Keluar dari sistem"
            aria-label="Keluar dari sistem"
        >
            <i class="fi fi-sr-sign-out-alt"></i>
            <span>Keluar dari Sistem</span>
        </button>
    </div>

</aside>
