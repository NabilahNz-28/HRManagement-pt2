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
            <i class="fi fi-rr-user"></i>
            <span>Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" id="logoutFormKaryawan">
            @csrf
        </form>
        <button type="button" onclick="showLogoutModal('logoutFormKaryawan')" class="sidebar-item text-red-500 hover:bg-red-50 hover:text-red-600 w-full text-left cursor-pointer">
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
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700">
                    {{ strtoupper(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </div>

</aside>
