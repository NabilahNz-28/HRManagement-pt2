<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    private function getActiveKaryawan()
    {
        $user = Auth::user();
        if ($user->karyawan) {
            return $user->karyawan;
        }
        return Karyawan::where('email', $user->email)->first();
    }

    // Halaman Absen Masuk
    public function masuk()
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        $today = Carbon::today()->format('Y-m-d');
        
        $sudahAbsen = $karyawan ? Absensi::where('karyawan_id', $karyawan->id)->where('tanggal', $today)->first() : null;

        // Untuk HR/Admin bisa pilih karyawan lain juga jika diperlukan
        $karyawanList = ($user->isHR() || $user->isAdmin()) ? Karyawan::where('status', 'aktif')->get() : collect();

        return view('absensi.masuk', compact('user', 'karyawan', 'sudahAbsen', 'karyawanList'));
    }

    // Proses Simpan Absen Masuk
    public function simpanMasuk(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'foto' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'lokasi' => 'nullable|string',
            'catatan' => 'nullable|string',
            'karyawan_id' => 'nullable|exists:karyawan,id',
        ]);

        $karyawanId = $request->karyawan_id;
        if (!$karyawanId || !$user->isHR()) {
            $karyawan = $this->getActiveKaryawan();
            if (!$karyawan) {
                return back()->with('error', 'Data karyawan tidak ditemukan untuk akun ini.');
            }
            $karyawanId = $karyawan->id;
        }

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        // Cek apakah sudah absen hari ini
        $existing = Absensi::where('karyawan_id', $karyawanId)->where('tanggal', $today)->first();
        if ($existing && $existing->jam_masuk) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Tentukan status: toleransi jam 08:15
        $status = 'hadir';
        if ($now->hour > 8 || ($now->hour == 8 && $now->minute > 15)) {
            $status = 'terlambat';
        }

        // Simpan foto dari base64
        $fotoPath = null;
        if ($request->foto && str_contains($request->foto, 'base64')) {
            $imageParts = explode(';base64,', $request->foto);
            $imageBase64 = base64_decode($imageParts[1] ?? '');
            $fileName = 'masuk_' . $karyawanId . '_' . time() . '.png';
            $dir = public_path('uploads/absensi');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($dir . '/' . $fileName, $imageBase64);
            $fotoPath = 'uploads/absensi/' . $fileName;
        }

        if ($existing) {
            $existing->update([
                'jam_masuk' => $currentTime,
                'foto_masuk' => $fotoPath,
                'lat_masuk' => $request->latitude,
                'lng_masuk' => $request->longitude,
                'lokasi_masuk' => $request->lokasi ?: 'Kantor Pusat Jakarta',
                'status' => $status,
                'catatan' => $request->catatan,
            ]);
        } else {
            Absensi::create([
                'karyawan_id' => $karyawanId,
                'tanggal' => $today,
                'jam_masuk' => $currentTime,
                'foto_masuk' => $fotoPath,
                'lat_masuk' => $request->latitude,
                'lng_masuk' => $request->longitude,
                'lokasi_masuk' => $request->lokasi ?: 'Kantor Pusat Jakarta',
                'status' => $status,
                'catatan' => $request->catatan,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Absen masuk berhasil direkam pada pukul ' . $currentTime . ' WIB (' . ucfirst($status) . ').');
    }

    // Halaman Absen Pulang
    public function pulang()
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        $today = Carbon::today()->format('Y-m-d');
        
        $absensiHariIni = $karyawan ? Absensi::where('karyawan_id', $karyawan->id)->where('tanggal', $today)->first() : null;

        return view('absensi.pulang', compact('user', 'karyawan', 'absensiHariIni'));
    }

    // Proses Simpan Absen Pulang
    public function simpanPulang(Request $request)
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'foto' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'lokasi' => 'nullable|string',
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        $absensi = Absensi::where('karyawan_id', $karyawan->id)->where('tanggal', $today)->first();
        if (!$absensi || !$absensi->jam_masuk) {
            return back()->with('error', 'Anda belum melakukan absen masuk hari ini.');
        }

        if ($absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        $masukTime = Carbon::createFromFormat('H:i:s', $absensi->jam_masuk);
        $durasiMenit = $masukTime->diffInMinutes($now);

        $fotoPath = null;
        if ($request->foto && str_contains($request->foto, 'base64')) {
            $imageParts = explode(';base64,', $request->foto);
            $imageBase64 = base64_decode($imageParts[1] ?? '');
            $fileName = 'pulang_' . $karyawan->id . '_' . time() . '.png';
            $dir = public_path('uploads/absensi');
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($dir . '/' . $fileName, $imageBase64);
            $fotoPath = 'uploads/absensi/' . $fileName;
        }

        $absensi->update([
            'jam_pulang' => $currentTime,
            'foto_pulang' => $fotoPath,
            'lat_pulang' => $request->latitude,
            'lng_pulang' => $request->longitude,
            'lokasi_pulang' => $request->lokasi ?: 'Kantor Pusat Jakarta',
            'durasi_menit' => $durasiMenit,
        ]);

        $jamDurasi = floor($durasiMenit / 60);
        $menitDurasi = $durasiMenit % 60;

        return redirect()->route('dashboard')->with('success', "Absen pulang berhasil dicatat pada {$currentTime} WIB. Total jam kerja: {$jamDurasi} jam {$menitDurasi} menit.");
    }

    // Rekap Harian
    public function rekapHarian(Request $request)
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $jabatan = $request->get('jabatan');

        $query = Absensi::with('karyawan')->where('tanggal', $tanggal);

        if ($user->isKaryawan() && $karyawan) {
            $query->where('karyawan_id', $karyawan->id);
        } elseif ($jabatan) {
            $query->whereHas('karyawan', function($q) use ($jabatan) {
                $q->where('jabatan', $jabatan);
            });
        }

        $absensiList = $query->orderBy('jam_masuk', 'asc')->get();
        $jabatanList = ['Karyawan', 'Kepala Toko', 'Management'];

        return view('monitoring.harian', compact('user', 'absensiList', 'tanggal', 'jabatan', 'jabatanList'));
    }

    // Rekap Bulanan
    public function rekapBulanan(Request $request)
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $jabatan = $request->get('jabatan');

        $query = Absensi::with('karyawan')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);

        if ($user->isKaryawan() && $karyawan) {
            $query->where('karyawan_id', $karyawan->id);
        } elseif ($jabatan) {
            $query->whereHas('karyawan', function($q) use ($jabatan) {
                $q->where('jabatan', $jabatan);
            });
        }

        $absensiList = $query->orderBy('tanggal', 'desc')->get();
        $jabatanList = ['Karyawan', 'Kepala Toko', 'Management'];

        // Summary stats
        $totalHadir = $absensiList->where('status', 'hadir')->count();
        $totalTerlambat = $absensiList->where('status', 'terlambat')->count();
        $totalIzin = $absensiList->where('status', 'izin')->count();

        return view('monitoring.bulanan', compact('user', 'absensiList', 'bulan', 'tahun', 'jabatan', 'jabatanList', 'totalHadir', 'totalTerlambat', 'totalIzin'));
    }

    // Export CSV
    public function exportCsv(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $absensiList = Absensi::with('karyawan')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get();

        $filename = "laporan_absensi_{$tahun}_{$bulan}.csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['NIK', 'Nama Karyawan', 'Jabatan', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Durasi Kerja (Menit)', 'Lokasi']);

        foreach ($absensiList as $row) {
            fputcsv($handle, [
                $row->karyawan->nik ?? '-',
                $row->karyawan->nama_lengkap ?? '-',
                $row->karyawan->jabatan ?? '-',
                $row->tanggal,
                $row->jam_masuk ?? '-',
                $row->jam_pulang ?? '-',
                ucfirst($row->status),
                $row->durasi_menit ?? 0,
                $row->lokasi_masuk ?? '-',
            ]);
        }

        fclose($handle);
        exit;
    }
}
