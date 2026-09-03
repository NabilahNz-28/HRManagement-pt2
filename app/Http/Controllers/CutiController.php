<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuti;
use App\Models\Karyawan;
use App\Models\JatahCuti;
use Carbon\Carbon;

class CutiController extends Controller
{
    private function getActiveKaryawan()
    {
        $user = Auth::user();
        if ($user->karyawan) {
            return $user->karyawan;
        }
        return Karyawan::where('email', $user->email)->first();
    }

    // Halaman Pengajuan Izin (Sesuai Screenshot 2)
    public function pengajuanIzin()
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        return view('cuti.izin', compact('user', 'karyawan'));
    }

    // Simpan Pengajuan Izin
    public function simpanIzin(Request $request)
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'tanggal_izin' => 'required|date',
            'jenis_izin' => 'required|string',
            'alasan' => 'required|string|min:10',
            'bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = 'izin_' . $karyawan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti'), $fileName);
            $lampiranPath = 'uploads/bukti/' . $fileName;
        }

        Cuti::create([
            'karyawan_id' => $karyawan->id,
            'jenis' => 'izin',
            'tanggal_mulai' => $request->tanggal_izin,
            'tanggal_selesai' => $request->tanggal_izin,
            'jumlah_hari' => 1,
            'alasan' => "[{$request->jenis_izin}] " . $request->alasan,
            'lampiran' => $lampiranPath,
            'status' => 'pending',
        ]);

        return redirect()->route('cuti.riwayat')->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan HRD.');
    }

    // Halaman Pengajuan Cuti (Sesuai Screenshot 2)
    public function pengajuanCuti()
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        
        $currentYear = Carbon::now()->year;
        $jatahCuti = $karyawan ? JatahCuti::where('karyawan_id', $karyawan->id)->where('tahun', $currentYear)->first() : null;
        $sisaCuti = $jatahCuti ? ($jatahCuti->total_jatah - $jatahCuti->terpakai) : 12;

        return view('cuti.cuti', compact('user', 'karyawan', 'sisaCuti'));
    }

    // Simpan Pengajuan Cuti
    public function simpanCuti(Request $request)
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();
        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $request->validate([
            'jenis_cuti' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|min:10',
            'bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ]);

        $d1 = Carbon::parse($request->tanggal_mulai);
        $d2 = Carbon::parse($request->tanggal_selesai);
        $jumlahHari = $d1->diffInDays($d2) + 1;

        $lampiranPath = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $fileName = 'cuti_' . $karyawan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/bukti'), $fileName);
            $lampiranPath = 'uploads/bukti/' . $fileName;
        }

        Cuti::create([
            'karyawan_id' => $karyawan->id,
            'jenis' => $request->jenis_cuti,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlahHari,
            'alasan' => $request->alasan,
            'lampiran' => $lampiranPath,
            'status' => 'pending',
        ]);

        return redirect()->route('cuti.riwayat')->with('success', "Pengajuan cuti ({$jumlahHari} hari) berhasil diajukan dan sedang diproses.");
    }

    // Riwayat Izin & Cuti
    public function riwayat()
    {
        $user = Auth::user();
        $karyawan = $this->getActiveKaryawan();

        if ($user->isHR() || $user->isAdmin()) {
            $cutiList = Cuti::with(['karyawan.departemen', 'approver'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $cutiList = $karyawan ? Cuti::where('karyawan_id', $karyawan->id)
                ->with('approver')
                ->orderBy('created_at', 'desc')
                ->get() : collect();
        }

        return view('cuti.riwayat', compact('user', 'cutiList'));
    }

    // Persetujuan HR
    public function approve($id)
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $cuti = Cuti::findOrFail($id);
        $cuti->update([
            'status' => 'disetujui',
            'disetujui_oleh' => $user->id,
            'disetujui_at' => now(),
        ]);

        // Kurangi jatah cuti jika cuti tahunan
        if ($cuti->jenis === 'cuti_tahunan') {
            $tahun = Carbon::parse($cuti->tanggal_mulai)->year;
            $jatah = JatahCuti::firstOrCreate(
                ['karyawan_id' => $cuti->karyawan_id, 'tahun' => $tahun],
                ['total_jatah' => 12, 'terpakai' => 0]
            );
            $jatah->increment('terpakai', $cuti->jumlah_hari);
        }

        return back()->with('success', 'Pengajuan cuti/izin berhasil disetujui.');
    }

    // Penolakan HR
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $cuti = Cuti::findOrFail($id);
        $cuti->update([
            'status' => 'ditolak',
            'disetujui_oleh' => $user->id,
            'disetujui_at' => now(),
            'catatan_hr' => $request->catatan_hr ?: 'Pengajuan ditolak oleh HRD.',
        ]);

        return back()->with('success', 'Pengajuan cuti/izin telah ditolak.');
    }
}
