@extends('layouts.app')

@section('title', 'Payroll & Slip Gaji')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 leading-tight">Daftar Payroll</h2>
        <p class="text-sm text-slate-500 mt-1">Kelola pembuatan slip gaji karyawan</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('payroll.create') }}" class="btn-primary">
            <i class="fi fi-rr-plus"></i> Buat Slip Gaji Baru
        </a>
    </div>
</div>

@if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <i class="fi fi-rr-check-circle mt-0.5 text-lg"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="card-white overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4 text-right">Total Pendapatan</th>
                    <th class="px-6 py-4 text-right text-red-500">Potongan Hutang</th>
                    <th class="px-6 py-4 text-right">Total Diterima</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
                @forelse($payrolls as $p)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $p->karyawan->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400">{{ $p->karyawan->nik }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg font-bold">{{ $p->periode }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-slate-700">
                            Rp {{ number_format($p->gaji_pokok + $p->uang_makan + $p->uang_transport + $p->lembur + $p->angkat_barang + $p->bonus + $p->thr, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-red-500">
                            Rp {{ number_format($p->potongan_hutang, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-600 text-sm">
                            Rp {{ number_format($p->total_gaji, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('payroll.pdf', $p->id) }}" target="_blank" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer inline-flex items-center gap-1.5" title="Cetak PDF">
                                <i class="fi fi-rr-print"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            Belum ada riwayat pembuatan slip gaji.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-slate-100">
        {{ $payrolls->links() }}
    </div>
</div>
@endsection
