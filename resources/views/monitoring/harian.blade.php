@extends('layouts.app')

@section('title', 'Rekap Harian')
@section('header-title', 'Rekap Presensi Harian')
@section('header-subtitle', 'Monitoring log kehadiran karyawan per tanggal')

@section('content')
<div class="space-y-6">

    {{-- Filter Card --}}
    <div class="card-white p-5">
        <form method="GET" action="{{ route('monitoring.harian') }}" class="flex flex-wrap items-end gap-4">
            
            {{-- Tanggal Filter --}}
            <div class="w-full sm:w-auto">
                <label class="form-label text-xs">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-input text-xs py-2">
            </div>

            {{-- Jabatan Filter (Jika HR/Admin) --}}
            @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                <div class="w-full sm:w-auto min-w-[180px]">
                    <label class="form-label text-xs">Jabatan</label>
                    <select name="jabatan" class="form-input text-xs py-2">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ $jabatan == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <i class="fi fi-rr-search"></i> Tampilkan
                </button>
                <a href="{{ route('monitoring.harian') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-all">
                    Reset
                </a>
            </div>

        </form>
    </div>

    {{-- Attendance Table Card --}}
    <div class="card-white overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">
                Log Kehadiran &bull; {{ date('d F Y', strtotime($tanggal)) }}
            </h4>
            <span class="text-xs font-semibold text-slate-500">
                Total: <strong>{{ $absensiList->count() }}</strong> catatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Jam Masuk</th>
                        <th class="px-6 py-4">Jam Pulang</th>
                        <th class="px-6 py-4">Durasi Kerja</th>
                        <th class="px-6 py-4">Lokasi Presensi</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($absensiList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($item->karyawan->nama_lengkap ?? 'K', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $item->karyawan->nama_lengkap ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->karyawan->nik ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ ($item->karyawan->jabatan ?? '') === 'Management' ? 'bg-purple-100 text-purple-700' : (($item->karyawan->jabatan ?? '') === 'Kepala Toko' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $item->karyawan->jabatan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                {{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) . ' WIB' : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) . ' WIB' : 'Belum Pulang' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $item->durasi_menit ? round($item->durasi_menit / 60, 1) . ' Jam' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-[200px] truncate" title="{{ $item->lokasi_masuk }}">
                                <i class="fi fi-rr-marker text-orange-500 mr-1"></i>
                                {{ $item->lokasi_masuk ?: 'Kantor Pusat' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status == 'hadir')
                                    <span class="badge badge-success">Tepat Waktu</span>
                                @elseif($item->status == 'terlambat')
                                    <span class="badge badge-warning">Terlambat</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                <i class="fi fi-rr-calendar text-2xl block mb-2 text-slate-300"></i>
                                Tidak ada data absensi untuk tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
