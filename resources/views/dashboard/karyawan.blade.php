@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('header-title', 'Halo, ' . ($karyawan->nama_lengkap ?? Auth::user()->name))
@section('header-subtitle', ($karyawan->jabatan->nama ?? 'Staff') . ' — ' . ($karyawan->departemen->nama ?? 'Bingxue & Mixue'))

@section('content')
<div class="space-y-6">

    {{-- Today's Attendance Banner Card --}}
    <div class="card-white p-6 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white border-0 shadow-lg relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-40 h-40 bg-orange-500/10 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white/90 text-xs font-semibold backdrop-blur-sm mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 live-dot"></span>
                    Presensi Hari Ini &bull; {{ date('d F Y') }}
                </div>
                <h3 class="text-2xl font-black text-white tracking-tight">
                    @if(!$absensiHariIni)
                        Anda Belum Melakukan Absensi Masuk
                    @elseif($absensiHariIni && !$absensiHariIni->jam_pulang)
                        Sudah Check-In Masuk (Pukul {{ substr($absensiHariIni->jam_masuk, 0, 5) }} WIB)
                    @else
                        Presensi Selesai! Terima kasih atas dedikasi Anda.
                    @endif
                </h3>
                <p class="text-xs text-slate-300 mt-1">
                    @if(!$absensiHariIni)
                        Batas toleransi kehadiran tepat waktu adalah pukul 08:15 WIB.
                    @elseif($absensiHariIni && !$absensiHariIni->jam_pulang)
                        Status: <strong class="text-emerald-400 font-bold uppercase">{{ $absensiHariIni->status }}</strong>. Jangan lupa absen pulang saat jam kerja berakhir.
                    @else
                        Jam Masuk: <strong>{{ substr($absensiHariIni->jam_masuk, 0, 5) }}</strong> &bull; Jam Pulang: <strong>{{ substr($absensiHariIni->jam_pulang, 0, 5) }}</strong> (Durasi: {{ round($absensiHariIni->durasi_menit / 60, 1) }} Jam)
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                @if(!$absensiHariIni)
                    <a href="{{ route('absensi.masuk') }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fi fi-tr-inbox-in text-base"></i>
                        Absen Masuk Sekarang
                    </a>
                @elseif($absensiHariIni && !$absensiHariIni->jam_pulang)
                    <a href="{{ route('absensi.pulang') }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                        <i class="fi fi-tr-inbox-out text-base"></i>
                        Absen Pulang Sekarang
                    </a>
                @else
                    <span class="px-5 py-2.5 bg-white/10 text-emerald-300 rounded-xl text-xs font-bold flex items-center gap-2">
                        <i class="fi fi-rr-check text-base"></i> Presensi Hari Ini Selesai
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {{-- Total Hadir --}}
        <div class="card-white p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hadir Bulan Ini</p>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalHadirBulanIni }} Hari</h4>
                <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                    Bulan {{ date('F Y') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fi fi-ts-clipboard-list-check"></i>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="card-white p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terlambat</p>
                <h4 class="text-3xl font-extrabold text-amber-500 mt-1">{{ $totalTerlambatBulanIni }} Kali</h4>
                <p class="text-[11px] text-amber-600 font-semibold mt-1">
                    Bulan {{ date('F') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fi fi-rr-clock"></i>
            </div>
        </div>

        {{-- Izin / Sakit --}}
        <div class="card-white p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Izin & Sakit</p>
                <h4 class="text-3xl font-extrabold text-blue-600 mt-1">{{ $totalIzinCutiBulanIni }} Hari</h4>
                <p class="text-[11px] text-blue-600 font-semibold mt-1">
                    Total hari izin
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fi fi-rr-calendar"></i>
            </div>
        </div>

        {{-- Sisa Cuti --}}
        <div class="card-white p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sisa Cuti Tahunan</p>
                <h4 class="text-3xl font-extrabold text-purple-600 mt-1">{{ $sisaCuti }} Hari</h4>
                <p class="text-[11px] text-purple-600 font-semibold mt-1">
                    Tahun {{ date('Y') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fi fi-rr-plus"></i>
            </div>
        </div>

    </div>

    {{-- Two Column: Riwayat Absensi Terakhir & Status Pengajuan Cuti --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Riwayat Absensi 5 Hari Terakhir --}}
        <div class="card-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-bold text-slate-800 text-base">Riwayat Presensi Terbaru</h4>
                    <p class="text-xs text-slate-400">5 log absensi terakhir Anda</p>
                </div>
                <a href="{{ route('monitoring.bulanan') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Semua Data &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($riwayatAbsensi as $absen)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-800">{{ date('d M Y', strtotime($absen->tanggal)) }}</span>
                                @if($absen->status == 'hadir')
                                    <span class="badge badge-success text-[10px]">Hadir</span>
                                @elseif($absen->status == 'terlambat')
                                    <span class="badge badge-warning text-[10px]">Terlambat</span>
                                @else
                                    <span class="badge badge-info text-[10px]">{{ ucfirst($absen->status) }}</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Masuk: <strong class="text-slate-700">{{ substr($absen->jam_masuk, 0, 5) }}</strong> &bull; Pulang: <strong class="text-slate-700">{{ $absen->jam_pulang ? substr($absen->jam_pulang, 0, 5) : '-' }}</strong>
                            </p>
                        </div>
                        <div class="text-right text-xs">
                            <span class="text-slate-500 font-medium">
                                {{ $absen->durasi_menit ? round($absen->durasi_menit / 60, 1) . ' Jam' : '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada riwayat presensi yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Status Pengajuan Cuti / Izin --}}
        <div class="card-white p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="font-bold text-slate-800 text-base">Status Pengajuan Izin & Cuti</h4>
                    <p class="text-xs text-slate-400">Lacak persetujuan dari tim HRD</p>
                </div>
                <a href="{{ route('cuti.riwayat') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                    Lihat Riwayat &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($riwayatCuti as $cuti)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-800">{{ ucwords(str_replace('_', ' ', $cuti->jenis)) }}</span>
                                @if($cuti->status == 'disetujui')
                                    <span class="badge badge-success text-[10px]">Disetujui</span>
                                @elseif($cuti->status == 'ditolak')
                                    <span class="badge badge-danger text-[10px]">Ditolak</span>
                                @else
                                    <span class="badge badge-warning text-[10px]">Pending</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ date('d M Y', strtotime($cuti->tanggal_mulai)) }} ({{ $cuti->jumlah_hari }} hari) &bull; <span class="italic text-slate-400 truncate">{{ $cuti->alasan }}</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada pengajuan izin/cuti saat ini.
                    </div>
                @endforelse

                <div class="pt-2 flex gap-2">
                    <a href="{{ route('cuti.izin') }}" class="flex-1 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold text-center transition-colors">
                        + Ajukan Izin
                    </a>
                    <a href="{{ route('cuti.cuti') }}" class="flex-1 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold text-center transition-colors">
                        + Ajukan Cuti
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
