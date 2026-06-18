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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

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
    <body class="font-sans text-slate-950 antialiased bg-[#EAF5FF] selection:bg-slate-950 selection:text-white flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">
        
        <!-- Large Centered Card Wrapper -->
        <div class="w-full max-w-5xl bg-white border-4 border-slate-950 shadow-[10px_10px_0px_0px_rgba(15,23,42,1)] rounded-[2.5rem] p-6 lg:p-8 flex flex-col lg:flex-row items-stretch gap-8">
            
            <!-- Left Side: Form Slot -->
            <div class="w-full lg:w-1/2 flex flex-col justify-between py-4">
                <!-- Branding Header -->
                <div class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl bg-white p-1 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                    <span class="text-xl font-black tracking-tight text-slate-950">CAKRAWALA</span>
                </div>

                <!-- Form content -->
                <div class="flex-grow flex flex-col justify-center">
                    {{ $slot }}
                </div>

                <!-- Back Link & Footer -->
                <div class="mt-6 pt-4 border-t border-slate-150 flex justify-between items-center text-xs">
                    <a href="/" class="hover:text-slate-700 transition font-bold flex items-center gap-x-1">
                        <span>← Kembali ke Beranda</span>
                    </a>
                    <span class="text-slate-400 font-semibold">CAKRAWALA © 2026</span>
                </div>
            </div>

            <!-- Right Side: Graphic/Illustration Panel (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-[#E4FF1A] border-4 border-slate-950 p-8 flex-col justify-between overflow-hidden" style="border-radius: 6rem 1.5rem 6rem 1.5rem;">
                <!-- Glowing Overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-black/5 pointer-events-none"></div>

                <!-- Text Overlay at Top Right -->
                <div class="text-right z-10">
                    <p class="text-xs font-black uppercase tracking-wider text-slate-950">CAKRAWALA EKOSISTEM</p>
                    <p class="text-sm font-extrabold text-slate-900 mt-1 max-w-xs ml-auto leading-normal">
                        Melampaui Nilai Akademik Biasa, Membentuk Karakter Unggul Masa Depan.
                    </p>
                </div>

                <!-- Image Illustration -->
                <div class="flex items-center justify-center py-6 z-10 flex-grow">
                    <img src="{{ asset('cakrawala_login.png') }}" alt="Achievement Illustration" class="max-h-[310px] object-contain rounded-3xl border-2 border-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:translate-x-0.5 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] transition-all duration-200">
                </div>

                <!-- Bottom Quote Card -->
                <div class="z-10 bg-white border-2 border-slate-950 rounded-xl p-4 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] text-center">
                    <span class="text-[9px] font-black text-slate-950 uppercase tracking-widest block">Filosofi Karakter</span>
                    <span class="text-xs font-bold text-slate-800 mt-1 block">"Karakter sejati terbentuk dari kebiasaan baik setiap hari."</span>
                </div>
            </div>

        </div>

    </body>
</html>
