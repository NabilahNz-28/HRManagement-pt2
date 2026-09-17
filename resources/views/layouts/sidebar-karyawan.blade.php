{{-- ==================== SIDEBAR KARYAWAN ==================== --}}
<aside class="sidebar-wrap">

    {{-- Header / Logo --}}
    <div class="sidebar-logo-area">
        <img src="{{ asset('img/bingxue.png') }}" alt="Bingxue">
        <div class="sidebar-logo-divider"></div>
        <img src="{{ asset('img/mixue1.jpg') }}" alt="Mixue">
        <div class="sidebar-brand-text" style="margin-left:2px;">
            <h1>Bingxue & Mixue</h1>
            <p>Absensi Karyawan</p>
        </div>
    </div>

    {{-- Nav Menu --}}
    <nav class="sidebar-nav">

        {{-- DASHBOARD --}}
        <div class="sidebar-section-title">Overview</div>
        <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fi fi-rr-home"></i>
            <span>Dashboard</span>
        </a>

        {{-- ABSENSI --}}
        <div class="sidebar-section-title">Absensi</div>
        <a href="{{ route('absensi.masuk') }}" class="sidebar-item {{ request()->routeIs('absensi.masuk') ? 'active' : '' }}">
            <i class="fi fi-rr-clock-seven"></i>
            <span>Absensi Masuk</span>
        </a>
        <a href="{{ route('absensi.pulang') }}" class="sidebar-item {{ request()->routeIs('absensi.pulang') ? 'active' : '' }}">
            <i class="fi fi-rr-time-forward"></i>
            <span>Absensi Pulang</span>
        </a>

        {{-- PENGAJUAN --}}
        <div class="sidebar-section-title">Pengajuan</div>
        <a href="{{ route('cuti.izin') }}" class="sidebar-item {{ request()->routeIs('cuti.izin') ? 'active' : '' }}">
            <i class="fi fi-rr-calendar-pen"></i>
            <span>Pengajuan Izin</span>
        </a>
        <a href="{{ route('cuti.cuti') }}" class="sidebar-item {{ request()->routeIs('cuti.cuti') ? 'active' : '' }}">
            <i class="fi fi-rr-umbrella-beach"></i>
            <span>Pengajuan Cuti</span>
        </a>

        {{-- RIWAYAT --}}
        <div class="sidebar-section-title">Riwayat</div>
        <a href="{{ route('monitoring.bulanan') }}" class="sidebar-item {{ request()->routeIs('monitoring.bulanan') ? 'active' : '' }}">
            <i class="fi fi-rr-chart-histogram"></i>
            <span>Rekap Kehadiran</span>
        </a>
        <a href="{{ route('cuti.riwayat') }}" class="sidebar-item {{ request()->routeIs('cuti.riwayat') ? 'active' : '' }}">
            <i class="fi fi-rr-document-signed"></i>
            <span>Riwayat Izin & Cuti</span>
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
        <div class="sidebar-avatar" style="background:linear-gradient(135deg,#3B82F6,#1D4ED8);color:white;">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <span class="sidebar-user-role role-kary">
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
            id="sidebarLogoutBtnKary"
            title="Keluar dari sistem"
            aria-label="Keluar dari sistem"
        >
            <i class="fi fi-sr-sign-out-alt"></i>
            <span>Keluar dari Sistem</span>
        </button>
    </div>

</aside>
