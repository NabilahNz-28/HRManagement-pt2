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

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    $maxAttempts = 3;
    $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
        $seconds = RateLimiter::availableIn($throttleKey);
        return back()->with('lockout_seconds', $seconds)->withErrors([
            'email' => 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $seconds . ' detik.',
        ])->onlyInput('email');
    }

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    RateLimiter::hit($throttleKey, 60);
    $retriesLeft = RateLimiter::retriesLeft($throttleKey, $maxAttempts);

    $errorMsg = 'Email atau password salah.';
    if ($retriesLeft > 0) {
        $errorMsg .= ' Sisa percobaan: ' . $retriesLeft . 'x';
    }

    return back()->withErrors([
        'email' => $errorMsg,
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

use App\Http\Controllers\ForgotPasswordController;

// Lupa Password (OTP via Email)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
    
    Route::get('/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.verify.form');
    Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify.post');
    
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

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

    // PAYROLL & SLIP GAJI
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PayrollController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\PayrollController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\PayrollController::class, 'store'])->name('store');
        Route::get('/karyawan-info/{id}', [\App\Http\Controllers\PayrollController::class, 'getKaryawanInfo'])->name('info');
        Route::get('/{id}/pdf', [\App\Http\Controllers\PayrollController::class, 'printPdf'])->name('pdf');
    });

    // PENGATURAN / PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
