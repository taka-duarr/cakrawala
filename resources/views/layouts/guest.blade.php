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
    <body class="font-sans text-slate-800 antialiased bg-slate-50/30">
        <div class="min-h-screen flex flex-col lg:flex-row relative">
            
            <!-- Left Side: Visual/Branding (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-950 text-white p-12 flex-col justify-between relative overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b, #0f172a);">
                <!-- Glowing effect -->
                <div class="absolute inset-0 -z-10">
                    <div class="absolute left-[-10%] top-[-10%] w-[40rem] h-[40rem] bg-indigo-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute right-[-10%] bottom-[-10%] w-[40rem] h-[40rem] bg-emerald-500/10 rounded-full blur-3xl"></div>
                </div>

                <!-- Branding Header -->
                <div class="flex items-center space-x-3 z-10">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl bg-white/10 p-1 backdrop-blur border border-white/10">
                    <span class="text-xl font-extrabold tracking-tight">CAKRAWALA</span>
                </div>

                <!-- Graphic/Illustration -->
                <div class="flex items-center justify-center py-12 z-10 flex-grow">
                    <img src="{{ asset('cakrawala_login.png') }}" alt="Achievement Graphic" class="max-w-xs object-contain rounded-3xl shadow-2xl border border-white/10 hover:scale-[1.02] transition-transform duration-500">
                </div>

                <!-- Testimonial/Message -->
                <div class="z-10">
                    <blockquote class="text-base font-medium leading-relaxed text-indigo-150">
                        "Karakter sejati terbentuk dari kebiasaan-kebiasaan kecil yang baik setiap hari."
                    </blockquote>
                    <p class="text-[10px] text-indigo-300 font-bold mt-2 uppercase tracking-wider">
                        — Filosofi CAKRAWALA
                    </p>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full lg:w-1/2 min-h-screen flex flex-col justify-center items-center py-12 px-6 relative">
                <!-- Glowing Soft Pastel Circles on Right Side for mobile/lg -->
                <div class="absolute inset-0 -z-10 lg:hidden">
                    <div class="absolute left-[-10%] top-[-10%] w-[25rem] h-[25rem] bg-indigo-100/30 rounded-full blur-3xl"></div>
                    <div class="absolute right-[-10%] bottom-[-10%] w-[25rem] h-[25rem] bg-emerald-50/30 rounded-full blur-3xl"></div>
                </div>

                <!-- Logo & Title for mobile/tablet (Hidden on Desktop) -->
                <div class="mb-8 flex flex-col items-center text-center lg:hidden">
                    <a href="/" class="flex flex-col items-center space-y-3">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-14 w-14 object-contain rounded-2xl shadow-sm bg-white p-2 border border-slate-100">
                        <span class="text-2xl font-black tracking-tight text-slate-850 block">CAKRAWALA</span>
                    </a>
                </div>

                <!-- Login Card Wrapper -->
                <div class="w-full sm:max-w-md px-8 py-8 bg-white border border-slate-100 shadow-xl shadow-indigo-100/10 rounded-2xl">
                    {{ $slot }}
                </div>

                <!-- Back to Landing Link -->
                <p class="text-xs text-slate-400 font-medium mt-6">
                    <a href="/" class="hover:text-indigo-600 transition flex items-center space-x-1.5 font-semibold">
                        <span>← Kembali ke Halaman Utama</span>
                    </a>
                </p>
            </div>
        </div>
    </body>
</html>
