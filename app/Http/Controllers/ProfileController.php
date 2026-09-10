<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Karyawan;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // For HR/Admin who might not have a karyawan record, try to find or create one
        $karyawan = null;
        if ($user->karyawan) {
            $karyawan = $user->karyawan;
        } else {
            $karyawan = Karyawan::where('email', $user->email)->first();
        }
        return view('profile', compact('user', 'karyawan'));
    }

    /**
     * Update photo only (separate from profile form) — receives cropped image as base64
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'cropped_image' => 'required|string',
        ]);

        $user = Auth::user();

        // Decode base64 image
        $imageData = $request->cropped_image;
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        $imageData = base64_decode($imageData);

        if (!$imageData) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses gambar.'], 422);
        }

        // Generate filename
        $filename = 'profile-photos/' . $user->id . '_' . time() . '.png';

        // Delete old photo
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Save new photo
        Storage::disk('public')->put($filename, $imageData);
        $user->profile_photo = $filename;
        $user->save();

        // Sync to karyawan record if exists
        $karyawan = $user->karyawan ?: Karyawan::where('email', $user->email)->first();
        if ($karyawan) {
            if ($karyawan->foto && $karyawan->foto !== $filename) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $karyawan->update(['foto' => $filename]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil disimpan!',
            'photo_url' => asset('storage/' . $filename),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'password_baru' => 'nullable|string|min:6|confirmed',
        ];

        // HR/Admin boleh edit email
        if ($user->isHR()) {
            $rules['email'] = 'nullable|email|max:255|unique:users,email,' . $user->id;
        }

        $request->validate($rules);

        $user->name = $request->name;

        // Update email hanya untuk HR/Admin
        if ($user->isHR() && $request->filled('email')) {
            $oldEmail = $user->email;
            $user->email = $request->email;

            // Sync email ke karyawan record jika ada
            $karyawan = $user->karyawan ?: Karyawan::where('email', $oldEmail)->first();
            if ($karyawan) {
                $karyawan->update(['email' => $request->email]);
            }
        }

        if ($request->password_baru) {
            $user->password = Hash::make($request->password_baru);
        }
        $user->save();

        // Update karyawan record if it exists
        $karyawan = $user->karyawan ?: Karyawan::where('email', $user->email)->first();
        if ($karyawan) {
            $karyawan->update([
                'nama_lengkap' => $request->name,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin ?: ($karyawan->jenis_kelamin ?? 'L'),
            ]);
        }

        return back()->with('success', 'Profil dan informasi akun berhasil diperbarui.');
    }
}
