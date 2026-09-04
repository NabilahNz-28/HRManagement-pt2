@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('header-title', 'Manajemen Karyawan')
@section('header-subtitle', 'Kelola informasi staf dan akun Bingxue & Mixue')

@section('content')
<div class="space-y-6">

    {{-- Filter & Add Employee Card --}}
    <div class="card-white p-5 flex flex-wrap items-center justify-between gap-4">
        
        <form method="GET" action="{{ route('karyawan.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[220px] flex-1 sm:flex-initial">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, email..." class="form-input text-xs py-2.5 pl-8">
                <i class="fi fi-rr-search absolute left-2.5 top-3 text-slate-400 text-xs"></i>
            </div>

            {{-- Jabatan Filter (Karyawan, Kepala Toko, Management) --}}
            <select name="jabatan" class="form-input text-xs py-2.5 min-w-[160px]">
                <option value="">Semua Jabatan</option>
                @foreach($jabatanList as $j)
                    <option value="{{ $j }}" {{ $jabatan == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>

            <select name="status" class="form-input text-xs py-2.5 min-w-[130px]">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                <option value="cuti" {{ $status == 'cuti' ? 'selected' : '' }}>Cuti</option>
            </select>

            <select name="toko" class="form-input text-xs py-2.5 min-w-[130px]">
                <option value="">Semua Toko</option>
                <option value="Mixue" {{ request('toko') == 'Mixue' ? 'selected' : '' }}>Mixue</option>
                <option value="Bingxue" {{ request('toko') == 'Bingxue' ? 'selected' : '' }}>Bingxue</option>
            </select>

            <button type="submit" class="px-5 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                Cari
            </button>
            @if($search || $jabatan || $status || request('toko'))
                <a href="{{ route('karyawan.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold">
                    Reset
                </a>
            @endif
        </form>

        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-5 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white font-bold text-xs rounded-xl flex items-center gap-2 shadow-sm transition-all cursor-pointer">
            <i class="fi fi-rr-plus"></i> Tambah Karyawan
        </button>

    </div>

    {{-- Table Card --}}
    <div class="card-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">NIK</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">No. Telepon</th>
                        <th class="px-6 py-4">Bergabung</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($karyawanList as $k)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($k->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $k->nama_lengkap }}</div>
                                        <div class="text-xs text-slate-400">{{ $k->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-slate-700">{{ $k->nik }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $k->jabatan === 'Management' ? 'bg-purple-100 text-purple-700' : ($k->jabatan === 'Kepala Toko' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $k->jabatan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $k->no_telepon ?: '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ date('d M Y', strtotime($k->tanggal_bergabung)) }}</td>
                            <td class="px-6 py-4">
                                @if($k->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($k->status == 'cuti')
                                    <span class="badge badge-warning">Cuti</span>
                                @else
                                    <span class="badge badge-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- View Detail Button --}}
                                    <button onclick="openDetailModal({{ json_encode($k) }})" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" title="Lihat Detail Profil">
                                        <i class="fi fi-rr-eye text-sm"></i>
                                    </button>

                                    {{-- Edit Button --}}
                                    <button onclick="openEditModal({{ json_encode($k) }})" class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors cursor-pointer" title="Edit Karyawan">
                                        <i class="fi fi-rr-edit text-sm"></i>
                                    </button>

                                    {{-- Delete Form --}}
                                    <form method="POST" action="{{ route('karyawan.destroy', $k->id) }}" onsubmit="return confirm('Hapus data karyawan {{ $k->nama_lengkap }}? Akun login juga akan terhapus.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Hapus">
                                            <i class="fi fi-rr-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                Tidak ada data karyawan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $karyawanList->links() }}
        </div>
    </div>

</div>

{{-- ==================== MODAL LIHAT DETAIL KARYAWAN ==================== --}}
<div id="modalDetail" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fi fi-rr-user"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base" id="detailNamaTitle">Detail Karyawan</h4>
                    <p class="text-xs text-slate-400" id="detailNikTitle">-</p>
                </div>
            </div>
            <button onclick="document.getElementById('modalDetail').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl font-bold cursor-pointer">&times;</button>
        </div>

        <div class="space-y-3.5 text-xs text-slate-600">
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Jabatan:</span>
                <span class="font-bold text-slate-900 text-sm" id="detailJabatan">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Status Karyawan:</span>
                <span id="detailStatus">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">NIK:</span>
                <span class="font-mono font-bold text-slate-800" id="detailNik">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Alamat Email:</span>
                <span class="font-bold text-blue-600" id="detailEmail">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">No. Telepon / WhatsApp:</span>
                <span class="font-bold text-slate-800" id="detailTelepon">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Jenis Kelamin:</span>
                <span class="font-semibold text-slate-800" id="detailJK">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Tanggal Lahir:</span>
                <span class="font-semibold text-slate-800" id="detailTglLahir">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl flex justify-between items-center">
                <span class="text-slate-400 font-medium">Tanggal Bergabung:</span>
                <span class="font-semibold text-slate-800" id="detailTglGabung">-</span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 font-medium block mb-1">Alamat Domisili:</span>
                <p class="font-semibold text-slate-800" id="detailAlamat">-</p>
            </div>
            <div class="p-3 bg-red-50 rounded-xl flex justify-between items-center">
                <span class="text-red-500 font-medium">Hutang Karyawan:</span>
                <span class="font-bold text-red-600 text-sm" id="detailHutang">Rp 0</span>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl flex justify-between items-center">
                <span class="text-emerald-500 font-medium">Uang Transport (Per Bulan):</span>
                <span class="font-bold text-emerald-600 text-sm" id="detailTransport">Rp 0</span>
            </div>
        </div>

        <div class="pt-5 border-t border-slate-100 mt-5 flex justify-end">
            <button type="button" onclick="document.getElementById('modalDetail').classList.add('hidden')" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT KARYAWAN ==================== --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h4 class="font-bold text-slate-900 text-base">Edit Data Karyawan</h4>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl font-bold cursor-pointer">&times;</button>
        </div>

        <form method="POST" id="formEditKaryawan" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" id="editNama" required class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">NIK Karyawan *</label>
                    <input type="text" name="nik" id="editNik" required class="form-input text-xs font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Jabatan *</label>
                    <select name="jabatan" id="editJabatan" required class="form-input text-xs">
                        <option value="Karyawan">Karyawan</option>
                        <option value="Kepala Toko">Kepala Toko</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Toko *</label>
                    <select name="toko" id="editToko" required class="form-input text-xs">
                        <option value="Mixue">Mixue</option>
                        <option value="Bingxue">Bingxue</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" id="editTelepon" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="editJK" required class="form-input text-xs">
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="editTglLahir" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Tanggal Bergabung</label>
                    <input type="date" name="tanggal_bergabung" id="editTglGabung" class="form-input text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Status Karyawan *</label>
                    <select name="status" id="editStatus" required class="form-input text-xs">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Non-Aktif</option>
                        <option value="cuti">Cuti</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Saldo Hutang (Rp)</label>
                    <input type="number" name="hutang" id="editHutang" min="0" step="1000" placeholder="0" class="form-input text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                <div>
                    <label class="form-label text-xs">Uang Transport Bulanan (Rp)</label>
                    <input type="number" name="uang_transport" id="editTransport" min="0" step="1000" placeholder="0" class="form-input text-xs">
                </div>
            </div>

            <div>
                <label class="form-label text-xs">Alamat Domisili</label>
                <textarea name="alamat" id="editAlamat" rows="2" class="form-input text-xs resize-none"></textarea>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="form-label text-xs">Reset Password Akun (Opsional)</label>
                <input type="password" name="password" placeholder="Biarkan kosong jika tidak diubah" class="form-input text-xs">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== MODAL TAMBAH KARYAWAN ==================== --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <h4 class="font-bold text-slate-900 text-base">Tambah Karyawan Baru</h4>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl font-bold cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('karyawan.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Rian Pratama" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">NIK Karyawan *</label>
                    <input type="text" name="nik" required placeholder="BX-2026-001" class="form-input text-xs font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Email Perusahaan *</label>
                    <input type="email" name="email" required placeholder="rian@bingxuemixue.id" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Password Akun *</label>
                    <div class="relative">
                        <input type="password" name="password" id="inputPassword" required value="karyawan123" class="form-input text-xs pr-10">
                        <button type="button" onclick="togglePassword('inputPassword', 'eyeIcon')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i id="eyeIcon" class="fi fi-rr-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Jabatan *</label>
                    <select name="jabatan" required class="form-input text-xs">
                        <option value="Karyawan">Karyawan</option>
                        <option value="Kepala Toko">Kepala Toko</option>
                        <option value="Management">Management</option>
                    </select>
                </div>
                <div>
                    <label class="form-label text-xs">Toko *</label>
                    <select name="toko" required class="form-input text-xs">
                        <option value="Mixue">Mixue</option>
                        <option value="Bingxue">Bingxue</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" placeholder="+62 812..." class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-input text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Tanggal Bergabung *</label>
                    <input type="date" name="tanggal_bergabung" value="{{ date('Y-m-d') }}" required class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Alamat Domisili</label>
                    <input type="text" name="alamat" placeholder="Kota / Alamat" class="form-input text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-xs">Saldo Hutang (Rp)</label>
                    <input type="number" name="hutang" min="0" step="1000" value="0" placeholder="0" class="form-input text-xs">
                </div>
                <div>
                    <label class="form-label text-xs">Uang Transport Bulanan (Rp)</label>
                    <input type="number" name="uang_transport" min="0" step="1000" value="0" placeholder="0" class="form-input text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm cursor-pointer">
                    Simpan Karyawan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openDetailModal(data) {
        document.getElementById('detailNamaTitle').textContent = data.nama_lengkap;
        document.getElementById('detailNikTitle').textContent = 'NIK: ' + data.nik;
        document.getElementById('detailNik').textContent = data.nik;
        document.getElementById('detailEmail').textContent = data.email;
        document.getElementById('detailTelepon').textContent = data.no_telepon || '-';
        document.getElementById('detailJabatan').textContent = data.jabatan;
        document.getElementById('detailJK').textContent = data.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan';
        document.getElementById('detailTglLahir').textContent = data.tanggal_lahir || '-';
        document.getElementById('detailTglGabung').textContent = data.tanggal_bergabung || '-';
        document.getElementById('detailAlamat').textContent = data.alamat || '-';
        
        let statusBadge = '<span class="badge badge-success">Aktif</span>';
        if (data.status === 'nonaktif') statusBadge = '<span class="badge badge-danger">Non-Aktif</span>';
        if (data.status === 'cuti') statusBadge = '<span class="badge badge-warning">Cuti</span>';
        document.getElementById('detailStatus').innerHTML = statusBadge;

        let hutang = parseFloat(data.hutang) || 0;
        document.getElementById('detailHutang').textContent = 'Rp ' + hutang.toLocaleString('id-ID');

        let transport = parseFloat(data.uang_transport) || 0;
        document.getElementById('detailTransport').textContent = 'Rp ' + transport.toLocaleString('id-ID');

        document.getElementById('modalDetail').classList.remove('hidden');
    }

    function openEditModal(data) {
        let form = document.getElementById('formEditKaryawan');
        form.action = "{{ url('karyawan') }}/" + data.id;

        document.getElementById('editNama').value = data.nama_lengkap;
        document.getElementById('editNik').value = data.nik;
        document.getElementById('editEmail').value = data.email;
        document.getElementById('editJabatan').value = data.jabatan;
        let editToko = document.getElementById('editToko');
        if (editToko) editToko.value = data.toko || 'Mixue';
        document.getElementById('editTelepon').value = data.no_telepon || '';
        document.getElementById('editJK').value = data.jenis_kelamin;
        document.getElementById('editTglLahir').value = data.tanggal_lahir || '';
        document.getElementById('editAlamat').value = data.alamat || '';
        
        let tglGabungField = document.getElementById('editTglGabung');
        if (tglGabungField) tglGabungField.value = data.tanggal_bergabung || '';
        
        document.getElementById('editStatus').value = data.status;
        document.getElementById('editHutang').value = Math.round(parseFloat(data.hutang) || 0);
        
        let editTransport = document.getElementById('editTransport');
        if (editTransport) editTransport.value = Math.round(parseFloat(data.uang_transport) || 0);

        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function togglePassword(inputId, iconId) {
        let input = document.getElementById(inputId);
        let icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fi-rr-eye');
            icon.classList.add('fi-rr-eye-crossed');
        } else {
            input.type = "password";
            icon.classList.remove('fi-rr-eye-crossed');
            icon.classList.add('fi-rr-eye');
        }
    }
</script>
@endsection
