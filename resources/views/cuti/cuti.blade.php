@extends('layouts.app')

@section('title', 'Pengajuan Cuti')
@section('header-title', 'Pengajuan Cuti')
@section('header-subtitle', 'Formulir permohonan cuti kerja tahunan & khusus')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Info Card Sisa Cuti --}}
    <div class="card-white p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-100 flex items-center justify-between">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                <i class="fi fi-rr-plus"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Sisa Jatah Cuti Anda</h4>
                <p class="text-xs text-slate-500">Tahun {{ date('Y') }} &bull; Diperbarui secara otomatis</p>
            </div>
        </div>
        <div class="text-right">
            <span class="text-2xl font-extrabold text-blue-700">{{ $sisaCuti }} Hari</span>
            <p class="text-[11px] text-slate-500">Dari total 12 hari</p>
        </div>
    </div>
    
    <div class="card-white p-8">
        
        {{-- Header Section --}}
        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-900">Pengajuan Cuti</h3>
            <p class="text-xs text-slate-500 mt-1">Ajukan permohonan cuti kerja sesuai ketentuan perusahaan</p>
        </div>

        <form method="POST" action="{{ route('cuti.cuti.post') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Row 1: Jenis Cuti --}}
            <div>
                <label for="jenis_cuti" class="form-label">Jenis Cuti</label>
                <select id="jenis_cuti" name="jenis_cuti" required class="form-input">
                    <option value="cuti_tahunan" {{ old('jenis_cuti') == 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="cuti_sakit" {{ old('jenis_cuti') == 'cuti_sakit' ? 'selected' : '' }}>Cuti Sakit (Lebih dari 1 Hari)</option>
                    <option value="cuti_melahirkan" {{ old('jenis_cuti') == 'cuti_melahirkan' ? 'selected' : '' }}>Cuti Melahirkan (Maternity)</option>
                    <option value="cuti_besar" {{ old('jenis_cuti') == 'cuti_besar' ? 'selected' : '' }}>Cuti Besar / Cuti Panjang</option>
                    <option value="lainnya" {{ old('jenis_cuti') == 'lainnya' ? 'selected' : '' }}>Cuti Khusus Lainnya</option>
                </select>
            </div>

            {{-- Row 2: Tanggal Mulai & Selesai --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Tanggal Mulai --}}
                <div>
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai Cuti</label>
                    <input 
                        type="date" 
                        id="tanggal_mulai" 
                        name="tanggal_mulai" 
                        value="{{ old('tanggal_mulai', date('Y-m-d')) }}" 
                        required 
                        class="form-input"
                    >
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai Cuti</label>
                    <input 
                        type="date" 
                        id="tanggal_selesai" 
                        name="tanggal_selesai" 
                        value="{{ old('tanggal_selesai', date('Y-m-d')) }}" 
                        required 
                        class="form-input"
                    >
                </div>

            </div>

            {{-- Row 3: Alasan Cuti --}}
            <div>
                <label for="alasan" class="form-label">Alasan Cuti</label>
                <textarea 
                    id="alasan" 
                    name="alasan" 
                    rows="3" 
                    required 
                    placeholder="Jelaskan keperluan cuti secara detail (minimal 10 karakter)" 
                    class="form-input resize-none"
                >{{ old('alasan') }}</textarea>
            </div>

            {{-- Row 4: Upload Dokumen Pendukung --}}
            <div>
                <label for="bukti" class="form-label">Dokumen Pendukung (Opsional)</label>
                <div class="border border-slate-300 rounded-xl p-2.5 bg-slate-50/50">
                    <input 
                        type="file" 
                        id="bukti" 
                        name="bukti" 
                        accept="image/*,.pdf" 
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer"
                    >
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Surat medis, surat keterangan, atau bukti pendukung lainnya (Maks. 5 MB)</p>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white font-bold text-xs rounded-lg transition-all shadow-sm flex items-center gap-2"
                >
                    Ajukan Cuti
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
