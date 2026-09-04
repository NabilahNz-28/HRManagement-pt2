<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $payrolls = Payroll::with('karyawan')->orderBy('created_at', 'desc')->paginate(10);
        return view('payroll.index', compact('user', 'payrolls'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap', 'asc')->get();
        return view('payroll.create', compact('user', 'karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_ids' => 'required|array|min:1',
            'karyawan_ids.*' => 'exists:karyawan,id',
            'periode' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'gaji_pokok_harian' => 'required|numeric|min:0',
            'uang_makan_harian' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'angkat_barang' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'thr' => 'nullable|numeric|min:0',
        ]);

        $gajiPokokHarian = $request->gaji_pokok_harian ?? 0;
        $uangMakanHarian = $request->uang_makan_harian ?? 0;
        $lembur = $request->lembur ?? 0;
        $angkatBarang = $request->angkat_barang ?? 0;
        $bonus = $request->bonus ?? 0;
        $thr = $request->thr ?? 0;

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $count = 0;

        foreach ($request->karyawan_ids as $karyawan_id) {
            $karyawan = Karyawan::find($karyawan_id);
            if (!$karyawan) continue;

            // Hitung absensi hadir / terlambat
            $hariMasuk = Absensi::where('karyawan_id', $karyawan->id)
                ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->whereIn('status', ['hadir', 'terlambat'])
                ->count();

            $gajiPokok = $hariMasuk * $gajiPokokHarian;
            $uangMakan = $hariMasuk * $uangMakanHarian;
            $uangTransport = $karyawan->uang_transport ?? 0;

            // Potongan hutang: baca dari form per karyawan (bisa angsur)
            $potonganHutang = floatval($request->input("potongan_hutang.$karyawan_id", 0));
            // Jangan melebihi saldo hutang yang ada
            $potonganHutang = min($potonganHutang, $karyawan->hutang ?? 0);

            $totalPendapatan = $gajiPokok + $uangMakan + $uangTransport + $lembur + $angkatBarang + $bonus + $thr;
            $totalGaji = max(0, $totalPendapatan - $potonganHutang);

            // Kurangi hutang karyawan sebesar nominal angsuran
            if ($potonganHutang > 0) {
                $karyawan->update(['hutang' => max(0, ($karyawan->hutang ?? 0) - $potonganHutang)]);
            }

            Payroll::create([
                'karyawan_id' => $karyawan->id,
                'periode' => $request->periode,
                'hari_masuk' => $hariMasuk,
                'gaji_pokok' => $gajiPokok,
                'uang_makan' => $uangMakan,
                'uang_transport' => $uangTransport,
                'lembur' => $lembur,
                'angkat_barang' => $angkatBarang,
                'bonus' => $bonus,
                'thr' => $thr,
                'potongan_hutang' => $potonganHutang,
                'total_gaji' => $totalGaji,
            ]);
            
            $count++;
        }

        return redirect()->route('payroll.index')->with('success', "$count Slip Gaji berhasil dibuat secara massal.");
    }

    public function printPdf($id)
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $payroll = Payroll::with('karyawan')->findOrFail($id);
        
        $pdf = Pdf::loadView('payroll.pdf', compact('payroll'));
        return $pdf->stream('Slip_Gaji_' . $payroll->karyawan->nama_lengkap . '_' . str_replace(' ', '_', $payroll->periode) . '.pdf');
    }
}
