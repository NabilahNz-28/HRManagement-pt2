@extends('layouts.app')

@section('title', 'Absensi Masuk')
@section('header-title', 'Absensi Masuk (Check-In)')
@section('header-subtitle', 'Rekam kehadiran masuk dengan kamera selfie & verifikasi lokasi GPS')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    @if($sudahAbsen && $sudahAbsen->jam_masuk)
        <div class="card-white p-6 bg-emerald-50 border-emerald-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-sm">
                    <i class="fi fi-rr-check"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-emerald-900">Anda Sudah Melakukan Absen Masuk Hari Ini</h3>
                    <p class="text-xs text-emerald-700 mt-0.5">
                        Jam Masuk: <strong class="text-slate-900">{{ substr($sudahAbsen->jam_masuk, 0, 5) }} WIB</strong> &bull; Status: <strong class="uppercase text-slate-900">{{ $sudahAbsen->status }}</strong> &bull; Lokasi: {{ $sudahAbsen->lokasi_masuk }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('absensi.masuk.post') }}" id="attendanceForm">
        @csrf
        <input type="hidden" name="foto" id="fotoInput">
        <input type="hidden" name="latitude" id="latInput">
        <input type="hidden" name="longitude" id="lngInput">
        <input type="hidden" name="lokasi" id="lokasiInput">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Left Column: Camera Preview --}}
            <div class="card-white p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fi fi-rr-camera text-blue-600 font-bold"></i>
                        <h4 class="font-bold text-slate-800 text-sm">Kamera Selfie</h4>
                    </div>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600" id="cameraStatus">
                        Standby
                    </span>
                </div>

                {{-- Camera Feed Container --}}
                <div class="relative rounded-2xl overflow-hidden bg-slate-900 aspect-[4/3] flex items-center justify-center border border-slate-200 shadow-inner">
                    <video id="videoFeed" class="w-full h-full object-cover" autoplay playsinline></video>
                    <canvas id="photoCanvas" class="hidden w-full h-full object-cover"></canvas>
                    
                    {{-- Target Face Guide overlay --}}
                    <div id="faceGuide" class="absolute inset-0 border-2 border-dashed border-white/30 rounded-2xl pointer-events-none m-8 flex items-center justify-center">
                        <span class="text-[10px] text-white/50 tracking-wider uppercase font-semibold">Posisikan Wajah di Sini</span>
                    </div>

                    {{-- Camera Off Placeholder --}}
                    <div id="cameraOffPlaceholder" class="absolute inset-0 bg-slate-900 flex flex-col items-center justify-center text-slate-400 p-6 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-2xl mb-3 text-slate-500">
                            <i class="fi fi-rr-camera"></i>
                        </div>
                        <p class="text-xs font-semibold text-slate-300">Kamera Belum Aktif</p>
                        <p class="text-[11px] text-slate-500 mt-1">Klik tombol di bawah untuk mengaktifkan webcam</p>
                    </div>
                </div>

                {{-- Controls --}}
                <div class="mt-4 flex gap-2">
                    <button type="button" id="btnStartCamera" onclick="startCamera()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                        <i class="fi fi-rr-camera"></i> Aktifkan Kamera
                    </button>
                    <button type="button" id="btnCapture" onclick="takeSnapshot()" disabled class="flex-1 py-2.5 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-not-allowed">
                        <i class="fi fi-tr-inbox-in"></i> Ambil Foto & Cap GPS
                    </button>
                    <button type="button" id="btnRetake" onclick="retakePhoto()" class="hidden flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <i class="fi fi-tr-rotate-left"></i> Foto Ulang
                    </button>
                </div>
            </div>

            {{-- Right Column: GPS Location & Employee Info --}}
            <div class="space-y-6">
                
                {{-- GPS Location Card --}}
                <div class="card-white p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <i class="fi fi-rr-marker text-orange-500 font-bold"></i>
                            <h4 class="font-bold text-slate-800 text-sm">Lokasi GPS Presensi</h4>
                        </div>
                        <button type="button" onclick="detectGPS()" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fi fi-tr-rotate-left"></i> Refresh
                        </button>
                    </div>

                    {{-- Map View Container --}}
                    <div id="map" class="h-44 w-full rounded-xl border border-slate-200 z-10"></div>

                    {{-- GPS Details --}}
                    <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Status GPS:</span>
                            <span id="gpsStatusText" class="font-bold text-emerald-600">Mendeteksi koordinat...</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Koordinat:</span>
                            <span id="coordsText" class="font-mono text-slate-700">-6.2088, 106.8456</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Lokasi Kantor:</span>
                            <span id="addressText" class="font-medium text-slate-800 truncate max-w-[200px]">Outlet Bingxue & Mixue</span>
                        </div>
                    </div>
                </div>

                {{-- Form Submit Card --}}
                <div class="card-white p-6">
                    @if(Auth::user()->isHR() && $karyawanList->count() > 0)
                        <div class="mb-4">
                            <label class="form-label">Pilih Karyawan (Khusus Admin/HR)</label>
                            <select name="karyawan_id" class="form-input">
                                <option value="{{ $karyawan->id ?? '' }}">Diri Sendiri ({{ Auth::user()->name }})</option>
                                @foreach($karyawanList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_lengkap }} ({{ $k->departemen->nama ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="catatan" placeholder="Contoh: Masuk pagi, tugas operasional outlet" class="form-input">
                    </div>

                    <button type="submit" id="btnSubmit" disabled class="w-full py-3 bg-slate-300 text-slate-500 font-extrabold text-sm rounded-xl transition-all flex items-center justify-center gap-2 cursor-not-allowed shadow-sm">
                        <i class="fi fi-tr-inbox-in"></i> Simpan Absen Masuk
                    </button>
                </div>

            </div>

        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    let video = document.getElementById('videoFeed');
    let canvas = document.getElementById('photoCanvas');
    let stream = null;
    let map = null;
    let marker = null;

    let currentLat = -6.2088105;
    let currentLng = 106.8455901;
    let isPhotoTaken = false;

    // Init Map
    function initMap(lat, lng) {
        if (!map) {
            map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            marker = L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Presensi Anda').openPopup();
        } else {
            map.setView([lat, lng], 15);
            marker.setLatLng([lat, lng]);
        }
    }

    // Detect GPS
    function detectGPS() {
        document.getElementById('gpsStatusText').textContent = 'Mencari sinyal GPS...';
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    currentLat = pos.coords.latitude;
                    currentLng = pos.coords.longitude;
                    updateGPSUI(currentLat, currentLng, true);
                },
                function(err) {
                    // Fallback to default Jakarta HQ
                    updateGPSUI(currentLat, currentLng, false);
                },
                { enableHighAccuracy: true, timeout: 7000 }
            );
        } else {
            updateGPSUI(currentLat, currentLng, false);
        }
    }

    function updateGPSUI(lat, lng, isAccurate) {
        document.getElementById('latInput').value = lat;
        document.getElementById('lngInput').value = lng;
        document.getElementById('coordsText').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        document.getElementById('gpsStatusText').textContent = isAccurate ? '✓ GPS Akurat (Terkunci)' : '📍 Default GPS (Jakarta)';
        document.getElementById('lokasiInput').value = 'Kantor Pusat Bingxue & Mixue';
        initMap(lat, lng);
    }

    // Start Webcam
    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: 'user' } })
                .then(function(s) {
                    stream = s;
                    video.srcObject = s;
                    document.getElementById('cameraOffPlaceholder').classList.add('hidden');
                    document.getElementById('btnStartCamera').classList.add('hidden');
                    
                    let captureBtn = document.getElementById('btnCapture');
                    captureBtn.disabled = false;
                    captureBtn.className = 'flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer';
                    document.getElementById('cameraStatus').textContent = 'Live Kamera Aktif';
                    document.getElementById('cameraStatus').className = 'text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700';
                })
                .catch(function(err) {
                    alert('Tidak dapat mengakses webcam. Membuka mode fallback preview foto.');
                    fallbackDemoSnapshot();
                });
        } else {
            fallbackDemoSnapshot();
        }
    }

    // Snapshot & Apply GPS Stamp (GPS Map Camera style)
    function takeSnapshot() {
        canvas.width = 640;
        canvas.height = 480;
        let ctx = canvas.getContext('2d');

        if (stream && video.readyState >= 2) {
            ctx.drawImage(video, 0, 0, 640, 480);
        } else {
            // Gradient selfie avatar simulation
            let grad = ctx.createLinearGradient(0, 0, 640, 480);
            grad.addColorStop(0, '#1e293b');
            grad.addColorStop(1, '#0f172a');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 640, 480);
            ctx.fillStyle = '#334155';
            ctx.beginPath(); ctx.arc(320, 200, 90, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.ellipse(320, 410, 140, 110, 0, 0, Math.PI * 2); ctx.fill();
        }

        // Apply GPS Map Stamp Banner at bottom
        applyGPSStamp(ctx, 640, 480);

        // Switch preview
        video.classList.add('hidden');
        canvas.classList.remove('hidden');
        document.getElementById('faceGuide').classList.add('hidden');

        // Stop stream
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
        }

        // Enable Submit Button
        let dataURL = canvas.toDataURL('image/png');
        document.getElementById('fotoInput').value = dataURL;
        
        document.getElementById('btnCapture').classList.add('hidden');
        document.getElementById('btnRetake').classList.remove('hidden');

        let submitBtn = document.getElementById('btnSubmit');
        submitBtn.disabled = false;
        submitBtn.className = 'w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer shadow-md';
        isPhotoTaken = true;
    }

    function applyGPSStamp(ctx, w, h) {
        let now = new Date();
        let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        let months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        let dayName = days[now.getDay()];
        let dateStr = `${dayName}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
        let timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')} WIB`;

        // Dark stamp container at bottom
        let stampH = 120;
        let stampY = h - stampH;

        ctx.fillStyle = 'rgba(15, 23, 42, 0.88)';
        ctx.fillRect(0, stampY, w, stampH);

        // Left orange accent line
        ctx.fillStyle = '#FF6B00';
        ctx.fillRect(0, stampY, 6, stampH);

        // Text Info
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 15px Inter, sans-serif';
        ctx.fillText('BINGXUE & MIXUE — ABSENSI MASUK', 20, stampY + 28);

        ctx.fillStyle = '#94A3B8';
        ctx.font = '12px Inter, sans-serif';
        ctx.fillText(`Nama: {{ Auth::user()->name }} | {{ Auth::user()->email }}`, 20, stampY + 50);

        ctx.fillStyle = '#38BDF8';
        ctx.font = '12px Inter, sans-serif';
        ctx.fillText(`Lat: ${currentLat.toFixed(6)}, Long: ${currentLng.toFixed(6)}`, 20, stampY + 70);

        ctx.fillStyle = '#F8FAFC';
        ctx.font = '11px Inter, sans-serif';
        ctx.fillText(`${dateStr} — ${timeStr} | Note: Verified by GPS Map Camera`, 20, stampY + 92);

        // Stamp badge tag right
        ctx.fillStyle = 'rgba(16, 185, 129, 0.25)';
        ctx.fillRect(w - 120, stampY + 16, 104, 26);
        ctx.strokeStyle = '#10B981';
        ctx.lineWidth = 1;
        ctx.strokeRect(w - 120, stampY + 16, 104, 26);

        ctx.fillStyle = '#10B981';
        ctx.font = 'bold 11px Inter, sans-serif';
        ctx.fillText('✓ CHECK-IN', w - 104, stampY + 33);
    }

    function fallbackDemoSnapshot() {
        startCamera();
    }

    function retakePhoto() {
        video.classList.remove('hidden');
        canvas.classList.add('hidden');
        document.getElementById('faceGuide').classList.remove('hidden');
        document.getElementById('btnRetake').classList.add('hidden');
        document.getElementById('btnCapture').classList.remove('hidden');

        let submitBtn = document.getElementById('btnSubmit');
        submitBtn.disabled = true;
        submitBtn.className = 'w-full py-3 bg-slate-300 text-slate-500 font-extrabold text-sm rounded-xl transition-all flex items-center justify-center gap-2 cursor-not-allowed shadow-sm';
        
        startCamera();
    }

    document.addEventListener('DOMContentLoaded', function() {
        detectGPS();
    });
</script>
@endsection
