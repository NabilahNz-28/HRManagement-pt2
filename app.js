// ============================================================
// HR MANAGEMENT SYSTEM — app.js
// ============================================================

// --------- STATE ---------
let currentPage = 'dashboard';
let currentAttendanceTab = 'masuk';
let cameraStream = null;
let photoTaken = false;
let currentLocation = null;
let editingEmployeeId = null;
let dashboardInitialized = false;  // prevent looping re-init
let analyticsInitialized = false;  // prevent looping re-init

// --------- DATA ---------
let employees = [
  { id: 1, name: 'Ahmad Fauzi', nik: 'EMP-001', email: 'ahmad@company.co.id', phone: '+62 812 1111 0001', position: 'Senior Engineer', dept: 'Engineering', status: 'aktif', joinDate: '2022-03-15', initials: 'AF', color: '#f97316' },
  { id: 2, name: 'Siti Rahma', nik: 'EMP-002', email: 'siti@company.co.id', phone: '+62 812 2222 0002', position: 'Marketing Lead', dept: 'Marketing', status: 'aktif', joinDate: '2021-07-01', initials: 'SR', color: '#14b8a6' },
  { id: 3, name: 'Budi Santoso', nik: 'EMP-003', email: 'budi@company.co.id', phone: '+62 812 3333 0003', position: 'Finance Analyst', dept: 'Finance', status: 'aktif', joinDate: '2020-01-10', initials: 'BS', color: '#8b5cf6' },
  { id: 4, name: 'Dewi Lestari', nik: 'EMP-004', email: 'dewi@company.co.id', phone: '+62 812 4444 0004', position: 'HR Manager', dept: 'HR', status: 'aktif', joinDate: '2019-05-20', initials: 'DL', color: '#ec4899' },
  { id: 5, name: 'Reza Pratama', nik: 'EMP-005', email: 'reza@company.co.id', phone: '+62 812 5555 0005', position: 'Backend Developer', dept: 'Engineering', status: 'aktif', joinDate: '2023-02-01', initials: 'RP', color: '#22c55e' },
  { id: 6, name: 'Nurul Hidayah', nik: 'EMP-006', email: 'nurul@company.co.id', phone: '+62 812 6666 0006', position: 'Customer Support', dept: 'Support', status: 'aktif', joinDate: '2022-11-15', initials: 'NH', color: '#f59e0b' },
  { id: 7, name: 'Andi Wijaya', nik: 'EMP-007', email: 'andi@company.co.id', phone: '+62 812 7777 0007', position: 'UI/UX Designer', dept: 'Engineering', status: 'nonaktif', joinDate: '2021-04-12', initials: 'AW', color: '#6366f1' },
  { id: 8, name: 'Rina Kusuma', nik: 'EMP-008', email: 'rina@company.co.id', phone: '+62 812 8888 0008', position: 'Marketing Specialist', dept: 'Marketing', status: 'aktif', joinDate: '2023-06-01', initials: 'RK', color: '#ef4444' },
];

let attendanceLogs = [
  { id: 1, empId: 1, empName: 'Ahmad Fauzi', dept: 'Engineering', date: '2026-09-03', checkIn: '08:05', checkOut: '17:12', location: 'Kantor Pusat Jakarta', coords: '-6.2088, 106.8456', status: 'Tepat Waktu', duration: '9j 7m' },
  { id: 2, empId: 2, empName: 'Siti Rahma', dept: 'Marketing', date: '2026-09-03', checkIn: '08:22', checkOut: '17:30', location: 'Kantor Pusat Jakarta', coords: '-6.2089, 106.8457', status: 'Terlambat', duration: '9j 8m' },
  { id: 3, empId: 3, empName: 'Budi Santoso', dept: 'Finance', date: '2026-09-03', checkIn: '07:55', checkOut: '17:00', location: 'Kantor Pusat Jakarta', coords: '-6.2090, 106.8455', status: 'Tepat Waktu', duration: '9j 5m' },
  { id: 4, empId: 4, empName: 'Dewi Lestari', dept: 'HR', date: '2026-09-03', checkIn: '08:01', checkOut: '', location: 'Kantor Pusat Jakarta', coords: '-6.2088, 106.8456', status: 'Hadir', duration: '-' },
  { id: 5, empId: 5, empName: 'Reza Pratama', dept: 'Engineering', date: '2026-09-03', checkIn: '09:15', checkOut: '18:20', location: 'WFH - Tangerang', coords: '-6.2487, 106.6226', status: 'Terlambat', duration: '9j 5m' },
];

let leaveRequests = [
  { id: 1, emp: 'Ahmad Fauzi', type: 'Cuti Tahunan', start: '2026-09-10', end: '2026-09-12', days: 3, reason: 'Liburan keluarga', status: 'pending' },
  { id: 2, emp: 'Siti Rahma', type: 'Cuti Sakit', start: '2026-09-05', end: '2026-09-05', days: 1, reason: 'Sakit demam', status: 'approved' },
  { id: 3, emp: 'Budi Santoso', type: 'Izin', start: '2026-09-04', end: '2026-09-04', days: 1, reason: 'Keperluan keluarga mendesak', status: 'approved' },
  { id: 4, emp: 'Reza Pratama', type: 'Cuti Tahunan', start: '2026-09-20', end: '2026-09-25', days: 6, reason: 'Pernikahan saudara', status: 'pending' },
  { id: 5, emp: 'Nurul Hidayah', type: 'Cuti Besar', start: '2026-10-01', end: '2026-10-14', days: 14, reason: 'Cuti besar tahunan', status: 'rejected' },
  { id: 6, emp: 'Dewi Lestari', type: 'Izin', start: '2026-09-03', end: '2026-09-03', days: 1, reason: 'Urusan administratif', status: 'pending' },
];

// --------- NAVIGATION ---------
function navigate(page) {
  currentPage = page;
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const pageEl = document.getElementById('page-' + page);
  if (pageEl) {
    pageEl.classList.add('active');
    pageEl.classList.remove('fade-in');
    void pageEl.offsetWidth;
    pageEl.classList.add('fade-in');
  }
  document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
  document.querySelectorAll('.sidebar-link').forEach(l => {
    if (l.getAttribute('onclick') && l.getAttribute('onclick').includes("'" + page + "'")) {
      l.classList.add('active');
    }
  });
  // Trigger page-specific init (guard against re-init loops)
  if (page === 'dashboard') {
    if (!dashboardInitialized) {
      dashboardInitialized = true;
      initDashboard();
    }
  }
  if (page === 'karyawan') renderEmployeeTable();
  if (page === 'laporan') renderReportTable();
  if (page === 'cuti') renderLeaveTable();
  if (page === 'analytics') {
    if (!analyticsInitialized) {
      analyticsInitialized = true;
      initAnalytics();
    }
  }
  lucide.createIcons();
}

// --------- LIVE TIME ---------
function updateTime() {
  const el = document.getElementById('liveTime');
  if (!el) return;
  const now = new Date();
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const day = days[now.getDay()];
  const date = now.getDate();
  const month = months[now.getMonth()];
  const year = now.getFullYear();
  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const s = String(now.getSeconds()).padStart(2, '0');
  el.textContent = `${day}, ${date} ${month} ${year} — ${h}:${m}:${s}`;
}
setInterval(updateTime, 1000);
updateTime();

// --------- DASHBOARD INIT ---------
function initDashboard() {
  renderRecentAttendance();
  renderRecentLeave();
  initCharts();
}

function renderRecentAttendance() {
  const el = document.getElementById('recentAttendance');
  if (!el) return;
  const recent = attendanceLogs.slice(0, 5);
  el.innerHTML = recent.map(a => {
    const emp = employees.find(e => e.id === a.empId) || {};
    const statusColor = a.status === 'Tepat Waktu' ? '#22c55e' : a.status === 'Terlambat' ? '#f97316' : '#14b8a6';
    return `<div class="flex items-center gap-3 py-2 table-row rounded-lg px-2 transition-colors">
      <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:${emp.color || '#f97316'}22; color:${emp.color || '#f97316'};">${emp.initials || 'NA'}</div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-medium truncate" style="color:#f1f5f9;">${a.empName}</div>
        <div class="text-xs" style="color:#64748b;">${a.dept} · Masuk ${a.checkIn}</div>
      </div>
      <span class="status-badge" style="background:${statusColor}22; color:${statusColor};">
        <span class="pulse-dot w-1.5 h-1.5 rounded-full" style="background:${statusColor};"></span>
        ${a.status}
      </span>
    </div>`;
  }).join('');
}

function renderRecentLeave() {
  const el = document.getElementById('recentLeave');
  if (!el) return;
  const recent = leaveRequests.slice(0, 4);
  el.innerHTML = recent.map(l => {
    const { bg, text, label } = getStatusStyle(l.status);
    return `<div class="flex items-center gap-3 py-2 table-row rounded-lg px-2 transition-colors">
      <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:#f9731622; color:#f97316;">${l.emp.split(' ').map(w => w[0]).join('').slice(0,2)}</div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-medium truncate" style="color:#f1f5f9;">${l.emp}</div>
        <div class="text-xs" style="color:#64748b;">${l.type} · ${l.days} hari</div>
      </div>
      <span class="status-badge" style="background:${bg}; color:${text};">${label}</span>
    </div>`;
  }).join('');
}

function getStatusStyle(status) {
  if (status === 'approved') return { bg: '#22c55e22', text: '#22c55e', label: 'Disetujui' };
  if (status === 'rejected') return { bg: '#ef444422', text: '#ef4444', label: 'Ditolak' };
  return { bg: '#eab30822', text: '#eab308', label: 'Pending' };
}

function initCharts() {
  // Attendance line chart
  const ctx1 = document.getElementById('attendanceChart');
  if (!ctx1) return;
  // Destroy existing chart instance if any
  const existing1 = Chart.getChart(ctx1);
  if (existing1) existing1.destroy();

  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep'];
  const data = [88, 91, 89, 93, 95, 92, 94, 90, 88];
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Kehadiran (%)',
        data: data,
        borderColor: '#f97316',
        backgroundColor: 'rgba(249,115,22,0.08)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#f97316',
        pointRadius: 4,
        pointHoverRadius: 6,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1a1a2e', titleColor: '#f1f5f9', bodyColor: '#94a3b8', borderColor: '#2d2d4a', borderWidth: 1 } },
      scales: {
        x: { grid: { color: '#1a1a2e' }, ticks: { color: '#64748b', font: { size: 11 } } },
        y: { grid: { color: '#1a1a2e' }, ticks: { color: '#64748b', font: { size: 11 } }, min: 80, max: 100 }
      }
    }
  });

  // Dept doughnut chart
  const ctx2 = document.getElementById('deptChart');
  if (!ctx2) return;
  const existing2 = Chart.getChart(ctx2);
  if (existing2) existing2.destroy();

  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Engineering', 'Marketing', 'Finance', 'HR & Support'],
      datasets: [{ data: [38, 24, 19, 46], backgroundColor: ['#f97316', '#14b8a6', '#8b5cf6', '#eab308'], borderWidth: 0, hoverBorderWidth: 2, hoverBorderColor: '#fff' }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '70%',
      plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1a1a2e', titleColor: '#f1f5f9', bodyColor: '#94a3b8', borderColor: '#2d2d4a', borderWidth: 1 } }
    }
  });
}

// --------- ANALYTICS CHARTS ---------
function initAnalytics() {
  setTimeout(() => {
    const ctx3 = document.getElementById('weeklyChart');
    if (ctx3) {
      const existing3 = Chart.getChart(ctx3);
      if (existing3) existing3.destroy();
      new Chart(ctx3, {
        type: 'bar',
        data: {
          labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
          datasets: [
            { label: 'Hadir', data: [112, 108, 115, 110, 105, 113, 109, 116, 111, 107], backgroundColor: '#f97316', borderRadius: 4 },
            { label: 'Terlambat', data: [8, 12, 5, 9, 7, 6, 11, 4, 8, 10], backgroundColor: '#eab308', borderRadius: 4 },
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { labels: { color: '#94a3b8', font: { size: 11 } } }, tooltip: { backgroundColor: '#1a1a2e', titleColor: '#f1f5f9', bodyColor: '#94a3b8', borderColor: '#2d2d4a', borderWidth: 1 } },
          scales: {
            x: { grid: { color: '#1a1a2e' }, ticks: { color: '#64748b', font: { size: 11 } }, stacked: true },
            y: { grid: { color: '#1a1a2e' }, ticks: { color: '#64748b', font: { size: 11 } }, stacked: true }
          }
        }
      });
    }
    const ctx4 = document.getElementById('statusChart');
    if (ctx4) {
      const existing4 = Chart.getChart(ctx4);
      if (existing4) existing4.destroy();
      new Chart(ctx4, {
        type: 'doughnut',
        data: {
          labels: ['Tepat Waktu', 'Terlambat', 'Absen', 'Cuti/Izin'],
          datasets: [{ data: [78, 12, 6, 4], backgroundColor: ['#22c55e', '#eab308', '#ef4444', '#14b8a6'], borderWidth: 0 }]
        },
        options: {
          responsive: true, maintainAspectRatio: false, cutout: '70%',
          plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1a1a2e', titleColor: '#f1f5f9', bodyColor: '#94a3b8', borderColor: '#2d2d4a', borderWidth: 1 } }
        }
      });
    }
    lucide.createIcons();
  }, 100);
}

// --------- ABSENSI ---------
function initAbsensi() {
  const now = new Date();
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const label = document.getElementById('todayDateLabel');
  if (label) label.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

  const today = formatDate(now);
  const todayLogs = attendanceLogs.filter(l => l.date === today);
  renderTodayLog(todayLogs);

  // Auto get location
  getLocation();
}

function formatDate(d) {
  return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
}

function switchAttendanceTab(tab) {
  currentAttendanceTab = tab;
  const btnMasuk = document.getElementById('tabMasuk');
  const btnPulang = document.getElementById('tabPulang');
  const submitText = document.getElementById('submitBtnText');
  if (tab === 'masuk') {
    btnMasuk.style.background = 'linear-gradient(135deg, #f97316, #ea6c0a)';
    btnMasuk.style.color = 'white';
    btnMasuk.style.border = 'none';
    btnPulang.style.background = 'transparent';
    btnPulang.style.color = '#94a3b8';
    btnPulang.style.border = '1px solid #2d2d4a';
    if (submitText) submitText.textContent = 'Catat Absen Masuk';
  } else {
    btnPulang.style.background = 'linear-gradient(135deg, #14b8a6, #0d9488)';
    btnPulang.style.color = 'white';
    btnPulang.style.border = 'none';
    btnMasuk.style.background = 'transparent';
    btnMasuk.style.color = '#94a3b8';
    btnMasuk.style.border = '1px solid #2d2d4a';
    if (submitText) submitText.textContent = 'Catat Absen Pulang';
  }
}

function startCamera() {
  if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 960 } } })
      .then(stream => {
        cameraStream = stream;
        const video = document.getElementById('cameraFeed');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('captureCanvas').style.display = 'none';
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('btnCapture').disabled = false;
        document.getElementById('btnCapture').style.background = 'linear-gradient(135deg, #14b8a6, #0d9488)';
        document.getElementById('btnCapture').style.color = 'white';
        document.getElementById('btnCapture').style.cursor = 'pointer';
        document.getElementById('btnStartCamera').style.display = 'none';
      })
      .catch(err => {
        console.error('Camera error:', err);
        // Show demo mode
        showDemoCamera();
      });
  } else {
    showDemoCamera();
  }
}

function showDemoCamera() {
  // Create a demo canvas with gradient instead of real camera
  const video = document.getElementById('cameraFeed');
  video.style.display = 'none';
  const placeholder = document.getElementById('cameraPlaceholder');
  placeholder.style.display = 'flex';
  placeholder.innerHTML = `
    <div style="text-align:center; padding: 20px;">
      <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#f97316,#14b8a6);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="4"/><path d="M20 4h-3.17L15 2H9L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/></svg>
      </div>
      <p style="color:#f1f5f9;font-size:14px;font-weight:600;margin-bottom:8px;">Mode Demo</p>
      <p style="color:#64748b;font-size:12px;">Kamera tidak tersedia di browser ini.</p>
      <p style="color:#64748b;font-size:12px;">Klik "Ambil Foto Demo" untuk melanjutkan.</p>
    </div>
  `;
  document.getElementById('btnStartCamera').style.display = 'none';
  document.getElementById('btnCapture').disabled = false;
  document.getElementById('btnCapture').style.background = 'linear-gradient(135deg, #14b8a6, #0d9488)';
  document.getElementById('btnCapture').style.color = 'white';
  document.getElementById('btnCapture').style.cursor = 'pointer';
  document.getElementById('btnCapture').querySelector('[data-lucide="aperture"]');
  const capBtn = document.getElementById('btnCapture');
  capBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="12" cy="12" r="4"/><path d="M20 4h-3.17L15 2H9L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/></svg> Ambil Foto Demo';
  photoTaken = false;
}

function capturePhoto() {
  if (!document.getElementById('btnCapture').disabled === false) return;
  const video = document.getElementById('cameraFeed');
  const canvas = document.getElementById('captureCanvas');
  const ctx = canvas.getContext('2d');

  if (cameraStream && video.readyState >= 2) {
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  } else {
    // Demo: draw gradient face placeholder
    canvas.width = 640;
    canvas.height = 480;
    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
    grad.addColorStop(0, '#1a1a2e');
    grad.addColorStop(1, '#0f0f17');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    // Draw silhouette
    ctx.fillStyle = '#2d2d4a';
    ctx.beginPath(); ctx.arc(320, 180, 80, 0, Math.PI * 2); ctx.fill();
    ctx.fillStyle = '#22223a';
    ctx.beginPath(); ctx.ellipse(320, 380, 120, 100, 0, 0, Math.PI * 2); ctx.fill();
    ctx.fillStyle = '#f97316';
    ctx.font = 'bold 16px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('📸 Foto Demo', 320, 440);
  }

  video.style.display = 'none';
  canvas.style.display = 'block';
  if (cameraStream) {
    cameraStream.getTracks().forEach(t => t.stop());
    cameraStream = null;
  }
  photoTaken = true;
  document.getElementById('btnCapture').style.display = 'none';
  document.getElementById('btnRetake').style.display = 'block';
  document.getElementById('btnStartCamera').style.display = 'none';
  document.getElementById('cameraPlaceholder').style.display = 'none';
}

function retakePhoto() {
  photoTaken = false;
  const video = document.getElementById('cameraFeed');
  const canvas = document.getElementById('captureCanvas');
  canvas.style.display = 'none';
  video.style.display = 'block';
  document.getElementById('btnRetake').style.display = 'none';
  document.getElementById('btnCapture').style.display = 'flex';
  document.getElementById('btnCapture').disabled = false;
  document.getElementById('btnStartCamera').style.display = 'none';
  document.getElementById('attendanceResult').style.display = 'none';
  startCamera();
}

function stopCamera() {
  if (cameraStream) {
    cameraStream.getTracks().forEach(t => t.stop());
    cameraStream = null;
  }
}

// --------- GEOLOCATION ---------
function getLocation() {
  const btn = document.getElementById('btnGetLocation');
  const statusEl = document.getElementById('locationStatus');
  const btnText = document.getElementById('locationBtnText');
  if (statusEl) statusEl.textContent = 'Mendeteksi lokasi...';
  if (btnText) btnText.textContent = 'Mendeteksi...';
  if (btn) btn.disabled = true;

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        currentLocation = { lat: pos.coords.latitude, lng: pos.coords.longitude, accuracy: pos.coords.accuracy };
        updateLocationUI(currentLocation);
      },
      (err) => {
        // Use a demo location (Jakarta)
        currentLocation = { lat: -6.2088105, lng: 106.8455901, accuracy: 15, demo: true };
        updateLocationUI(currentLocation);
      },
      { timeout: 8000, enableHighAccuracy: true }
    );
  } else {
    currentLocation = { lat: -6.2088105, lng: 106.8455901, accuracy: 15, demo: true };
    updateLocationUI(currentLocation);
  }
}

function updateLocationUI(loc) {
  const statusEl = document.getElementById('locationStatus');
  const coordsEl = document.getElementById('locationCoords');
  const addrEl = document.getElementById('locationAddress');
  const infoEl = document.getElementById('locationInfo');
  const btn = document.getElementById('btnGetLocation');
  const btnText = document.getElementById('locationBtnText');

  const lat = loc.lat.toFixed(6);
  const lng = loc.lng.toFixed(6);

  if (statusEl) statusEl.textContent = loc.demo ? '📍 Lokasi Demo (Jakarta)' : `📍 Akurasi ±${Math.round(loc.accuracy)}m`;
  if (coordsEl) coordsEl.textContent = `Lat ${lat}, Long ${lng}`;
  if (addrEl) addrEl.textContent = loc.demo ? 'Jl. Sudirman, Jakarta Pusat' : `Lat ${lat}, Long ${lng}`;
  if (infoEl) infoEl.style.display = 'block';
  if (btn) btn.disabled = false;
  if (btnText) btnText.textContent = '✓ Lokasi Terdeteksi';
  if (btn) {
    btn.style.borderColor = '#22c55e';
    btn.style.color = '#22c55e';
  }

  // Update map visual
  updateMapVisual(loc);
}

function updateMapVisual(loc) {
  const mapContainer = document.getElementById('mapContainer');
  if (!mapContainer) return;
  // Add a "map" visual overlay
  const lat = loc.lat.toFixed(4);
  const lng = loc.lng.toFixed(4);
  const mapOverlay = mapContainer.querySelector('.absolute.inset-0.flex');
  if (mapOverlay) {
    mapOverlay.innerHTML = `<span class="text-xs font-medium px-3 py-1 rounded-full" style="background:rgba(34,197,94,0.2); color:#22c55e; backdrop-filter:blur(4px);">📍 ${lat}, ${lng}</span>`;
  }
}

// --------- SUBMIT ATTENDANCE ---------
function submitAttendance() {
  const empSelect = document.getElementById('employeeSelect');
  const empId = parseInt(empSelect.value);

  if (!empId) { showToast('Pilih karyawan terlebih dahulu!', 'error'); return; }
  if (!photoTaken) { showToast('Ambil foto selfie terlebih dahulu!', 'error'); return; }
  if (!currentLocation) { showToast('Dapatkan lokasi GPS terlebih dahulu!', 'error'); return; }

  const emp = employees.find(e => e.id === empId);
  if (!emp) return;

  const now = new Date();
  const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
  const dateStr = formatDate(now);
  const lat = currentLocation.lat.toFixed(6);
  const lng = currentLocation.lng.toFixed(6);
  const locationStr = currentLocation.demo ? 'Jl. Sudirman, Jakarta Pusat' : `Lat ${lat}, Long ${lng}`;

  const type = currentAttendanceTab;
  const note = document.getElementById('attendanceNote').value;

  // Build stamped photo
  createStampedPhoto(emp, type, now, locationStr, lat, lng);

  // Update log
  const today = formatDate(now);
  const existingLog = attendanceLogs.find(l => l.empId === empId && l.date === today);
  if (type === 'masuk') {
    const logEntry = {
      id: Date.now(), empId: empId, empName: emp.name, dept: emp.dept,
      date: today, checkIn: timeStr, checkOut: '', location: locationStr,
      coords: `${lat}, ${lng}`, status: determineStatus(now), duration: '-'
    };
    if (!existingLog) attendanceLogs.unshift(logEntry);
  } else if (type === 'pulang' && existingLog) {
    existingLog.checkOut = timeStr;
    existingLog.duration = calcDuration(existingLog.checkIn, timeStr);
  }

  // Show result
  document.getElementById('attendanceResult').style.display = 'block';
  document.getElementById('resultName').textContent = emp.name + ' — ' + emp.dept;
  document.getElementById('resultType').textContent = type === 'masuk' ? '🟢 Absen Masuk' : '🔴 Absen Pulang';
  document.getElementById('resultType').style.color = type === 'masuk' ? '#22c55e' : '#f97316';
  document.getElementById('resultTime').textContent = formatFullDateTime(now);
  document.getElementById('resultLocation').textContent = locationStr;
  document.getElementById('resultCoords').textContent = `Lat ${lat}, Long ${lng}`;

  const todayLogs = attendanceLogs.filter(l => l.date === today);
  renderTodayLog(todayLogs);
  showToast(`Absen ${type === 'masuk' ? 'masuk' : 'pulang'} ${emp.name} berhasil dicatat!`);

  // Scroll to result
  document.getElementById('attendanceResult').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function determineStatus(now) {
  const hours = now.getHours();
  const mins = now.getMinutes();
  if (hours < 8 || (hours === 8 && mins <= 15)) return 'Tepat Waktu';
  if (hours === 8 && mins <= 30) return 'Terlambat';
  return 'Terlambat';
}

function calcDuration(checkIn, checkOut) {
  if (!checkIn || !checkOut) return '-';
  const [ih, im] = checkIn.split(':').map(Number);
  const [oh, om] = checkOut.split(':').map(Number);
  const totalMin = (oh * 60 + om) - (ih * 60 + im);
  if (totalMin < 0) return '-';
  const h = Math.floor(totalMin / 60);
  const m = totalMin % 60;
  return `${h}j ${m}m`;
}

function formatFullDateTime(d) {
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const h = String(d.getHours()).padStart(2, '0');
  const m = String(d.getMinutes()).padStart(2, '0');
  return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()} ${h}:${m} WIB`;
}

// --------- STAMPED PHOTO (GPS Map Camera style) ---------
function createStampedPhoto(emp, type, now, locationStr, lat, lng) {
  const sourceCanvas = document.getElementById('captureCanvas');
  const destCanvas = document.getElementById('stampedCanvas');
  const ctx = destCanvas.getContext('2d');

  const W = sourceCanvas.width || 640;
  const H = sourceCanvas.height || 480;
  destCanvas.width = W;
  destCanvas.height = H;

  // Draw source photo
  ctx.drawImage(sourceCanvas, 0, 0, W, H);

  // === STAMP OVERLAY (GPS Map Camera style) ===
  const stampH = Math.round(H * 0.30);
  const stampY = H - stampH;

  // Dark gradient overlay for stamp area
  const grad = ctx.createLinearGradient(0, stampY - 40, 0, H);
  grad.addColorStop(0, 'rgba(0,0,0,0)');
  grad.addColorStop(0.3, 'rgba(0,0,0,0.7)');
  grad.addColorStop(1, 'rgba(0,0,0,0.92)');
  ctx.fillStyle = grad;
  ctx.fillRect(0, stampY - 40, W, stampH + 40);

  // Main stamp box
  ctx.fillStyle = 'rgba(15,15,23,0.88)';
  roundRect(ctx, 12, stampY - 4, W - 24, stampH - 4, 14);
  ctx.fill();

  // Orange left border accent
  ctx.fillStyle = '#f97316';
  roundRect(ctx, 12, stampY - 4, 5, stampH - 4, 3);
  ctx.fill();

  // Mini map thumbnail area (left side)
  const mapW = Math.round(W * 0.22);
  const mapH = Math.round(stampH * 0.72);
  const mapX = 26;
  const mapTopY = stampY + 12;

  ctx.fillStyle = '#1a1a2e';
  roundRect(ctx, mapX, mapTopY, mapW, mapH, 8);
  ctx.fill();

  // Draw mini map grid
  ctx.strokeStyle = 'rgba(249,115,22,0.2)';
  ctx.lineWidth = 1;
  for (let gx = mapX; gx < mapX + mapW; gx += 14) {
    ctx.beginPath(); ctx.moveTo(gx, mapTopY); ctx.lineTo(gx, mapTopY + mapH); ctx.stroke();
  }
  for (let gy = mapTopY; gy < mapTopY + mapH; gy += 14) {
    ctx.beginPath(); ctx.moveTo(mapX, gy); ctx.lineTo(mapX + mapW, gy); ctx.stroke();
  }

  // Map roads (simulated)
  ctx.strokeStyle = 'rgba(249,115,22,0.4)';
  ctx.lineWidth = 2;
  const mx = mapX + mapW / 2;
  const my = mapTopY + mapH / 2;
  ctx.beginPath(); ctx.moveTo(mapX, my); ctx.lineTo(mapX + mapW, my); ctx.stroke();
  ctx.beginPath(); ctx.moveTo(mx, mapTopY); ctx.lineTo(mx, mapTopY + mapH); ctx.stroke();

  // Map pin
  ctx.fillStyle = '#f97316';
  ctx.beginPath(); ctx.arc(mx, my - 4, 7, 0, Math.PI * 2); ctx.fill();
  ctx.fillStyle = 'white';
  ctx.beginPath(); ctx.arc(mx, my - 4, 3, 0, Math.PI * 2); ctx.fill();
  ctx.fillStyle = '#f97316';
  ctx.beginPath(); ctx.moveTo(mx - 5, my - 4); ctx.lineTo(mx + 5, my - 4); ctx.lineTo(mx, my + 5); ctx.fill();

  // Map border
  ctx.strokeStyle = '#f97316';
  ctx.lineWidth = 1.5;
  roundRect(ctx, mapX, mapTopY, mapW, mapH, 8);
  ctx.stroke();

  // === TEXT CONTENT ===
  const textX = mapX + mapW + 14;
  const maxTextW = W - textX - 16;
  const fs = Math.round(W / 45);

  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const dateLabel = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()} ${h}:${m} GMT+07.00`;

  // Location name (large bold)
  ctx.fillStyle = '#ffffff';
  ctx.font = `bold ${fs + 4}px Inter, Arial, sans-serif`;
  wrapText(ctx, locationStr, textX, stampY + 20, maxTextW, fs + 7);

  // Sub-address
  ctx.fillStyle = '#94a3b8';
  ctx.font = `${fs}px Inter, Arial, sans-serif`;
  const addrY = stampY + 20 + (fs + 7) * 2 + 8;
  ctx.fillText(truncateText(ctx, emp.dept + ' · ' + emp.name, maxTextW), textX, addrY);

  // Lat/Long
  ctx.fillStyle = '#14b8a6';
  ctx.font = `${fs - 1}px Inter, Arial, sans-serif`;
  ctx.fillText(`Lat ${lat}, Long ${lng}`, textX, addrY + fs + 4);

  // Date/Time
  ctx.fillStyle = '#cbd5e1';
  ctx.font = `${fs - 1}px Inter, Arial, sans-serif`;
  ctx.fillText(dateLabel, textX, addrY + (fs + 4) * 2 + 2);

  // Type badge
  const badgeColor = type === 'masuk' ? '#22c55e' : '#f97316';
  const badgeLabel = type === 'masuk' ? '✓ ABSEN MASUK' : '✓ ABSEN PULANG';
  ctx.fillStyle = badgeColor + '33';
  roundRect(ctx, textX, addrY + (fs + 4) * 3 + 6, Math.min(ctx.measureText(badgeLabel).width + 16, maxTextW), fs + 8, 4);
  ctx.fill();
  ctx.fillStyle = badgeColor;
  ctx.font = `bold ${fs - 1}px Inter, Arial, sans-serif`;
  ctx.fillText(badgeLabel, textX + 8, addrY + (fs + 4) * 3 + 6 + fs + 1);

  // Note
  ctx.fillStyle = '#64748b';
  ctx.font = `${fs - 2}px Inter, Arial, sans-serif`;
  ctx.fillText('Note: Captured by HR Management System', textX, H - 12);

  // Top-right logo
  ctx.fillStyle = '#f97316';
  ctx.font = `bold ${fs - 1}px Inter, Arial, sans-serif`;
  ctx.textAlign = 'right';
  ctx.fillText('🗺 GPS Map HR', W - 16, stampY + fs + 8);
  ctx.textAlign = 'left';
}

// Helper: round rect path
function roundRect(ctx, x, y, w, h, r) {
  ctx.beginPath();
  ctx.moveTo(x + r, y);
  ctx.lineTo(x + w - r, y); ctx.arcTo(x + w, y, x + w, y + r, r);
  ctx.lineTo(x + w, y + h - r); ctx.arcTo(x + w, y + h, x + w - r, y + h, r);
  ctx.lineTo(x + r, y + h); ctx.arcTo(x, y + h, x, y + h - r, r);
  ctx.lineTo(x, y + r); ctx.arcTo(x, y, x + r, y, r);
  ctx.closePath();
}

function wrapText(ctx, text, x, y, maxW, lineH) {
  const words = text.split(' ');
  let line = '';
  let yy = y;
  for (let n = 0; n < words.length; n++) {
    const testLine = line + words[n] + ' ';
    if (ctx.measureText(testLine).width > maxW && n > 0) {
      ctx.fillText(line, x, yy); line = words[n] + ' '; yy += lineH;
    } else { line = testLine; }
  }
  ctx.fillText(line, x, yy);
}

function truncateText(ctx, text, maxW) {
  if (ctx.measureText(text).width <= maxW) return text;
  while (text.length > 0 && ctx.measureText(text + '...').width > maxW) text = text.slice(0, -1);
  return text + '...';
}

function downloadStampedPhoto() {
  const canvas = document.getElementById('stampedCanvas');
  const link = document.createElement('a');
  link.download = `absensi_${new Date().toISOString().slice(0, 10)}_${Date.now()}.png`;
  link.href = canvas.toDataURL('image/png');
  link.click();
}

// --------- TODAY LOG RENDER ---------
function renderTodayLog(logs) {
  const el = document.getElementById('todayLog');
  if (!el) return;
  if (!logs || logs.length === 0) {
    el.innerHTML = '<p class="text-sm text-center py-8" style="color:#3d3d5c;">Belum ada absensi hari ini</p>';
    return;
  }
  el.innerHTML = logs.map(l => {
    const emp = employees.find(e => e.id === l.empId) || {};
    const statusColor = l.status === 'Tepat Waktu' ? '#22c55e' : l.status === 'Terlambat' ? '#f97316' : '#14b8a6';
    return `<div class="flex items-center gap-4 p-3 rounded-xl transition-colors" style="background:#141420;">
      <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:${emp.color || '#f97316'}22; color:${emp.color || '#f97316'};">${emp.initials || '??'}</div>
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <span class="text-sm font-semibold" style="color:#f1f5f9;">${l.empName}</span>
          <span class="status-badge" style="background:${statusColor}22; color:${statusColor};">${l.status}</span>
        </div>
        <div class="flex items-center gap-3 text-xs mt-1" style="color:#64748b;">
          <span>🏢 ${l.dept}</span>
          <span>📍 ${l.location}</span>
        </div>
      </div>
      <div class="text-right flex-shrink-0">
        <div class="text-sm font-semibold" style="color:#22c55e;">Masuk: ${l.checkIn}</div>
        <div class="text-xs mt-0.5" style="color:${l.checkOut ? '#f97316' : '#3d3d5c'};">Pulang: ${l.checkOut || '-'}</div>
      </div>
    </div>`;
  }).join('');
}

// --------- EMPLOYEE TABLE ---------
let employeeFilter = 'all';

function renderEmployeeTable(filter) {
  if (filter !== undefined) employeeFilter = filter;
  const tbody = document.getElementById('employeeTableBody');
  if (!tbody) return;
  const search = (document.getElementById('empSearch') || {}).value || '';
  let list = employees;
  if (employeeFilter !== 'all') list = list.filter(e => e.status === employeeFilter);
  if (search) list = list.filter(e => e.name.toLowerCase().includes(search.toLowerCase()) || e.dept.toLowerCase().includes(search.toLowerCase()) || e.nik.toLowerCase().includes(search.toLowerCase()));

  tbody.innerHTML = list.map(e => {
    const statusColor = e.status === 'aktif' ? '#22c55e' : '#ef4444';
    const statusLabel = e.status === 'aktif' ? 'Aktif' : 'Non-Aktif';
    return `<tr class="table-row" style="border-bottom:1px solid #1a1a2e;">
      <td class="px-5 py-4">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style="background:${e.color}22; color:${e.color};">${e.initials}</div>
          <div>
            <div class="font-medium text-sm" style="color:#f1f5f9;">${e.name}</div>
            <div class="text-xs" style="color:#64748b;">${e.email}</div>
          </div>
        </div>
      </td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${e.nik}</td>
      <td class="px-4 py-4 text-sm" style="color:#f1f5f9;">${e.position}</td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${e.dept}</td>
      <td class="px-4 py-4">
        <span class="status-badge" style="background:${statusColor}22; color:${statusColor};">
          <span class="w-1.5 h-1.5 rounded-full" style="background:${statusColor};"></span>
          ${statusLabel}
        </span>
      </td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${formatDisplayDate(e.joinDate)}</td>
      <td class="px-4 py-4">
        <div class="flex items-center gap-2">
          <button onclick="editEmployee(${e.id})" class="p-1.5 rounded-lg transition-colors hover:bg-white/10" title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </button>
          <button onclick="deleteEmployee(${e.id})" class="p-1.5 rounded-lg transition-colors hover:bg-red-500/10" title="Hapus">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
          </button>
        </div>
      </td>
    </tr>`;
  }).join('') || `<tr><td colspan="7" class="text-center py-12" style="color:#3d3d5c;">Tidak ada data karyawan</td></tr>`;
  lucide.createIcons();
}

function filterEmployees(f) {
  employeeFilter = f;
  ['filterAll', 'filterActive', 'filterInactive'].forEach(id => {
    const btn = document.getElementById(id);
    if (btn) { btn.style.background = 'transparent'; btn.style.color = '#64748b'; btn.style.border = '1px solid #2d2d4a'; }
  });
  const activeId = f === 'all' ? 'filterAll' : f === 'aktif' ? 'filterActive' : 'filterInactive';
  const activeBtn = document.getElementById(activeId);
  if (activeBtn) { activeBtn.style.background = '#f97316'; activeBtn.style.color = 'white'; activeBtn.style.border = 'none'; }
  renderEmployeeTable();
}

function searchEmployees() { renderEmployeeTable(); }

function openEmployeeModal(id) {
  editingEmployeeId = id || null;
  const modal = document.getElementById('employeeModal');
  const title = document.getElementById('empModalTitle');
  if (id) {
    const e = employees.find(emp => emp.id === id);
    if (!e) return;
    title.textContent = 'Edit Karyawan';
    document.getElementById('empName').value = e.name;
    document.getElementById('empNik').value = e.nik;
    document.getElementById('empEmail').value = e.email;
    document.getElementById('empPhone').value = e.phone;
    document.getElementById('empPosition').value = e.position;
    document.getElementById('empDept').value = e.dept;
    document.getElementById('empStatus').value = e.status;
    document.getElementById('empJoinDate').value = e.joinDate;
  } else {
    title.textContent = 'Tambah Karyawan';
    ['empName', 'empNik', 'empEmail', 'empPhone', 'empPosition'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('empDept').value = '';
    document.getElementById('empStatus').value = 'aktif';
    document.getElementById('empJoinDate').value = formatDate(new Date());
  }
  modal.classList.add('show');
}

function editEmployee(id) { openEmployeeModal(id); }

function deleteEmployee(id) {
  if (confirm('Yakin ingin menghapus karyawan ini?')) {
    employees = employees.filter(e => e.id !== id);
    renderEmployeeTable();
    showToast('Karyawan berhasil dihapus');
  }
}

function saveEmployee() {
  const name = document.getElementById('empName').value.trim();
  const nik = document.getElementById('empNik').value.trim();
  const email = document.getElementById('empEmail').value.trim();
  const phone = document.getElementById('empPhone').value.trim();
  const position = document.getElementById('empPosition').value.trim();
  const dept = document.getElementById('empDept').value;
  const status = document.getElementById('empStatus').value;
  const joinDate = document.getElementById('empJoinDate').value;

  if (!name || !nik || !email || !position || !dept) { showToast('Isi semua field yang wajib diisi!', 'error'); return; }

  const initials = name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
  const colors = ['#f97316', '#14b8a6', '#8b5cf6', '#ec4899', '#22c55e', '#f59e0b', '#6366f1', '#ef4444'];
  const color = colors[Math.floor(Math.random() * colors.length)];

  if (editingEmployeeId) {
    const idx = employees.findIndex(e => e.id === editingEmployeeId);
    if (idx > -1) employees[idx] = { ...employees[idx], name, nik, email, phone, position, dept, status, joinDate, initials, color: employees[idx].color };
    showToast('Data karyawan berhasil diperbarui');
  } else {
    const newId = Math.max(...employees.map(e => e.id)) + 1;
    employees.push({ id: newId, name, nik, email, phone, position, dept, status, joinDate, initials, color });
    showToast('Karyawan baru berhasil ditambahkan');
  }
  closeModal('employeeModal');
  renderEmployeeTable();
}

function formatDisplayDate(dateStr) {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

// --------- REPORT TABLE ---------
function renderReportTable() {
  const tbody = document.getElementById('reportTableBody');
  if (!tbody) return;
  tbody.innerHTML = attendanceLogs.map(l => {
    const statusColor = l.status === 'Tepat Waktu' ? '#22c55e' : l.status === 'Terlambat' ? '#f97316' : '#14b8a6';
    return `<tr class="table-row" style="border-bottom:1px solid #1a1a2e;">
      <td class="px-5 py-4">
        <div class="font-medium text-sm" style="color:#f1f5f9;">${l.empName}</div>
        <div class="text-xs" style="color:#64748b;">${l.dept}</div>
      </td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${formatDisplayDate(l.date)}</td>
      <td class="px-4 py-4 text-sm font-medium" style="color:#22c55e;">${l.checkIn || '-'}</td>
      <td class="px-4 py-4 text-sm font-medium" style="color:${l.checkOut ? '#f97316' : '#3d3d5c'};">${l.checkOut || 'Belum'}</td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${l.duration}</td>
      <td class="px-4 py-4 text-xs" style="color:#94a3b8;">📍 ${l.location}</td>
      <td class="px-4 py-4">
        <span class="status-badge" style="background:${statusColor}22; color:${statusColor};">${l.status}</span>
      </td>
    </tr>`;
  }).join('') || `<tr><td colspan="7" class="text-center py-12" style="color:#3d3d5c;">Tidak ada data laporan</td></tr>`;
}

function generateReport() { renderReportTable(); showToast('Laporan berhasil difilter'); }

// --------- LEAVE TABLE ---------
function renderLeaveTable() {
  const tbody = document.getElementById('leaveTableBody');
  if (!tbody) return;
  tbody.innerHTML = leaveRequests.map(l => {
    const { bg, text, label } = getStatusStyle(l.status);
    const typeColor = { 'Cuti Tahunan': '#f97316', 'Cuti Sakit': '#ef4444', 'Izin': '#14b8a6', 'Cuti Melahirkan': '#ec4899', 'Cuti Besar': '#8b5cf6' }[l.type] || '#64748b';
    return `<tr style="border-bottom:1px solid #1a1a2e;">
      <td class="px-5 py-4">
        <div class="font-medium text-sm" style="color:#f1f5f9;">${l.emp}</div>
      </td>
      <td class="px-4 py-4">
        <span class="status-badge" style="background:${typeColor}22; color:${typeColor};">${l.type}</span>
      </td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${formatDisplayDate(l.start)}</td>
      <td class="px-4 py-4 text-sm" style="color:#94a3b8;">${formatDisplayDate(l.end)}</td>
      <td class="px-4 py-4 text-sm font-medium" style="color:#f1f5f9;">${l.days}</td>
      <td class="px-4 py-4 text-xs" style="color:#94a3b8; max-width:200px;">${l.reason}</td>
      <td class="px-4 py-4">
        <span class="status-badge" style="background:${bg}; color:${text};">${label}</span>
      </td>
      <td class="px-4 py-4">
        ${l.status === 'pending' ? `
          <div class="flex gap-1.5">
            <button onclick="updateLeave(${l.id},'approved')" class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors" style="background:#22c55e22; color:#22c55e;">Setujui</button>
            <button onclick="updateLeave(${l.id},'rejected')" class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors" style="background:#ef444422; color:#ef4444;">Tolak</button>
          </div>
        ` : '<span class="text-xs" style="color:#3d3d5c;">—</span>'}
      </td>
    </tr>`;
  }).join('');
}

function updateLeave(id, status) {
  const req = leaveRequests.find(l => l.id === id);
  if (req) {
    req.status = status;
    renderLeaveTable();
    showToast(status === 'approved' ? 'Cuti berhasil disetujui' : 'Cuti berhasil ditolak');
  }
}

function openLeaveModal() {
  document.getElementById('leaveModal').classList.add('show');
  document.getElementById('leaveStart').value = formatDate(new Date());
  document.getElementById('leaveEnd').value = formatDate(new Date());
}

function saveLeave() {
  const emp = document.getElementById('leaveEmployee').value;
  const type = document.getElementById('leaveType').value;
  const start = document.getElementById('leaveStart').value;
  const end = document.getElementById('leaveEnd').value;
  const reason = document.getElementById('leaveReason').value.trim();

  if (!emp) { showToast('Pilih karyawan terlebih dahulu!', 'error'); return; }
  if (!reason) { showToast('Isi alasan cuti/izin!', 'error'); return; }

  const d1 = new Date(start), d2 = new Date(end);
  const days = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;

  leaveRequests.unshift({
    id: Date.now(), emp, type, start, end, days: Math.max(1, days), reason, status: 'pending'
  });

  closeModal('leaveModal');
  renderLeaveTable();
  showToast('Pengajuan cuti berhasil dikirim');
  document.getElementById('leaveEmployee').value = '';
  document.getElementById('leaveReason').value = '';
}

// --------- MODAL HELPERS ---------
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
function closeIfBackdrop(e, id) { if (e.target === document.getElementById(id)) closeModal(id); }

// --------- TOAST ---------
function showToast(msg, type) {
  const toast = document.getElementById('toast');
  const msgEl = document.getElementById('toastMsg');
  msgEl.textContent = msg;
  if (type === 'error') {
    toast.style.background = '#ef4444';
    toast.style.boxShadow = '0 8px 32px rgba(239,68,68,0.3)';
  } else {
    toast.style.background = '#22c55e';
    toast.style.boxShadow = '0 8px 32px rgba(34,197,94,0.3)';
  }
  toast.style.transform = 'translateY(0)';
  toast.style.opacity = '1';
  setTimeout(() => {
    toast.style.transform = 'translateY(80px)';
    toast.style.opacity = '0';
  }, 3200);
}

// --------- LOGOUT MODAL ---------
function showLogoutModal() {
  const modal = document.getElementById('logoutModal');
  if (!modal) return;
  modal.style.display = 'flex';
  lucide.createIcons();
}
function hideLogoutModal() {
  const modal = document.getElementById('logoutModal');
  if (modal) modal.style.display = 'none';
}
function confirmLogout() {
  hideLogoutModal();
  showToast('Anda telah keluar dari sistem', 'success');
  setTimeout(() => {
    if (window.location.pathname.includes('public')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = window.location.origin + '/HRManagement-pt2/public/logout';
      const tokenMeta = document.querySelector('meta[name="csrf-token"]');
      if (tokenMeta) {
        const csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token';
        csrf.value = tokenMeta.getAttribute('content');
        form.appendChild(csrf);
      }
      document.body.appendChild(form);
      form.submit();
    } else {
      window.location.href = window.location.origin + '/HRManagement-pt2/public/login';
    }
  }, 1500);
}
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') { hideLogoutModal(); closeCropModal(); }
});

// --------- DARK / LIGHT MODE ---------
let isDarkMode = true;

function toggleTheme() {
  isDarkMode = !isDarkMode;
  applyTheme(isDarkMode);
  localStorage.setItem('hrTheme', isDarkMode ? 'dark' : 'light');
}

function applyTheme(dark) {
  const html = document.documentElement;
  const themeIcon = document.getElementById('themeIcon');
  const darkModeToggle = document.getElementById('darkModeToggle');
  const darkModeKnob = document.getElementById('darkModeKnob');

  if (dark) {
    html.classList.remove('light');
    html.classList.add('dark');
    if (themeIcon) { themeIcon.setAttribute('data-lucide', 'moon'); }
    if (darkModeToggle) { darkModeToggle.style.background = '#f97316'; }
    if (darkModeKnob) { darkModeKnob.style.right = '2px'; darkModeKnob.style.left = 'auto'; }
  } else {
    html.classList.remove('dark');
    html.classList.add('light');
    if (themeIcon) { themeIcon.setAttribute('data-lucide', 'sun'); }
    if (darkModeToggle) { darkModeToggle.style.background = '#64748b'; }
    if (darkModeKnob) { darkModeKnob.style.left = '2px'; darkModeKnob.style.right = 'auto'; }
  }
  lucide.createIcons();
}

function toggleNotif() {
  const btn = document.getElementById('notifToggle');
  const knob = document.getElementById('notifKnob');
  const isOn = knob.style.left === '2px' || knob.style.left === '' || !knob.style.right;
  if (isOn) {
    btn.style.background = '#f97316';
    knob.style.left = 'auto'; knob.style.right = '2px';
    showToast('Notifikasi browser diaktifkan', 'success');
  } else {
    btn.style.background = '#3d3d5c';
    knob.style.right = 'auto'; knob.style.left = '2px';
    showToast('Notifikasi browser dinonaktifkan', 'error');
  }
}

// --------- PROFILE MANAGEMENT ---------
let profileData = {
  name: 'Admin HR',
  email: 'admin@company.co.id',
  nik: 'ADM-001',
  phone: '+62 812 0000 0001',
  position: 'HR Administrator',
  dept: 'HR',
  photo: null
};

function loadProfileFromStorage() {
  const saved = localStorage.getItem('hrProfile');
  if (saved) {
    try { profileData = { ...profileData, ...JSON.parse(saved) }; } catch(e) {}
  }
  applyProfileToUI();
}

function applyProfileToUI() {
  // Sidebar
  const sidebarName = document.querySelector('.sidebar .text-sm.font-medium');
  const sidebarEmail = document.querySelector('.sidebar .text-xs.truncate');
  if (sidebarName) sidebarName.textContent = profileData.name;
  if (sidebarEmail) sidebarEmail.textContent = profileData.email;

  // Topbar
  const topbarName = document.getElementById('topbarName');
  const topbarInitials = document.getElementById('topbarInitials');
  const topbarAvatar = document.getElementById('topbarAvatar');
  const initials = profileData.name.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
  if (topbarName) topbarName.textContent = profileData.name.split(' ')[0];
  if (topbarInitials) topbarInitials.textContent = initials;

  // Settings page form
  const pName = document.getElementById('profileName');
  const pEmail = document.getElementById('profileEmail');
  const pNik = document.getElementById('profileNik');
  const pPhone = document.getElementById('profilePhone');
  const pPos = document.getElementById('profilePosition');
  const pDept = document.getElementById('profileDept');
  if (pName) pName.value = profileData.name;
  if (pEmail) pEmail.value = profileData.email;
  if (pNik) pNik.value = profileData.nik;
  if (pPhone) pPhone.value = profileData.phone;
  if (pPos) pPos.value = profileData.position;
  if (pDept) pDept.value = profileData.dept;

  // Profile card
  const cardName = document.getElementById('profileCardName');
  const cardRole = document.getElementById('profileCardRole');
  const avatarInitials = document.getElementById('profileAvatarInitials');
  if (cardName) cardName.textContent = profileData.name;
  if (cardRole) cardRole.textContent = profileData.email;
  if (avatarInitials) avatarInitials.textContent = initials;
  if (topbarInitials) topbarInitials.textContent = initials;

  // Photo
  if (profileData.photo) {
    setProfilePhotoUI(profileData.photo);
  }
}

function saveProfileInfo() {
  profileData.name = document.getElementById('profileName').value.trim() || profileData.name;
  profileData.email = document.getElementById('profileEmail').value.trim() || profileData.email;
  profileData.nik = document.getElementById('profileNik').value.trim() || profileData.nik;
  profileData.phone = document.getElementById('profilePhone').value.trim() || profileData.phone;
  profileData.position = document.getElementById('profilePosition').value.trim() || profileData.position;
  profileData.dept = document.getElementById('profileDept').value || profileData.dept;
  const saveData = { ...profileData };
  if (saveData.photo && saveData.photo.length > 100000) delete saveData.photo; // Don't save large photos in profile data
  localStorage.setItem('hrProfile', JSON.stringify(saveData));
  applyProfileToUI();
  showToast('Informasi profil berhasil disimpan!', 'success');
}

function savePassword() {
  const cur = document.getElementById('currentPass').value;
  const nw = document.getElementById('newPass').value;
  const conf = document.getElementById('confirmPass').value;
  if (!cur) return showToast('Masukkan password saat ini', 'error');
  if (nw.length < 8) return showToast('Password baru minimal 8 karakter', 'error');
  if (nw !== conf) return showToast('Konfirmasi password tidak sesuai', 'error');
  document.getElementById('currentPass').value = '';
  document.getElementById('newPass').value = '';
  document.getElementById('confirmPass').value = '';
  showToast('Password berhasil diubah!', 'success');
}

// --------- PHOTO UPLOAD + CROP ---------
let cropOriginalSrc = '';
let cropDragging = false;
let cropResizing = false;
let cropResizeDir = '';
let cropDragStartX = 0, cropDragStartY = 0;
let cropBoxStartLeft = 0, cropBoxStartTop = 0;
let cropBoxStartW = 0, cropBoxStartH = 0;

function onPhotoSelected(event) {
  const file = event.target.files[0];
  if (!file) return;
  if (!file.type.startsWith('image/')) return showToast('File harus berupa gambar', 'error');
  const reader = new FileReader();
  reader.onload = (e) => {
    cropOriginalSrc = e.target.result;
    openCropModal(e.target.result);
  };
  reader.readAsDataURL(file);
  // Reset input so same file can be re-selected
  event.target.value = '';
}

function openCropModal(src) {
  const modal = document.getElementById('cropModal');
  const img = document.getElementById('cropImage');
  img.src = src;
  img.onload = () => {
    // Center crop box
    const area = document.getElementById('cropArea');
    const areaW = area.offsetWidth;
    const areaH = area.offsetHeight;
    const size = Math.min(areaW, areaH) * 0.7;
    const box = document.getElementById('cropBox');
    box.style.width = size + 'px';
    box.style.height = size + 'px';
    box.style.left = ((areaW - size) / 2) + 'px';
    box.style.top = ((areaH - size) / 2) + 'px';
    lucide.createIcons();
  };
  modal.classList.add('show');
}

function closeCropModal() {
  document.getElementById('cropModal').classList.remove('show');
}

function startDragCrop(e) {
  if (e.target !== document.getElementById('cropBox')) return;
  cropDragging = true;
  const touch = e.touches ? e.touches[0] : e;
  cropDragStartX = touch.clientX;
  cropDragStartY = touch.clientY;
  const box = document.getElementById('cropBox');
  cropBoxStartLeft = parseInt(box.style.left) || 0;
  cropBoxStartTop = parseInt(box.style.top) || 0;
  e.preventDefault();
  e.stopPropagation();
}

function startResizeCrop(e, dir) {
  cropResizing = true;
  cropResizeDir = dir;
  const touch = e.touches ? e.touches[0] : e;
  cropDragStartX = touch.clientX;
  cropDragStartY = touch.clientY;
  const box = document.getElementById('cropBox');
  cropBoxStartLeft = parseInt(box.style.left) || 0;
  cropBoxStartTop = parseInt(box.style.top) || 0;
  cropBoxStartW = box.offsetWidth;
  cropBoxStartH = box.offsetHeight;
  e.preventDefault();
  e.stopPropagation();
}

document.addEventListener('mousemove', (e) => {
  if (!cropDragging && !cropResizing) return;
  const area = document.getElementById('cropArea');
  const box = document.getElementById('cropBox');
  const areaRect = area.getBoundingClientRect();
  const dx = e.clientX - cropDragStartX;
  const dy = e.clientY - cropDragStartY;

  if (cropDragging) {
    let newL = cropBoxStartLeft + dx;
    let newT = cropBoxStartTop + dy;
    newL = Math.max(0, Math.min(newL, area.offsetWidth - box.offsetWidth));
    newT = Math.max(0, Math.min(newT, area.offsetHeight - box.offsetHeight));
    box.style.left = newL + 'px';
    box.style.top = newT + 'px';
  }
  if (cropResizing) {
    const minSize = 60;
    let newW = cropBoxStartW, newH = cropBoxStartH;
    let newL = cropBoxStartLeft, newT = cropBoxStartTop;
    if (cropResizeDir.includes('e')) newW = Math.max(minSize, cropBoxStartW + dx);
    if (cropResizeDir.includes('s')) newH = Math.max(minSize, cropBoxStartH + dy);
    if (cropResizeDir.includes('w')) { newW = Math.max(minSize, cropBoxStartW - dx); newL = cropBoxStartLeft + (cropBoxStartW - newW); }
    if (cropResizeDir.includes('n')) { newH = Math.max(minSize, cropBoxStartH - dy); newT = cropBoxStartTop + (cropBoxStartH - newH); }
    // Keep square
    const s = Math.min(newW, newH);
    box.style.width = s + 'px';
    box.style.height = s + 'px';
    box.style.left = Math.max(0, Math.min(newL, area.offsetWidth - s)) + 'px';
    box.style.top = Math.max(0, Math.min(newT, area.offsetHeight - s)) + 'px';
  }
});

document.addEventListener('mouseup', () => { cropDragging = false; cropResizing = false; });
document.addEventListener('touchend', () => { cropDragging = false; cropResizing = false; });

function applyCrop() {
  const area = document.getElementById('cropArea');
  const img = document.getElementById('cropImage');
  const box = document.getElementById('cropBox');
  const areaRect = area.getBoundingClientRect();
  const imgRect = img.getBoundingClientRect();

  // Calculate actual image coordinates from box position
  const scaleX = img.naturalWidth / imgRect.width;
  const scaleY = img.naturalHeight / imgRect.height;
  const boxLeft = parseInt(box.style.left) || 0;
  const boxTop = parseInt(box.style.top) || 0;
  const boxW = box.offsetWidth;
  const boxH = box.offsetHeight;

  // Offset relative to image within crop area
  const imgOffsetX = imgRect.left - areaRect.left;
  const imgOffsetY = imgRect.top - areaRect.top;
  const srcX = Math.max(0, (boxLeft - imgOffsetX) * scaleX);
  const srcY = Math.max(0, (boxTop - imgOffsetY) * scaleY);
  const srcW = boxW * scaleX;
  const srcH = boxH * scaleY;

  const canvas = document.createElement('canvas');
  const size = 300;
  canvas.width = size;
  canvas.height = size;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(img, srcX, srcY, srcW, srcH, 0, 0, size, size);

  const croppedDataURL = canvas.toDataURL('image/jpeg', 0.9);
  setProfilePhotoUI(croppedDataURL);
  profileData.photo = croppedDataURL;
  // Save to localStorage
  try { localStorage.setItem('hrProfilePhoto', croppedDataURL); } catch(e) {}
  closeCropModal();
  showToast('Foto profil berhasil diperbarui!', 'success');
  // Show remove button
  const removeBtn = document.getElementById('btnRemovePhoto');
  if (removeBtn) removeBtn.style.display = '';
}

function setProfilePhotoUI(dataURL) {
  const img = document.getElementById('profileAvatarImg');
  const initials = document.getElementById('profileAvatarInitials');
  const topbarAvatar = document.getElementById('topbarAvatar');
  const topbarInitials = document.getElementById('topbarInitials');

  if (img) { img.src = dataURL; img.style.display = 'block'; }
  if (initials) initials.style.display = 'none';

  // Update topbar avatar too
  if (topbarAvatar) {
    topbarAvatar.innerHTML = `<img src="${dataURL}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
  }
}

function removeProfilePhoto() {
  const img = document.getElementById('profileAvatarImg');
  const initials = document.getElementById('profileAvatarInitials');
  const removeBtn = document.getElementById('btnRemovePhoto');
  const topbarAvatar = document.getElementById('topbarAvatar');
  const topbarInitials = document.getElementById('topbarInitials');
  if (img) { img.src = ''; img.style.display = 'none'; }
  if (initials) initials.style.display = '';
  if (removeBtn) removeBtn.style.display = 'none';
  profileData.photo = null;
  localStorage.removeItem('hrProfilePhoto');
  if (topbarAvatar && topbarInitials) {
    topbarAvatar.innerHTML = `<span id="topbarInitials">${topbarInitials.textContent || 'AD'}</span>`;
    topbarAvatar.style.background = 'linear-gradient(135deg,#f97316,#14b8a6)';
  }
  showToast('Foto profil dihapus', 'success');
}

// --------- INIT ---------
document.addEventListener('DOMContentLoaded', () => {
  // Load saved theme
  const savedTheme = localStorage.getItem('hrTheme');
  isDarkMode = savedTheme !== 'light';
  applyTheme(isDarkMode);

  // Load profile
  loadProfileFromStorage();
  // Load saved photo separately (may be too large for JSON)
  const savedPhoto = localStorage.getItem('hrProfilePhoto');
  if (savedPhoto) {
    profileData.photo = savedPhoto;
    setProfilePhotoUI(savedPhoto);
    const removeBtn = document.getElementById('btnRemovePhoto');
    if (removeBtn) removeBtn.style.display = '';
  }

  initDashboard();
  dashboardInitialized = true;

  // Set default dates for report
  const today = formatDate(new Date());
  const reportFrom = document.getElementById('reportFrom');
  const reportTo = document.getElementById('reportTo');
  if (reportFrom) reportFrom.value = today.slice(0, 7) + '-01';
  if (reportTo) reportTo.value = today;
  lucide.createIcons();
});
