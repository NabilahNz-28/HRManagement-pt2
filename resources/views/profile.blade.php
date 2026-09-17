@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('header-title', 'Pengaturan Profil')
@section('header-subtitle', 'Informasi data diri dan keamanan akun')

@section('content')
{{-- Cropper.js CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="max-w-4xl mx-auto space-y-6">

    {{-- ============================================================ --}}
    {{-- SECTION 1: FOTO PROFIL (Save terpisah) --}}
    {{-- ============================================================ --}}
    <div class="card-white p-5 sm:p-8">
        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-5 flex items-center gap-2">
            <i class="fi fi-rr-camera text-blue-500"></i> Foto Profil
        </h4>

        <div class="flex flex-col sm:flex-row items-start gap-6">
            {{-- Current Photo --}}
            <div class="flex flex-col items-center gap-3">
                <div class="relative group cursor-pointer" onclick="document.getElementById('photoInput').click()">
                    @if(Auth::user()->getProfilePhotoUrl())
                        <img src="{{ Auth::user()->getProfilePhotoUrl() }}" 
                             alt="Foto Profil" 
                             class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover shadow-lg border-2 border-white" 
                             id="currentPhoto">
                    @else
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-3xl shadow-lg border-2 border-white" id="photoInitials">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 rounded-2xl bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <div class="text-center">
                            <i class="fi fi-rr-camera text-white text-xl"></i>
                            <p class="text-white text-[10px] mt-1 font-medium">Ganti Foto</p>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 text-center">Klik untuk pilih foto<br>JPG, PNG, WebP (max 2MB)</p>
            </div>

            {{-- Crop Area (hidden by default, shown after selecting image) --}}
            <div id="cropSection" class="flex-1 hidden w-full">
                <div class="bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 p-4">
                    <p class="text-xs font-semibold text-slate-600 mb-3 flex items-center gap-2">
                        <i class="fi fi-rr-crop text-indigo-500"></i> Sesuaikan Area Foto
                    </p>
                    <div class="relative w-full overflow-hidden rounded-xl bg-slate-900" style="max-height: 320px;">
                        <img id="cropImage" src="" alt="Crop" class="block max-w-full">
                    </div>
                    <div class="flex items-center justify-between mt-4 gap-3">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="rotateCrop(-90)" class="px-3 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-xs font-medium text-slate-700 transition-colors" title="Putar Kiri">
                                <i class="fi fi-rr-undo"></i>
                            </button>
                            <button type="button" onclick="rotateCrop(90)" class="px-3 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-xs font-medium text-slate-700 transition-colors" title="Putar Kanan">
                                <i class="fi fi-rr-redo"></i>
                            </button>
                            <button type="button" onclick="resetCrop()" class="px-3 py-1.5 rounded-lg bg-slate-200 hover:bg-slate-300 text-xs font-medium text-slate-700 transition-colors" title="Reset">
                                <i class="fi fi-rr-refresh"></i>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="cancelCrop()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-600 transition-colors">
                                Batal
                            </button>
                            <button type="button" onclick="saveCroppedPhoto()" id="btnSavePhoto" class="px-5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                <i class="fi fi-rr-disk"></i> Simpan Gambar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden file input --}}
        <input type="file" id="photoInput" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="onPhotoSelected(this)">

        {{-- Success/Error feedback --}}
        <div id="photoFeedback" class="hidden mt-4 px-4 py-2.5 rounded-xl text-xs font-medium"></div>
    </div>

    {{-- ============================================================ --}}
    {{-- SECTION 2: DATA PROFIL (Save terpisah) --}}
    {{-- ============================================================ --}}
    <div class="card-white p-5 sm:p-8">

        {{-- Profile Header Info --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pb-6 border-b border-slate-100 mb-6">
            @if(Auth::user()->getProfilePhotoUrl())
                <img src="{{ Auth::user()->getProfilePhotoUrl() }}" alt="Foto" class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover shadow-md" id="headerPhoto">
            @else
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-2xl shadow-md" id="headerInitials">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-slate-500 mt-0.5 break-all">{{ Auth::user()->email }} &bull; Role: <strong class="text-blue-600 uppercase">{{ Auth::user()->role }}</strong></p>
                @if($karyawan)
                    <p class="text-xs text-slate-400 mt-1">
                        NIK: <strong class="text-slate-700 font-mono">{{ $karyawan->nik }}</strong> &bull; Jabatan: <strong class="text-slate-700">{{ $karyawan->jabatan ?? '-' }}</strong> &bull; Bergabung: <strong class="text-slate-700">{{ date('d M Y', strtotime($karyawan->tanggal_bergabung)) }}</strong>
                    </p>
                @else
                    <p class="text-xs text-amber-500 mt-1 bg-amber-50 px-2 py-0.5 rounded-lg inline-block">HR / Admin — tidak memiliki profil karyawan terhubung.</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label text-xs">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label text-xs">Alamat Email (Akun Login)</label>
                    @if(Auth::user()->isHR())
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-input text-sm">
                        <p class="text-[11px] text-slate-400 mt-1">HR/Admin dapat mengubah alamat email.</p>
                    @else
                        <input type="email" value="{{ Auth::user()->email }}" disabled class="form-input text-sm bg-slate-100 text-slate-400 cursor-not-allowed">
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label text-xs">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon ?? '') }}" placeholder="+62 812..." class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label text-xs">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-input text-sm">
                        <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? 'L') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label text-xs">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ?? '') }}" class="form-input text-sm">
                </div>
                <div>
                    <label class="form-label text-xs">Alamat Domisili</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $karyawan->alamat ?? '') }}" placeholder="Kota / Alamat lengkap" class="form-input text-sm">
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="pt-5 border-t border-slate-100">
                <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Ganti Password (Opsional)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label text-xs">Password Baru</label>
                        <input type="password" name="password_baru" placeholder="Biarkan kosong jika tidak diganti" class="form-input text-sm">
                    </div>
                    <div>
                        <label class="form-label text-xs">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru" class="form-input text-sm">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fi fi-rr-disk"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>

{{-- Cropper.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
let cropper = null;

function onPhotoSelected(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];

    // Validate size (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        showFeedback('Ukuran file terlalu besar. Maksimal 2MB.', false);
        input.value = '';
        return;
    }

    // Validate type
    if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
        showFeedback('Format tidak didukung. Gunakan JPG, PNG, atau WebP.', false);
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const cropImage = document.getElementById('cropImage');
        cropImage.src = e.target.result;

        // Show crop section
        document.getElementById('cropSection').classList.remove('hidden');

        // Destroy old cropper if exists
        if (cropper) {
            cropper.destroy();
        }

        // Init Cropper.js
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 2,
            dragMode: 'move',
            autoCropArea: 0.85,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            minCropBoxWidth: 100,
            minCropBoxHeight: 100,
        });
    };
    reader.readAsDataURL(file);
}

function rotateCrop(deg) {
    if (cropper) cropper.rotate(deg);
}

function resetCrop() {
    if (cropper) cropper.reset();
}

function cancelCrop() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    document.getElementById('cropSection').classList.add('hidden');
    document.getElementById('cropImage').src = '';
    document.getElementById('photoInput').value = '';
    hideFeedback();
}

function saveCroppedPhoto() {
    if (!cropper) return;

    const btn = document.getElementById('btnSavePhoto');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fi fi-rr-spinner animate-spin"></i> Menyimpan...';
    btn.disabled = true;

    // Get cropped canvas as base64
    const canvas = cropper.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });

    const base64 = canvas.toDataURL('image/png');

    // Send to server via AJAX
    fetch('{{ route("profile.photo") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ cropped_image: base64 }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showFeedback(data.message, true);

            // Update all photos on page
            const currentPhoto = document.getElementById('currentPhoto');
            const photoInitials = document.getElementById('photoInitials');
            const headerPhoto = document.getElementById('headerPhoto');
            const headerInitials = document.getElementById('headerInitials');

            // Update current photo section
            if (currentPhoto) {
                currentPhoto.src = data.photo_url;
            } else if (photoInitials) {
                // Replace initials with img
                const img = document.createElement('img');
                img.src = data.photo_url;
                img.alt = 'Foto Profil';
                img.className = 'w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover shadow-lg border-2 border-white';
                img.id = 'currentPhoto';
                photoInitials.parentNode.replaceChild(img, photoInitials);
            }

            // Update header section
            if (headerPhoto) {
                headerPhoto.src = data.photo_url;
            } else if (headerInitials) {
                const img = document.createElement('img');
                img.src = data.photo_url;
                img.alt = 'Foto';
                img.className = 'w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover shadow-md';
                img.id = 'headerPhoto';
                headerInitials.parentNode.replaceChild(img, headerInitials);
            }

            // Hide crop section
            cancelCrop();
        } else {
            showFeedback(data.message || 'Gagal menyimpan foto.', false);
        }
    })
    .catch(err => {
        showFeedback('Terjadi kesalahan saat menyimpan foto.', false);
        console.error(err);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function showFeedback(msg, success) {
    const el = document.getElementById('photoFeedback');
    el.textContent = msg;
    el.className = 'mt-4 px-4 py-2.5 rounded-xl text-xs font-medium ' + 
        (success ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200');
    el.classList.remove('hidden');

    if (success) {
        setTimeout(() => el.classList.add('hidden'), 4000);
    }
}

function hideFeedback() {
    document.getElementById('photoFeedback').classList.add('hidden');
}
</script>
@endsection
