@extends('layouts.app')

@section('title', 'Dashboard HR')
@section('header-title', 'Dashboard Overview')
@section('header-subtitle', 'Ringkasan performa kehadiran & SDM Bingxue & Mixue')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Total Karyawan --}}
        <div class="card-white p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $totalKaryawan }}</h3>
                <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1 mt-1">
                    <i class="fi fi-ts-chart-line-up"></i> Karyawan Aktif
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl shadow-inner">
                <i class="fi fi-ts-people-poll"></i>
            </div>
        </div>

        {{-- Hadir Hari Ini --}}
        <div class="card-white p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hadir Hari Ini</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $hadirHariIni }}</h3>
                <span class="text-xs font-semibold text-slate-500 mt-1 inline-block">
                    Dari total {{ $totalKaryawan }} staf
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
                <i class="fi fi-ts-clipboard-list-check"></i>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="card-white p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terlambat</p>
                <h3 class="text-3xl font-extrabold text-amber-500 mt-1">{{ $terlambatHariIni }}</h3>
                <span class="text-xs font-semibold text-amber-600 mt-1 inline-block">
                    Check-in > 08:15 WIB
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shadow-inner">
                <i class="fi fi-rr-clock"></i>
            </div>
        </div>

        {{-- Izin / Cuti --}}
        <div class="card-white p-5 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Izin & Cuti</p>
                <h3 class="text-3xl font-extrabold text-blue-600 mt-1">{{ $izinCutiHariIni }}</h3>
                <span class="text-xs font-semibold text-blue-600 mt-1 inline-block">
                    Disetujui hari ini
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-inner">
                <i class="fi fi-rr-calendar"></i>
            </div>
        </div>

    </div>

    {{-- Charts Section: Grafik Kehadiran Aktual (Line Chart) & Distribusi Jabatan (Donut Chart) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Area Line Chart (Zenith style dengan Data Riil Database) --}}
        <div class="card-white p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Grafik Kehadiran Aktual (14 Hari Terakhir)</h4>
                    <p class="text-xs text-slate-400">Data presensi riil dari database absensi karyawan</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <span class="flex items-center gap-1.5 text-orange-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Tepat Waktu
                    </span>
                    <span class="flex items-center gap-1.5 text-amber-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Terlambat
                    </span>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="kehadiranChart"></canvas>
            </div>
        </div>

        {{-- Donut Chart: Distribusi Jabatan (Karyawan, Kepala Toko, Management) --}}
        <div class="card-white p-6">
            <div class="mb-4">
                <h4 class="font-bold text-slate-900 text-base">Distribusi Jabatan</h4>
                <p class="text-xs text-slate-400">Jumlah staf per tingkatan posisi</p>
            </div>
            <div class="h-44 w-full flex items-center justify-center">
                <canvas id="jabatanChart"></canvas>
            </div>
            <div class="mt-4 space-y-2 text-xs">
                <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                    <span class="font-medium text-slate-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Karyawan
                    </span>
                    <span class="font-bold text-slate-800">{{ $jabatanCounts[0] }} orang</span>
                </div>
                <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                    <span class="font-medium text-slate-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Kepala Toko
                    </span>
                    <span class="font-bold text-slate-800">{{ $jabatanCounts[1] }} orang</span>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <span class="font-medium text-slate-600 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Management
                    </span>
                    <span class="font-bold text-slate-800">{{ $jabatanCounts[2] }} orang</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Two Column: Absensi Hari Ini & Pengajuan Cuti Pending --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Absensi Hari Ini --}}
        <div class="card-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Log Presensi Hari Ini</h4>
                    <p class="text-xs text-slate-400">Check-in staf secara real-time</p>
                </div>
                <a href="{{ route('monitoring.harian') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($absensiHariIni as $absen)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($absen->karyawan->nama_lengkap ?? 'K', 0, 2)) }}
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">{{ $absen->karyawan->nama_lengkap ?? '-' }}</h5>
                                <p class="text-[11px] text-slate-400">
                                    <span class="font-semibold text-slate-700">{{ $absen->karyawan->jabatan ?? '-' }}</span> &bull; Masuk: <strong class="text-slate-800">{{ $absen->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-' }} WIB</strong>
                                </p>
                            </div>
                        </div>
                        <div>
                            @if($absen->status == 'hadir')
                                <span class="badge badge-success">Tepat Waktu</span>
                            @elseif($absen->status == 'terlambat')
                                <span class="badge badge-warning">Terlambat</span>
                            @else
                                <span class="badge badge-info">{{ ucfirst($absen->status) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <i class="fi fi-rr-clock text-2xl block mb-1"></i>
                        Belum ada data absensi untuk hari ini
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pengajuan Cuti / Izin Pending --}}
        <div class="card-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Persetujuan Cuti & Izin</h4>
                    <p class="text-xs text-slate-400">Pengajuan menunggu konfirmasi HR</p>
                </div>
                <a href="{{ route('cuti.riwayat') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($cutiPending as $cuti)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h5 class="text-xs font-bold text-slate-900">{{ $cuti->karyawan->nama_lengkap ?? '-' }}</h5>
                                <span class="badge badge-warning text-[10px]">Pending</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                <strong class="text-slate-700">{{ ucwords(str_replace('_', ' ', $cuti->jenis)) }}</strong> ({{ $cuti->jumlah_hari }} hari) &bull; {{ date('d M Y', strtotime($cuti->tanggal_mulai)) }}
                            </p>
                            <p class="text-[11px] text-slate-400 italic truncate max-w-xs mt-0.5">"{{ $cuti->alasan }}"</p>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <form method="POST" action="{{ route('cuti.approve', $cuti->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors" title="Setujui">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('cuti.reject', $cuti->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-colors" title="Tolak">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <i class="fi fi-rr-check text-2xl block mb-1"></i>
                        Semua pengajuan cuti/izin telah diproses
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Chart Kehadiran Aktual 14 Hari
    const ctxKehadiran = document.getElementById('kehadiranChart').getContext('2d');
    const gradHadir = ctxKehadiran.createLinearGradient(0, 0, 0, 250);
    gradHadir.addColorStop(0, 'rgba(255, 107, 0, 0.35)');
    gradHadir.addColorStop(1, 'rgba(255, 107, 0, 0.0)');

    new Chart(ctxKehadiran, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Tepat Waktu',
                    data: {!! json_encode($chartHadir) !!},
                    borderColor: '#FF6B00',
                    borderWidth: 2.5,
                    backgroundColor: gradHadir,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#FF6B00',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                },
                {
                    label: 'Terlambat',
                    data: {!! json_encode($chartTerlambat) !!},
                    borderColor: '#F59E0B',
                    borderWidth: 2,
                    borderDash: [4, 4],
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#F59E0B',
                    pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#64748B' } },
                y: { grid: { color: '#F1F5F9' }, ticks: { font: { size: 11 }, color: '#64748B', stepSize: 2 }, beginAtZero: true }
            }
        }
    });

    // Chart Distribusi Jabatan
    const ctxDept = document.getElementById('jabatanChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($jabatanLabels) !!},
            datasets: [{
                data: {!! json_encode($jabatanCounts) !!},
                backgroundColor: ['#2563EB', '#FF6B00', '#10B981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '72%'
        }
    });
</script>
@endsection
