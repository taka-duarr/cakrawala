<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>CAKRAWALA - Masuk</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50/50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden px-4">
            
            <!-- Glowing Soft Pastel Circles -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-[-10%] top-[-10%] w-[35rem] h-[35rem] bg-indigo-100/30 rounded-full blur-3xl"></div>
                <div class="absolute right-[-10%] bottom-[-10%] w-[35rem] h-[35rem] bg-emerald-50/30 rounded-full blur-3xl"></div>
            </div>

            <div class="mb-6 flex flex-col items-center text-center">
                <a href="/" class="flex flex-col items-center space-y-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-16 w-16 object-contain rounded-2xl shadow-sm bg-white p-2 border border-slate-100">
                    <span class="text-3xl font-black tracking-tight text-slate-850 block">CAKRAWALA</span>
                </a>
                <span class="block text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wider">
                    Melampaui Nilai, Membentuk Masa Depan
                </span>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-8 py-8 bg-white border border-slate-100 shadow-xl shadow-indigo-100/30 rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
