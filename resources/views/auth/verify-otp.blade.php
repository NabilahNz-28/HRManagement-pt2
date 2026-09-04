<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP — HR Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 relative z-10 border border-slate-100">
        
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fi fi-rr-envelope-open"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Verifikasi OTP</h1>
            <p class="text-sm text-slate-500">Kami telah mengirimkan 6 digit kode OTP ke email <strong class="text-slate-700">{{ session('reset_email') }}</strong></p>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-3 rounded-lg mb-5 flex items-start gap-2">
                <i class="fi fi-rr-check mt-0.5"></i>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg mb-5 flex items-start gap-2">
                <i class="fi fi-rr-exclamation mt-0.5"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5 text-center">Masukkan Kode OTP</label>
                <input type="text" name="otp" required autofocus maxlength="6" pattern="\d{6}" class="w-full text-center text-3xl tracking-[10px] py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all outline-none font-bold text-slate-800" placeholder="••••••">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-emerald-600/20">
                Verifikasi Kode
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
                Ganti Email / Kirim Ulang OTP
            </a>
        </div>
    </div>
</body>
</html>
