@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('header-title', 'Pengaturan Profil')
@section('header-subtitle', 'Informasi data diri dan keamanan akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="card-white p-8">
        
        {{-- Profile Header Card --}}
        <div class="flex items-center gap-5 pb-6 border-b border-slate-100 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-2xl shadow-md">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }} &bull; Role: <strong class="text-blue-600 uppercase">{{ Auth::user()->role }}</strong></p>
                @if($karyawan)
                    <p class="text-xs text-slate-400 mt-1">
                        NIK: <strong class="text-slate-700 font-mono">{{ $karyawan->nik }}</strong> &bull; Jabatan: <strong class="text-slate-700">{{ $karyawan->jabatan ?? '-' }}</strong> &bull; Bergabung: <strong class="text-slate-700">{{ date('d M Y', strtotime($karyawan->tanggal_bergabung)) }}</strong>
                    </p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Nama Lengkap --}}
                <div>
                    <label class="form-label text-xs">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="form-input text-xs">
                </div>

                {{-- Email (Read only) --}}
                <div>
                    <label class="form-label text-xs">Alamat Email (Akun Login)</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled class="form-input text-xs bg-slate-100 text-slate-400 cursor-not-allowed">
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- No Telepon --}}
                <div>
                    <label class="form-label text-xs">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon ?? '') }}" placeholder="+62 812..." class="form-input text-xs">
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="form-label text-xs">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-input text-xs">
                        <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? 'L') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Tanggal Lahir --}}
                <div>
                    <label class="form-label text-xs">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ?? '') }}" class="form-input text-xs">
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="form-label text-xs">Alamat Domisili</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $karyawan->alamat ?? '') }}" placeholder="Kota / Alamat lengkap" class="form-input text-xs">
                </div>

            </div>

            {{-- Ganti Password Section --}}
            <div class="pt-5 border-t border-slate-100">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Ganti Password (Opsional)</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label text-xs">Password Baru</label>
                        <input type="password" name="password_baru" placeholder="Biarkan kosong jika tidak diganti" class="form-input text-xs">
                    </div>
                    <div>
                        <label class="form-label text-xs">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru" class="form-input text-xs">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white font-bold text-xs rounded-lg transition-all shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
