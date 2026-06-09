<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CAKRAWALA - Melampaui Nilai, Membentuk Masa Depan</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <!-- Fonts (Outfit for Headers, Inter for Body) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

        <!-- Tailwind & Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Franken UI (Shadcn CDN equivalent) -->
        <link rel="stylesheet" href="https://unpkg.com/franken-ui@0.0.12/dist/css/core.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit-icons.min.js"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50/50 text-slate-800">
        
        <!-- Navigation (Sticky Glassmorphism) -->
        <header class="bg-white/85 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain rounded-xl shadow-sm bg-slate-50 p-1">
                        <span class="text-xl font-extrabold tracking-tight text-slate-800">
                            CAKRAWALA
                        </span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="#tentang" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">Tentang Kami</a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-semibold transition border border-indigo-100">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition shadow-md shadow-indigo-100">
                                    Masuk
                                </a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main>
            <div class="relative pt-32 pb-40 flex content-center items-center justify-center min-h-[90vh] overflow-hidden">
                <!-- Glowing Abstract Vector Background (Futuristic but Soothing) -->
                <div class="absolute inset-0 -z-10">
                    <div class="absolute left-[5%] top-[-5%] w-[45rem] h-[45rem] bg-indigo-100/40 rounded-full blur-3xl"></div>
                    <div class="absolute right-[5%] bottom-[-5%] w-[45rem] h-[45rem] bg-emerald-50/40 rounded-full blur-3xl"></div>
                </div>

                <div class="container relative mx-auto text-center px-4">
                    <div class="max-w-4xl mx-auto space-y-8">
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100 inline-block uppercase tracking-wider">
                            🚀 Pengembangan Karakter Era Baru
                        </span>
                        
                        <h1 class="text-5xl font-black tracking-tight text-slate-800 sm:text-7xl leading-tight">
                            Melampaui Nilai, <br>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-750 to-purple-800">Membentuk Masa Depan.</span>
                        </h1>
                        
                        <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-500 leading-relaxed">
                            Platform pendidikan berbasis gamifikasi yang tidak hanya mengukur pencapaian akademik, tetapi juga membentuk karakter, kepemimpinan, kedisiplinan, dan kontribusi nyata siswa bagi masyarakat.
                        </p>

                        <div class="pt-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-semibold transition shadow-md shadow-indigo-100">
                                        Kembali ke Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-semibold transition shadow-md shadow-indigo-100">
                                        Mulai Sekarang
                                    </a>
                                    <a href="#tentang" class="px-8 py-3.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-2xl text-sm font-semibold transition ml-4 shadow-sm">
                                        Pelajari Lebih Lanjut
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="tentang" class="py-28 bg-white border-t border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-20 max-w-2xl mx-auto space-y-3">
                        <h2 class="text-3xl font-bold tracking-tight text-slate-800 sm:text-4xl">Mengapa CAKRAWALA?</h2>
                        <p class="text-sm text-slate-400 leading-relaxed">Kami percaya pendidikan sejati adalah keseimbangan yang harmonis antara kemampuan akademik dan kekuatan budi pekerti.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Card 1 -->
                        <div class="shimmer-card bg-slate-50/50 border border-slate-100 rounded-2xl p-8 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-indigo-50/50 soft-glow-indigo">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100/40">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-3">Sistem Poin Kebaikan</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Setiap tindakan positif, kontribusi kepedulian sosial, dan perilaku disiplin siswa diapresiasi secara terukur menggunakan model poin kebaikan.</p>
                        </div>

                        <!-- Card 2 -->
                        <div class="shimmer-card bg-slate-50/50 border border-slate-100 rounded-2xl p-8 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-emerald-50/50 soft-glow-emerald">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-6 shadow-sm border border-emerald-100/40">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-3">Misi & Tantangan Terarah</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Quest harian, mingguan, hingga misi global sekolah memandu perkembangan siswa secara holistik dalam suasana menyenangkan berbasis gamifikasi.</p>
                        </div>

                        <!-- Card 3 -->
                        <div class="shimmer-card bg-slate-50/50 border border-slate-100 rounded-2xl p-8 transition hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-indigo-50/50 soft-glow-indigo">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100/40">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-3">Rekomendasi Cerdas AI</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">Kecerdasan Buatan (AI) menganalisis pencapaian siswa untuk menyarankan misi dan memberikan wawasan perkembangan karakter kepada orang tua & guru.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-slate-900 py-12 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center justify-center space-y-3">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl bg-white/10 p-1">
                    <span class="text-xl font-extrabold tracking-tight text-white">CAKRAWALA</span>
                </div>
                <p class="text-xs text-slate-500">© 2026 CAKRAWALA. Melampaui Nilai, Membentuk Masa Depan.</p>
            </div>
        </footer>
    </body>
</html>
