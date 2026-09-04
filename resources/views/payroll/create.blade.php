@extends('layouts.app')

@section('title', 'Buat Slip Gaji Massal')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 leading-tight">Buat Slip Gaji Massal</h2>
        <p class="text-sm text-slate-500 mt-1">Gaji Pokok & Uang Makan dihitung otomatis dari kehadiran. Hutang bisa diangsur per karyawan.</p>
    </div>
    <a href="{{ route('payroll.index') }}" class="btn-secondary">
        <i class="fi fi-rr-arrow-left"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm">
        <i class="fi fi-rr-exclamation mt-0.5 text-lg"></i>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('payroll.store') }}" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kiri: Periode & Karyawan --}}
        <div class="lg:col-span-1 space-y-5">
            <div class="card-white p-5">
                <h3 class="text-sm font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fi fi-rr-calendar text-blue-600"></i> Pengaturan Periode
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label text-xs">Nama Periode *</label>
                        <input type="text" name="periode" class="form-input text-sm" placeholder="Contoh: September 2026" required>
                    </div>
                    <div>
                        <label class="form-label text-xs">Mulai Tanggal Absensi *</label>
                        <input type="date" name="start_date" class="form-input text-sm" required>
                    </div>
                    <div>
                        <label class="form-label text-xs">Sampai Tanggal Absensi *</label>
                        <input type="date" name="end_date" class="form-input text-sm" required>
                    </div>
                </div>
            </div>

            {{-- Daftar Karyawan dengan Hutang --}}
            <div class="card-white p-5">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fi fi-rr-users text-blue-600"></i> Pilih Karyawan
                    </h3>
                    <button type="button" id="btnSelectAll" class="text-xs font-bold text-blue-600 hover:text-blue-800">
                        Pilih Semua
                    </button>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto pr-1 custom-scrollbar" id="karyawanList">
                    @forelse($karyawans as $k)
                        <div class="border border-slate-100 rounded-xl transition-all" id="row-{{ $k->id }}">
                            {{-- Baris utama (checkbox) --}}
                            <label class="flex items-center gap-3 p-3 cursor-pointer hover:bg-slate-50 rounded-xl">
                                <input type="checkbox" 
                                    name="karyawan_ids[]" 
                                    value="{{ $k->id }}" 
                                    class="karyawan-checkbox w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                                    data-hutang="{{ (float)$k->hutang }}"
                                    data-id="{{ $k->id }}"
                                    onchange="toggleHutangRow(this)">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-bold text-slate-900">{{ $k->nama_lengkap }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $k->nik }} · {{ $k->jabatan }} · 
                                        <span class="{{ $k->toko === 'Bingxue' ? 'text-orange-500' : 'text-blue-500' }} font-semibold">{{ $k->toko }}</span>
                                    </div>
                                </div>
                                @if($k->hutang > 0)
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full flex-shrink-0">
                                        Hutang: Rp {{ number_format($k->hutang, 0, ',', '.') }}
                                    </span>
                                @endif
                            </label>

                            {{-- Baris Angsuran Hutang (hanya muncul jika punya hutang & karyawan dicentang) --}}
                            @if($k->hutang > 0)
                            <div id="hutang-row-{{ $k->id }}" class="hidden px-3 pb-3">
                                <div class="bg-red-50 rounded-xl p-3 border border-red-100">
                                    <label class="text-[11px] font-bold text-red-600 block mb-1.5">
                                        Angsuran Hutang Bulan Ini (Rp) 
                                        <span class="text-red-400 font-normal">— Saldo: Rp {{ number_format($k->hutang, 0, ',', '.') }}</span>
                                    </label>
                                    <input type="number" 
                                        name="potongan_hutang[{{ $k->id }}]" 
                                        class="form-input text-sm" 
                                        min="0" 
                                        max="{{ $k->hutang }}"
                                        step="1000"
                                        value="{{ $k->hutang }}"
                                        placeholder="Isi nominal angsuran (boleh parsial)">
                                    <p class="text-[10px] text-red-400 mt-1">Isi 0 jika tidak ingin potong hutang bulan ini.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-4">Belum ada data karyawan aktif.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kanan: Komponen Gaji --}}
        <div class="lg:col-span-2">
            <div class="card-white p-6 space-y-6">
                
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fi fi-rr-calculator text-emerald-600"></i> Komponen Harian (× Jumlah Kehadiran)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label text-xs">Gaji Pokok / Hari *</label>
                            <input type="number" name="gaji_pokok_harian" class="form-input text-sm" required min="0" value="0" placeholder="100000">
                            <p class="text-[10px] text-slate-400 mt-1">Masuk 26 hari → 26 × Gaji Pokok Harian.</p>
                        </div>
                        <div>
                            <label class="form-label text-xs">Uang Makan / Hari</label>
                            <input type="number" name="uang_makan_harian" class="form-input text-sm" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <i class="fi fi-rr-coins text-amber-500"></i> Komponen Flat / Global
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label text-xs">Uang Lembur</label>
                            <input type="number" name="lembur" class="form-input text-sm" min="0" value="0">
                        </div>
                        <div>
                            <label class="form-label text-xs">Angkat Barang</label>
                            <input type="number" name="angkat_barang" class="form-input text-sm" min="0" value="0">
                        </div>
                        <div>
                            <label class="form-label text-xs">Bonus</label>
                            <input type="number" name="bonus" class="form-input text-sm" min="0" value="0">
                        </div>
                        <div>
                            <label class="form-label text-xs">THR</label>
                            <input type="number" name="thr" class="form-input text-sm" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="fi fi-rr-info text-blue-600 mt-0.5"></i>
                        <div class="text-xs text-blue-800 space-y-1">
                            <p class="font-bold">Catatan Sistem:</p>
                            <ul class="list-disc list-inside">
                                <li><strong>Uang Transport</strong> ditarik otomatis dari profil personal karyawan.</li>
                                <li><strong>Hutang</strong> dapat diangsur — form angsuran muncul otomatis di samping karyawan yang punya hutang saat dicentang. Isi 0 jika tidak mau dipotong bulan ini.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="btn-primary text-base px-8 py-3">
                        <i class="fi fi-rr-rocket-lunch"></i> Proses & Buat Slip Gaji
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    // Toggle hutang angsuran row when checkbox toggled
    function toggleHutangRow(checkbox) {
        const id = checkbox.dataset.id;
        const hutang = parseFloat(checkbox.dataset.hutang) || 0;
        const hutangRow = document.getElementById('hutang-row-' + id);
        const row = document.getElementById('row-' + id);
        
        if (hutangRow) {
            hutangRow.classList.toggle('hidden', !checkbox.checked);
        }
        row.classList.toggle('bg-blue-50/50', checkbox.checked);
        row.classList.toggle('border-blue-200', checkbox.checked);
    }

    // Select All toggle
    let allSelected = false;
    document.getElementById('btnSelectAll').addEventListener('click', function() {
        allSelected = !allSelected;
        document.querySelectorAll('.karyawan-checkbox').forEach(cb => {
            cb.checked = allSelected;
            toggleHutangRow(cb);
        });
        this.textContent = allSelected ? 'Batal Pilih Semua' : 'Pilih Semua';
    });
</script>
@endsection
