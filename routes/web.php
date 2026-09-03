<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Redirect root ke login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication routes
Route::get('/login', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'email' => 'Email atau password yang Anda masukkan salah.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Forgot password placeholder
Route::get('/forgot-password', function () {
    return redirect()->route('login')->with('status', 'Silakan hubungi HRD untuk reset password akun Anda.');
})->name('password.request');

// Protected Routes (Harus Login)
Route::middleware('auth')->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ABSENSI
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/masuk', [AbsensiController::class, 'masuk'])->name('masuk');
        Route::post('/masuk', [AbsensiController::class, 'simpanMasuk'])->name('masuk.post');
        Route::get('/pulang', [AbsensiController::class, 'pulang'])->name('pulang');
        Route::post('/pulang', [AbsensiController::class, 'simpanPulang'])->name('pulang.post');
    });

    // PENGAJUAN IZIN & CUTI
    Route::prefix('cuti')->name('cuti.')->group(function () {
        Route::get('/izin', [CutiController::class, 'pengajuanIzin'])->name('izin');
        Route::post('/izin', [CutiController::class, 'simpanIzin'])->name('izin.post');
        Route::get('/cuti', [CutiController::class, 'pengajuanCuti'])->name('cuti');
        Route::post('/cuti', [CutiController::class, 'simpanCuti'])->name('cuti.post');
        Route::get('/riwayat', [CutiController::class, 'riwayat'])->name('riwayat');
        
        // HR actions
        Route::post('/{id}/approve', [CutiController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [CutiController::class, 'reject'])->name('reject');
    });

    // MONITORING & REKAP
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/harian', [AbsensiController::class, 'rekapHarian'])->name('harian');
        Route::get('/bulanan', [AbsensiController::class, 'rekapBulanan'])->name('bulanan');
        Route::get('/export-csv', [AbsensiController::class, 'exportCsv'])->name('export');
    });

    // MANAJEMEN KARYAWAN (HR / Admin)
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::put('/{id}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('destroy');
    });

    // PENGATURAN / PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
