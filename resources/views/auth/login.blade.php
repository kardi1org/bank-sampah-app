<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Bank Sampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .bg-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%234338ca' fill-opacity='0.03'%3E%3Cpath d='M92.4 44.4C96 44.4 99 47.4 99 51s-3 6.6-6.6 6.6-6.6-3-6.6-6.6 3-6.6 6.6-6.6zM10.6 44.4C14.2 44.4 17.2 47.4 17.2 51s-3 6.6-6.6 6.6S4 54.4 4 51s3-6.6 6.6-6.6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-pattern min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-200 mb-4 transform -rotate-6">
                <span class="text-4xl">♻️</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bank Sampah</h1>
            <p class="text-slate-500 font-medium mt-2">Silakan login untuk mengelola setoran</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] shadow-2xl shadow-slate-200 border border-slate-100">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email / Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            📧
                        </span>
                        <input type="text" name="email" required autofocus
                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-600 focus:bg-white focus:outline-none transition-all font-semibold text-slate-900 placeholder-slate-400"
                            placeholder="Masukkan email Anda">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-bold mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="text-sm font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">Lupa
                                Password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            🔒
                        </span>
                        <input type="password" name="password" required
                            class="w-full pl-11 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-indigo-600 focus:bg-white focus:outline-none transition-all font-semibold text-slate-900 placeholder-slate-400"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center mb-8 ml-1">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-5 h-5 border-2 border-slate-300 rounded text-indigo-600 focus:ring-indigo-500">
                    <label for="remember"
                        class="ml-3 text-sm font-semibold text-slate-600 cursor-pointer select-none">Ingat saya di
                        perangkat ini</label>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-extrabold text-lg shadow-xl shadow-indigo-200 hover:shadow-indigo-300 transform active:scale-[0.98] transition-all duration-200">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-slate-400 text-sm font-semibold">
            &copy; 2026 Bank Sampah Mandiri. <br>
            <span class="text-xs opacity-75 uppercase tracking-widest mt-2 block italic">Sustainable Future</span>
        </p>
    </div>

</body>

</html>
