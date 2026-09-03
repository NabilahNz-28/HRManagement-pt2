@extends('layouts.app')

@section('title', 'Rekap Bulanan')
@section('header-title', 'Rekap Presensi Bulanan')
@section('header-subtitle', 'Laporan & rekap kehadiran staf per periode bulan')

@section('content')
<div class="space-y-6">

    {{-- Filter & Action Bar --}}
    <div class="card-white p-5 flex flex-wrap items-end justify-between gap-4">
        
        <form method="GET" action="{{ route('monitoring.bulanan') }}" class="flex flex-wrap items-end gap-3 flex-1">
            
            {{-- Bulan --}}
            <div>
                <label class="form-label text-xs">Bulan</label>
                <select name="bulan" class="form-input text-xs py-2 min-w-[140px]">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tahun --}}
            <div>
                <label class="form-label text-xs">Tahun</label>
                <select name="tahun" class="form-input text-xs py-2 min-w-[100px]">
                    @foreach(range(date('Y') - 2, date('Y') + 1) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jabatan Filter --}}
            @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                <div>
                    <label class="form-label text-xs">Jabatan</label>
                    <select name="jabatan" class="form-input text-xs py-2 min-w-[160px]">
                        <option value="">Semua Jabatan</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ $jabatan == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                <i class="fi fi-rr-search"></i> Filter
            </button>
        </form>

        {{-- Export Excel Button --}}
        <div>
            <a href="{{ route('monitoring.export', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-sm">
                <i class="fi fi-tr-file-spreadsheet text-base"></i>
                <span>Export Laporan (CSV / Excel)</span>
            </a>
        </div>

    </div>

    {{-- Summary Cards Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        
        <div class="card-white p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fi fi-tr-inbox-in"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tepat Waktu</p>
                <h4 class="text-2xl font-extrabold text-emerald-600">{{ $totalHadir }} Catatan</h4>
            </div>
        </div>

        <div class="card-white p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fi fi-rr-clock"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Terlambat</p>
                <h4 class="text-2xl font-extrabold text-amber-500">{{ $totalTerlambat }} Catatan</h4>
            </div>
        </div>

        <div class="card-white p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fi fi-rr-calendar"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Izin & Sakit</p>
                <h4 class="text-2xl font-extrabold text-blue-600">{{ $totalIzin }} Catatan</h4>
            </div>
        </div>

    </div>

    {{-- Monthly Attendance Table --}}
    <div class="card-white overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">
                Rekap Presensi &bull; {{ date('F', mktime(0, 0, 0, $bulan, 1)) }} {{ $tahun }}
            </h4>
            <span class="text-xs font-semibold text-slate-500">
                Total: <strong>{{ $absensiList->count() }}</strong> catatan kehadiran
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Masuk</th>
                        <th class="px-6 py-4">Pulang</th>
                        <th class="px-6 py-4">Durasi</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($absensiList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ date('d M Y', strtotime($item->tanggal)) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $item->karyawan->nama_lengkap ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $item->karyawan->nik ?? '-' }}</div>
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
                                {{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) . ' WIB' : '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $item->durasi_menit ? round($item->durasi_menit / 60, 1) . ' Jam' : '-' }}
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
                                Tidak ada catatan kehadiran untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
