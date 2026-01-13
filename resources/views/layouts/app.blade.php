<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="w-64 bg-slate-800 text-white hidden md:block">
            <div class="p-6 text-2xl font-bold border-b border-slate-700">BANK SAMPAH</div>
            <nav class="mt-6 p-4 space-y-2">
                <a href="#" class="block py-2 px-4 bg-slate-700 rounded">Dashboard</a>
                <a href="#" class="block py-2 px-4 hover:bg-slate-700 rounded text-slate-300">Data Nasabah</a>
                <a href="#" class="block py-2 px-4 hover:bg-slate-700 rounded text-slate-300">Laporan Keuangan</a>
            </nav>
        </aside>

        <div class="flex-1">
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800 uppercase">{{ Auth::user()->role }} PANEL</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline text-sm font-bold">LOGOUT</button>
                    </form>
                </div>
            </header>

            <main class="p-4 md:p-8 w-full">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
            @livewireScripts
        </div>
    </div>
</body>

</html>
