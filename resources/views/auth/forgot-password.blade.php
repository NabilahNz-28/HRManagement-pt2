<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password — HR Management</title>
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
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fi fi-rr-key"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Lupa Password?</h1>
            <p class="text-sm text-slate-500">Masukkan alamat email Anda yang terdaftar, kami akan mengirimkan kode OTP untuk reset password.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg mb-5 flex items-start gap-2">
                <i class="fi fi-rr-exclamation mt-0.5"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <i class="fi fi-rr-envelope absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="email" name="email" required autofocus class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="karyawan@bingxue.id">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-blue-700/20">
                Kirim Kode OTP
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
                &larr; Kembali ke halaman Login
            </a>
        </div>
    </div>
</body>
</html>
