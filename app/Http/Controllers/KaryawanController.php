<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;
use App\Models\User;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->isHR() && !$user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $search = $request->get('search');
        $jabatan = $request->get('jabatan');
        $status = $request->get('status');

        $query = Karyawan::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_telepon', 'like', "%{$search}%");
            });
        }

        if ($jabatan) {
            $query->where('jabatan', $jabatan);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $karyawanList = $query->orderBy('nama_lengkap', 'asc')->paginate(10);
        $jabatanList = ['Karyawan', 'Kepala Toko', 'Management'];

        return view('karyawan.index', compact('user', 'karyawanList', 'jabatanList', 'search', 'jabatan', 'status'));
    }

    public function show($id)
    {
        $karyawan = Karyawan::with(['absensi' => function($q) {
            $q->orderBy('tanggal', 'desc')->take(5);
        }, 'cuti' => function($q) {
            $q->orderBy('created_at', 'desc')->take(5);
        }])->findOrFail($id);

        return response()->json($karyawan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|unique:karyawan,nik',
            'email' => 'required|email|unique:karyawan,email|unique:users,email',
            'jabatan' => 'required|in:Karyawan,Kepala Toko,Management',
            'no_telepon' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
            'password' => 'required|string|min:6',
        ]);

        $role = ($request->jabatan === 'Management') ? 'hr' : 'karyawan';

        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        Karyawan::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'jabatan' => $request->jabatan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|unique:karyawan,nik,' . $id,
            'email' => 'required|email|unique:karyawan,email,' . $id,
            'jabatan' => 'required|in:Karyawan,Kepala Toko,Management',
            'no_telepon' => 'nullable|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'nullable|date',
            'status' => 'required|in:aktif,nonaktif,cuti',
        ]);

        $karyawan->update($request->only([
            'nama_lengkap', 'nik', 'email', 'jabatan',
            'no_telepon', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'tanggal_bergabung', 'status'
        ]));

        if ($karyawan->user) {
            $karyawan->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ]);
            if ($request->password) {
                $karyawan->user->update(['password' => Hash::make($request->password)]);
            }
        }

        return back()->with('success', 'Data karyawan ' . $karyawan->nama_lengkap . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $nama = $karyawan->nama_lengkap;
        if ($karyawan->user) {
            $karyawan->user->delete();
        }
        $karyawan->delete();

        return back()->with('success', 'Karyawan ' . $nama . ' berhasil dihapus.');
    }
}
