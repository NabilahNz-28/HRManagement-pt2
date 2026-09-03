@extends('layouts.app')

@section('title', 'Pengajuan Izin')
@section('header-title', 'Pengajuan Izin')
@section('header-subtitle', 'Formulir izin tidak masuk kerja karyawan')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="card-white p-8">
        
        {{-- Header Section --}}
        <div class="mb-6">
            <h3 class="text-xl font-bold text-slate-900">Pengajuan Izin</h3>
            <p class="text-xs text-slate-500 mt-1">Ajukan izin tidak masuk kerja dengan alasan yang jelas</p>
        </div>

        <form method="POST" action="{{ route('cuti.izin.post') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Row 1: Tanggal Izin & Jenis Izin --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Tanggal Izin --}}
                <div>
                    <label for="tanggal_izin" class="form-label">Tanggal Izin</label>
                    <input 
                        type="date" 
                        id="tanggal_izin" 
                        name="tanggal_izin" 
                        value="{{ old('tanggal_izin', date('Y-m-d')) }}" 
                        required 
                        class="form-input"
                    >
                </div>

                {{-- Jenis Izin --}}
                <div>
                    <label for="jenis_izin" class="form-label">Jenis Izin</label>
                    <select id="jenis_izin" name="jenis_izin" required class="form-input">
                        <option value="" disabled {{ old('jenis_izin') ? '' : 'selected' }}>Pilih jenis izin</option>
                        <option value="Izin Sakit" {{ old('jenis_izin') == 'Izin Sakit' ? 'selected' : '' }}>Izin Sakit</option>
                        <option value="Izin Penting" {{ old('jenis_izin') == 'Izin Penting' ? 'selected' : '' }}>Izin Penting</option>
                        <option value="Urusan Keluarga" {{ old('jenis_izin') == 'Urusan Keluarga' ? 'selected' : '' }}>Urusan Keluarga</option>
                        <option value="Urusan Pribadi" {{ old('jenis_izin') == 'Urusan Pribadi' ? 'selected' : '' }}>Urusan Pribadi</option>
                        <option value="Lainnya" {{ old('jenis_izin') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

            </div>

            {{-- Row 2: Alasan Izin --}}
            <div>
                <label for="alasan" class="form-label">Alasan Izin</label>
                <textarea 
                    id="alasan" 
                    name="alasan" 
                    rows="3" 
                    required 
                    placeholder="Jelaskan alasan izin secara detail (minimal 10 karakter)" 
                    class="form-input resize-none"
                >{{ old('alasan') }}</textarea>
            </div>

            {{-- Row 3: Upload Bukti --}}
            <div>
                <label for="bukti" class="form-label">Upload Bukti (Opsional)</label>
                <div class="border border-slate-300 rounded-xl p-2.5 bg-slate-50/50">
                    <input 
                        type="file" 
                        id="bukti" 
                        name="bukti" 
                        accept="image/*,.pdf" 
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer"
                    >
                </div>
                <p class="text-[11px] text-slate-400 mt-1.5">Surat dokter, foto, atau dokumen pendukung (Maks. 5 MB)</p>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white font-bold text-xs rounded-lg transition-all shadow-sm flex items-center gap-2"
                >
                    Ajukan Izin
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
