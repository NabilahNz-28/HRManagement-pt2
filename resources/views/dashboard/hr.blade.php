@extends('layouts.app')

@section('title', 'Dashboard HR')
@section('header-title', 'Dashboard Overview')
@section('header-subtitle', 'Ringkasan performa kehadiran & SDM Bingxue & Mixue')

@section('styles')
<style>
    /* Override card-white untuk compatibility dengan build baru */
    .card-white {
        background: #FFFFFF;
        border: 1px solid #DDE4EF;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(15,31,61,0.04);
    }
    .stat-number { font-size: 28px; font-weight: 800; line-height: 1; color: #0F1F3D; }
    .stat-number-green { color: #16A34A; }
    .stat-number-amber { color: #D97706; }
    .stat-number-blue  { color: #2563EB; }
    .stat-label-sm { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #7C8DAB; }
    .stat-icon-box {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .badge-success { background: #DCFCE7; color: #15803D; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
    .badge-warning { background: #FEF3C7; color: #D97706; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
    .badge-info    { background: #DBEAFE; color: #1D4ED8; padding: 3px 10px; border-radius: 999px; font-size: 11.5px; font-weight: 700; }
    .data-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-radius: 10px; background: #F8FAFD; border: 1px solid #EEF2F8; margin-bottom: 8px; }
    .data-row:last-child { margin-bottom: 0; }
</style>
@endsection

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Stats Cards Row --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">

        {{-- Total Karyawan --}}
        <div class="card-white" style="padding:20px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p class="stat-label-sm">Total Karyawan</p>
                <div class="stat-number" style="margin-top:6px;">{{ $totalKaryawan }}</div>
                <p style="font-size:11.5px;color:#16A34A;font-weight:600;margin-top:4px;">
                    <i class="fi fi-rr-users" style="margin-right:4px;"></i>Karyawan Aktif
                </p>
            </div>
            <div class="stat-icon-box" style="background:rgba(30,58,110,0.08);color:#1E3A6E;">
                <i class="fi fi-rr-users"></i>
            </div>
        </div>

        {{-- Hadir Hari Ini --}}
        <div class="card-white" style="padding:20px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p class="stat-label-sm">Hadir Hari Ini</p>
                <div class="stat-number stat-number-green" style="margin-top:6px;">{{ $hadirHariIni }}</div>
                <p style="font-size:11.5px;color:#7C8DAB;font-weight:500;margin-top:4px;">
                    Dari total {{ $totalKaryawan }} staf
                </p>
            </div>
            <div class="stat-icon-box" style="background:rgba(22,163,74,0.08);color:#16A34A;">
                <i class="fi fi-rr-check-circle"></i>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="card-white" style="padding:20px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p class="stat-label-sm">Terlambat</p>
                <div class="stat-number stat-number-amber" style="margin-top:6px;">{{ $terlambatHariIni }}</div>
                <p style="font-size:11.5px;color:#D97706;font-weight:600;margin-top:4px;">
                    <i class="fi fi-rr-clock" style="margin-right:4px;"></i>Check-in &gt; 08:15 WIB
                </p>
            </div>
            <div class="stat-icon-box" style="background:rgba(217,119,6,0.08);color:#D97706;">
                <i class="fi fi-rr-clock"></i>
            </div>
        </div>

        {{-- Izin / Cuti --}}
        <div class="card-white" style="padding:20px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <p class="stat-label-sm">Izin &amp; Cuti</p>
                <div class="stat-number stat-number-blue" style="margin-top:6px;">{{ $izinCutiHariIni }}</div>
                <p style="font-size:11.5px;color:#2563EB;font-weight:600;margin-top:4px;">
                    <i class="fi fi-rr-calendar" style="margin-right:4px;"></i>Disetujui hari ini
                </p>
            </div>
            <div class="stat-icon-box" style="background:rgba(37,99,235,0.08);color:#2563EB;">
                <i class="fi fi-rr-calendar"></i>
            </div>
        </div>

    </div>

    {{-- Charts Section --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">

        {{-- Line Chart Kehadiran --}}
        <div class="card-white" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <h4 style="font-size:14px;font-weight:800;color:#0F1F3D;margin:0 0 3px;">Grafik Kehadiran (14 Hari Terakhir)</h4>
                    <p style="font-size:11.5px;color:#7C8DAB;margin:0;">Data presensi riil dari database</p>
                </div>
                <div style="display:flex;gap:14px;font-size:11.5px;font-weight:700;">
                    <span style="display:flex;align-items:center;gap:5px;color:#1E3A6E;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#1E3A6E;display:inline-block;"></span> Tepat Waktu
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;color:#D97706;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#D97706;display:inline-block;"></span> Terlambat
                    </span>
                </div>
            </div>
            <div style="height:220px;width:100%;position:relative;">
                <canvas id="kehadiranChart"></canvas>
            </div>
        </div>

        {{-- Donut Chart Jabatan --}}
        <div class="card-white" style="padding:22px;">
            <div style="margin-bottom:14px;">
                <h4 style="font-size:14px;font-weight:800;color:#0F1F3D;margin:0 0 3px;">Distribusi Jabatan</h4>
                <p style="font-size:11.5px;color:#7C8DAB;margin:0;">Jumlah staf per posisi</p>
            </div>
            <div style="height:160px;display:flex;align-items:center;justify-content:center;">
                <canvas id="jabatanChart"></canvas>
            </div>
            <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:7px;border-bottom:1px solid #EEF2F8;">
                    <span style="font-size:12px;color:#5A6B88;font-weight:500;display:flex;align-items:center;gap:7px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#1E3A6E;display:inline-block;"></span> Karyawan
                    </span>
                    <span style="font-size:12px;font-weight:800;color:#0F1F3D;">{{ $jabatanCounts[0] }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-bottom:7px;border-bottom:1px solid #EEF2F8;">
                    <span style="font-size:12px;color:#5A6B88;font-weight:500;display:flex;align-items:center;gap:7px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#F5A623;display:inline-block;"></span> Kepala Toko
                    </span>
                    <span style="font-size:12px;font-weight:800;color:#0F1F3D;">{{ $jabatanCounts[1] }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:12px;color:#5A6B88;font-weight:500;display:flex;align-items:center;gap:7px;">
                        <span style="width:9px;height:9px;border-radius:50%;background:#16A34A;display:inline-block;"></span> Management
                    </span>
                    <span style="font-size:12px;font-weight:800;color:#0F1F3D;">{{ $jabatanCounts[2] }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Two Column: Absensi & Cuti Pending --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        {{-- Log Absensi Hari Ini --}}
        <div class="card-white" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <h4 style="font-size:14px;font-weight:800;color:#0F1F3D;margin:0 0 3px;">Log Presensi Hari Ini</h4>
                    <p style="font-size:11.5px;color:#7C8DAB;margin:0;">Check-in staf secara real-time</p>
                </div>
                <a href="{{ route('monitoring.harian') }}" style="font-size:12px;font-weight:700;color:#1E3A6E;text-decoration:none;">
                    Lihat Semua →
                </a>
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($absensiHariIni as $absen)
                    <div class="data-row">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#DBEAFE;color:#1D4ED8;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;flex-shrink:0;">
                                {{ strtoupper(substr($absen->karyawan->nama_lengkap ?? 'K', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-size:12.5px;font-weight:700;color:#0F1F3D;">{{ $absen->karyawan->nama_lengkap ?? '-' }}</div>
                                <div style="font-size:11px;color:#7C8DAB;">
                                    {{ $absen->karyawan->jabatan ?? '-' }} &bull;
                                    <strong style="color:#3B4C6E;">{{ $absen->jam_masuk ? substr($absen->jam_masuk, 0, 5) : '-' }} WIB</strong>
                                </div>
                            </div>
                        </div>
                        <div>
                            @if($absen->status == 'hadir')
                                <span class="badge-success">Tepat Waktu</span>
                            @elseif($absen->status == 'terlambat')
                                <span class="badge-warning">Terlambat</span>
                            @else
                                <span class="badge-info">{{ ucfirst($absen->status) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:28px 0;color:#9BADC8;font-size:12.5px;">
                        <i class="fi fi-rr-clock" style="font-size:24px;display:block;margin-bottom:6px;opacity:0.5;"></i>
                        Belum ada data absensi untuk hari ini
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pengajuan Cuti Pending --}}
        <div class="card-white" style="padding:22px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <div>
                    <h4 style="font-size:14px;font-weight:800;color:#0F1F3D;margin:0 0 3px;">Persetujuan Cuti &amp; Izin</h4>
                    <p style="font-size:11.5px;color:#7C8DAB;margin:0;">Pengajuan menunggu konfirmasi HR</p>
                </div>
                <a href="{{ route('cuti.riwayat') }}" style="font-size:12px;font-weight:700;color:#1E3A6E;text-decoration:none;">
                    Kelola Semua →
                </a>
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($cutiPending as $cuti)
                    <div class="data-row" style="flex-direction:column;align-items:flex-start;gap:8px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-size:12.5px;font-weight:700;color:#0F1F3D;">{{ $cuti->karyawan->nama_lengkap ?? '-' }}</span>
                                <span class="badge-warning" style="font-size:10px;">Pending</span>
                            </div>
                            <div style="display:flex;gap:6px;">
                                <form method="POST" action="{{ route('cuti.approve', $cuti->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:5px 12px;background:#16A34A;color:white;border:none;border-radius:7px;font-size:11.5px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.15s;" onmouseover="this.style.background='#15803D'" onmouseout="this.style.background='#16A34A'">
                                        Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cuti.reject', $cuti->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="padding:5px 12px;background:#DC2626;color:white;border:none;border-radius:7px;font-size:11.5px;font-weight:700;cursor:pointer;font-family:'Inter',sans-serif;transition:background 0.15s;" onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='#DC2626'">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div style="font-size:11.5px;color:#5A6B88;">
                            <strong style="color:#3B4C6E;">{{ ucwords(str_replace('_', ' ', $cuti->jenis)) }}</strong>
                            ({{ $cuti->jumlah_hari }} hari) &bull; {{ date('d M Y', strtotime($cuti->tanggal_mulai)) }}
                            <span style="color:#9BADC8;font-style:italic;display:block;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px;">"{{ $cuti->alasan }}"</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:28px 0;color:#9BADC8;font-size:12.5px;">
                        <i class="fi fi-rr-check-circle" style="font-size:24px;display:block;margin-bottom:6px;opacity:0.5;"></i>
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
    // ===== CHART KEHADIRAN 14 HARI =====
    (function() {
        const canvas = document.getElementById('kehadiranChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const gradHadir = ctx.createLinearGradient(0, 0, 0, 220);
        gradHadir.addColorStop(0, 'rgba(30,58,110,0.25)');
        gradHadir.addColorStop(1, 'rgba(30,58,110,0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Tepat Waktu',
                        data: {!! json_encode($chartHadir) !!},
                        borderColor: '#1E3A6E',
                        borderWidth: 2.5,
                        backgroundColor: gradHadir,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1E3A6E',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    },
                    {
                        label: 'Terlambat',
                        data: {!! json_encode($chartTerlambat) !!},
                        borderColor: '#F5A623',
                        borderWidth: 2,
                        borderDash: [5, 4],
                        backgroundColor: 'transparent',
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#F5A623',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0B1628',
                        titleColor: '#F5A623',
                        bodyColor: '#FFFFFF',
                        padding: 10,
                        cornerRadius: 8,
                        borderColor: 'rgba(245,166,35,0.3)',
                        borderWidth: 1,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'Inter' }, color: '#7C8DAB' }
                    },
                    y: {
                        grid: { color: '#EEF2F8', lineWidth: 1 },
                        ticks: { font: { size: 11, family: 'Inter' }, color: '#7C8DAB', stepSize: 1 },
                        beginAtZero: true
                    }
                }
            }
        });
    })();

    // ===== CHART JABATAN =====
    (function() {
        const canvas = document.getElementById('jabatanChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($jabatanLabels) !!},
                datasets: [{
                    data: {!! json_encode($jabatanCounts) !!},
                    backgroundColor: ['#1E3A6E', '#F5A623', '#16A34A'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0B1628',
                        titleColor: '#F5A623',
                        bodyColor: '#FFFFFF',
                        padding: 8,
                        cornerRadius: 8,
                    }
                },
                cutout: '70%'
            }
        });
    })();
</script>
@endsection
