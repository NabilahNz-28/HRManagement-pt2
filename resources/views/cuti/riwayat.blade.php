@extends('layouts.app')

@section('title', 'Riwayat Izin & Cuti')
@section('header-title', 'Riwayat Izin & Cuti')
@section('header-subtitle', 'Daftar pengajuan izin dan cuti kerja karyawan')

@section('content')
<div class="space-y-6">

    {{-- Action & Header Card --}}
    <div class="card-white p-5 flex items-center justify-between">
        <div>
            <h4 class="font-bold text-slate-800 text-sm">Status Permohonan</h4>
            <p class="text-xs text-slate-400">Total {{ $cutiList->count() }} pengajuan tercatat di sistem</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cuti.izin') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="fi fi-rr-calendar"></i> + Ajukan Izin
            </a>
            <a href="{{ route('cuti.cuti') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                <i class="fi fi-rr-plus"></i> + Ajukan Cuti
            </a>
        </div>
    </div>

    {{-- Leave History Table Card --}}
    <div class="card-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Karyawan</th>
                        <th class="px-6 py-3.5">Jenis</th>
                        <th class="px-6 py-3.5">Periode</th>
                        <th class="px-6 py-3.5">Durasi</th>
                        <th class="px-6 py-3.5">Alasan</th>
                        <th class="px-6 py-3.5">Bukti</th>
                        <th class="px-6 py-3.5">Status</th>
                        @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                            <th class="px-6 py-3.5 text-right">Aksi HR</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($cutiList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $item->karyawan->nama_lengkap ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $item->karyawan->departemen->nama ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">
                                    {{ ucwords(str_replace('_', ' ', $item->jenis)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ date('d M Y', strtotime($item->tanggal_mulai)) }}
                                @if($item->tanggal_mulai != $item->tanggal_selesai)
                                    s/d {{ date('d M Y', strtotime($item->tanggal_selesai)) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $item->jumlah_hari }} Hari
                            </td>
                            <td class="px-6 py-4 max-w-xs text-slate-600 truncate" title="{{ $item->alasan }}">
                                {{ $item->alasan }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->lampiran)
                                    <a href="{{ asset($item->lampiran) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:underline font-semibold">
                                        <i class="fi fi-rr-clip"></i> Lihat File
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Tanpa Lampiran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status == 'disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="badge badge-danger">Ditolak</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>

                            {{-- HR Action Column --}}
                            @if(Auth::user()->isHR() || Auth::user()->isAdmin())
                                <td class="px-6 py-4 text-right">
                                    @if($item->status == 'pending')
                                        <div class="flex items-center justify-end gap-1.5">
                                            <form method="POST" action="{{ route('cuti.approve', $item->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors" title="Setujui">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('cuti.reject', $item->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-colors" title="Tolak">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400">Diproses</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <i class="fi fi-rr-document text-2xl block mb-2 text-slate-300"></i>
                                Belum ada riwayat pengajuan izin atau cuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
