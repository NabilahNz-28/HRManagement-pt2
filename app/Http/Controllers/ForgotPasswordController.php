<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ResetPasswordOtp;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan form email
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Generate OTP & Kirim Email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Alamat email tidak terdaftar di sistem kami.'
        ]);

        $email = $request->email;
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Hapus token/otp lama jika ada
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Simpan OTP ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otp, // Kita gunakan kolom token untuk menyimpan OTP
            'created_at' => Carbon::now()
        ]);

        // Kirim email
        Mail::to($email)->send(new ResetPasswordOtp($otp));

        // Simpan email di session untuk form OTP
        session(['reset_email' => $email]);

        return redirect()->route('password.verify.form')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    // 3. Tampilkan form input OTP
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    // 4. Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Cek kedaluwarsa (misal 15 menit)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan request ulang.']);
        }

        // Tandai OTP diverifikasi
        session(['otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    // 5. Tampilkan form reset password
    public function showResetForm()
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    // 6. Proses Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('reset_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        // Update password user
        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token dan bersihkan session
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}
