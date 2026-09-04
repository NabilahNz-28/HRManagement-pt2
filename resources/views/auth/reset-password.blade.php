<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Password Baru — HR Management</title>
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
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fi fi-rr-lock"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Buat Password Baru</h1>
            <p class="text-sm text-slate-500">Silakan masukkan password baru Anda yang kuat dan mudah diingat.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg mb-5 flex items-start gap-2">
                <i class="fi fi-rr-exclamation mt-0.5"></i>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
                <div class="relative">
                    <i class="fi fi-rr-lock absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="password" name="password" required autofocus class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-purple-500 focus:bg-white focus:ring-4 focus:ring-purple-500/10 transition-all outline-none" placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                <div class="relative">
                    <i class="fi fi-rr-check absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="password" name="password_confirmation" required class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-purple-500 focus:bg-white focus:ring-4 focus:ring-purple-500/10 transition-all outline-none" placeholder="Ketik ulang password">
                </div>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-purple-600/20 mt-2">
                Simpan Password
            </button>
        </form>

    </div>
</body>
</html>
