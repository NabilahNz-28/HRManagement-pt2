<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Cuti;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        if ($user->isHR() || $user->isAdmin()) {
            // Data untuk Dashboard HR / Admin
            $totalKaryawan = Karyawan::where('status', 'aktif')->count();
            
            $hadirHariIni = Absensi::where('tanggal', $today)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();
                
            $terlambatHariIni = Absensi::where('tanggal', $today)
                ->where('status', 'terlambat')
                ->count();
                
            $izinCutiHariIni = Cuti::where('status', 'disetujui')
                ->where('tanggal_mulai', '<=', $today)
                ->where('tanggal_selesai', '>=', $today)
                ->count();

            $tidakHadir = max(0, $totalKaryawan - ($hadirHariIni + $izinCutiHariIni));

            // Absensi terbaru hari ini
            $absensiHariIni = Absensi::with('karyawan')
                ->where('tanggal', $today)
                ->orderBy('jam_masuk', 'desc')
                ->take(6)
                ->get();

            // Pengajuan cuti pending
            $cutiPending = Cuti::with('karyawan')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Data grafik kehadiran bulanan AKTUAL (7 hari terakhir hari kerja atau bulanan riil)
            // Grafik 14 hari terakhir aktual
            $chartLabels = [];
            $chartHadir = [];
            $chartTerlambat = [];

            for ($i = 13; $i >= 0; $i--) {
                $dateObj = Carbon::today()->subDays($i);
                $dateStr = $dateObj->format('Y-m-d');
                $chartLabels[] = $dateObj->translatedFormat('d M');

                $hadirCount = Absensi::where('tanggal', $dateStr)
                    ->where('status', 'hadir')
                    ->count();

                $terlambatCount = Absensi::where('tanggal', $dateStr)
                    ->where('status', 'terlambat')
                    ->count();

                $chartHadir[] = $hadirCount;
                $chartTerlambat[] = $terlambatCount;
            }

            // Distribusi Jabatan (Karyawan, Kepala Toko, Management)
            $jabatanStats = Karyawan::select('jabatan', DB::raw('count(*) as total'))
                ->groupBy('jabatan')
                ->pluck('total', 'jabatan')
                ->toArray();

            $jabatanLabels = ['Karyawan', 'Kepala Toko', 'Management'];
            $jabatanCounts = [
                $jabatanStats['Karyawan'] ?? 0,
                $jabatanStats['Kepala Toko'] ?? 0,
                $jabatanStats['Management'] ?? 0,
            ];

            return view('dashboard.hr', compact(
                'user',
                'totalKaryawan',
                'hadirHariIni',
                'terlambatHariIni',
                'izinCutiHariIni',
                'tidakHadir',
                'absensiHariIni',
                'cutiPending',
                'chartLabels',
                'chartHadir',
                'chartTerlambat',
                'jabatanLabels',
                'jabatanCounts'
            ));
        } else {
            // Data untuk Dashboard Karyawan
            $karyawan = $user->karyawan;
            if (!$karyawan) {
                $karyawan = Karyawan::where('email', $user->email)->first();
            }

            $karyawanId = $karyawan ? $karyawan->id : null;

            // Absensi hari ini
            $absensiHariIni = $karyawanId ? Absensi::where('karyawan_id', $karyawanId)->where('tanggal', $today)->first() : null;

            // Statistik bulan ini
            $totalHadirBulanIni = $karyawanId ? Absensi::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $currentMonth)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count() : 0;

            $totalTerlambatBulanIni = $karyawanId ? Absensi::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $currentMonth)
                ->where('status', 'terlambat')
                ->count() : 0;

            $totalIzinCutiBulanIni = $karyawanId ? Cuti::where('karyawan_id', $karyawanId)
                ->whereYear('tanggal_mulai', $currentYear)
                ->whereMonth('tanggal_mulai', $currentMonth)
                ->where('status', 'disetujui')
                ->sum('jumlah_hari') : 0;

            // Jatah cuti
            $jatahCuti = $karyawan ? DB::table('jatah_cuti')->where('karyawan_id', $karyawan->id)->where('tahun', $currentYear)->first() : null;
            $sisaCuti = $jatahCuti ? ($jatahCuti->total_jatah - $jatahCuti->terpakai) : 12;

            // Riwayat absensi 5 hari terakhir
            $riwayatAbsensi = $karyawanId ? Absensi::where('karyawan_id', $karyawanId)
                ->orderBy('tanggal', 'desc')
                ->take(5)
                ->get() : collect();

            // Pengajuan cuti terbaru
            $riwayatCuti = $karyawanId ? Cuti::where('karyawan_id', $karyawanId)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get() : collect();

            return view('dashboard.karyawan', compact(
                'user',
                'karyawan',
                'absensiHariIni',
                'totalHadirBulanIni',
                'totalTerlambatBulanIni',
                'totalIzinCutiBulanIni',
                'sisaCuti',
                'riwayatAbsensi',
                'riwayatCuti'
            ));
        }
    }
}
