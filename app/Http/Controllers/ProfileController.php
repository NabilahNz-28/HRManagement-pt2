<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $karyawan = $user->karyawan ?: Karyawan::where('email', $user->email)->first();
        return view('profile', compact('user', 'karyawan'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password_baru' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        if ($request->password_baru) {
            $user->password = Hash::make($request->password_baru);
        }
        $user->save();

        if ($user->karyawan) {
            $user->karyawan->update([
                'nama_lengkap' => $request->name,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin ?: 'L',
            ]);
        }

        return back()->with('success', 'Profil dan informasi akun berhasil diperbarui.');
    }
}
