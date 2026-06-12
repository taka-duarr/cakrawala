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
            .smooth-scroll {
                scroll-behavior: smooth;
            }
            /* Subtle float animation for hero image */
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-12px); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="smooth-scroll font-sans antialiased bg-slate-50/30 text-slate-800">
        
        <!-- Navigation (Sticky Glassmorphism) -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl shadow-sm bg-slate-50 p-1 border border-slate-100">
                        <span class="text-lg font-black tracking-tight text-slate-900">
                            CAKRAWALA
                        </span>
                    </div>
                    <nav class="hidden md:flex space-x-8">
                        <a href="#tentang" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">Tentang</a>
                        <a href="#fitur" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">Fitur Utama</a>
                        <a href="#statistik" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">Statistik</a>
                    </nav>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition border border-indigo-100/50">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-100">
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
            <div class="relative pt-16 pb-24 lg:pt-24 lg:pb-32 overflow-hidden">
                <!-- Glowing Abstract Vector Background -->
                <div class="absolute inset-0 -z-10">
                    <div class="absolute left-[-5%] top-[-5%] w-[45rem] h-[45rem] bg-indigo-100/30 rounded-full blur-3xl"></div>
                    <div class="absolute right-[-5%] bottom-[-5%] w-[45rem] h-[45rem] bg-emerald-50/30 rounded-full blur-3xl"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                        <!-- Hero Content -->
                        <div class="w-full lg:w-1/2 space-y-6 text-center lg:text-left">
                            <span class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100 uppercase tracking-wider">
                                🚀 Pengembangan Karakter Era Baru
                            </span>
                            
                            <h1 class="text-4xl font-black tracking-tight text-slate-900 sm:text-6xl leading-tight">
                                Melampaui Nilai, <br>
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-indigo-750 to-purple-800">Membentuk Masa Depan.</span>
                            </h1>
                            
                            <p class="text-sm sm:text-base text-slate-555 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium">
                                Platform pendidikan berbasis gamifikasi yang mengapresiasi kontribusi, kedisiplinan, dan kepemimpinan siswa. Kami membantu guru, orang tua, dan sekolah berkolaborasi membentuk karakter tangguh generasi mendatang.
                            </p>

                            <div class="pt-4 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                                @if (Route::has('login'))
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-bold transition shadow-md shadow-indigo-100 flex items-center justify-center space-x-2">
                                            <span>Kembali ke Dashboard</span>
                                            <span uk-icon="icon: arrow-right; ratio: 0.8"></span>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-bold transition shadow-lg shadow-indigo-150/50 flex items-center justify-center space-x-2">
                                            <span>Mulai Sekarang</span>
                                            <span uk-icon="icon: sign-in; ratio: 0.8"></span>
                                        </a>
                                        <a href="#tentang" class="px-8 py-3.5 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-2xl text-xs font-bold transition shadow-sm flex items-center justify-center space-x-2">
                                            <span>Pelajari Selengkapnya</span>
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>

                        <!-- Hero Image (Interactive/Float) -->
                        <div class="w-full lg:w-1/2 flex justify-center">
                            <div class="relative w-full max-w-lg lg:max-w-none">
                                <!-- Background card-glow decoration -->
                                <div class="absolute -inset-1.5 bg-gradient-to-r from-indigo-600 to-emerald-500 rounded-3xl blur-2xl opacity-15"></div>
                                <img src="{{ asset('cakrawala_hero.png') }}" alt="Cakrawala Hero Illustration" class="relative rounded-3xl shadow-2xl border border-slate-100/50 animate-float w-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="tentang" class="py-24 bg-white border-t border-slate-100 relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16 max-w-2xl mx-auto space-y-3">
                        <h2 class="text-3xl font-black tracking-tight text-slate-800 sm:text-4xl">Mengapa CAKRAWALA?</h2>
                        <p class="text-xs sm:text-sm text-slate-450 leading-relaxed font-semibold">Kami percaya bahwa pendidikan sejati adalah keseimbangan yang harmonis antara kecerdasan akademis dan budi pekerti yang mulia.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="fitur">
                        <!-- Card 1 -->
                        <div class="bg-slate-50/50 border border-slate-100 hover:border-indigo-200 rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-indigo-100/20 group">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100/50 transition-all">
                                <span uk-icon="icon: heart; ratio: 1.2"></span>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-800 mb-3 group-hover:text-indigo-700 transition-colors">Sistem Poin Karakter</h3>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                Setiap perbuatan baik, kontribusi kepedulian sosial, keaktifan di kelas, dan kedisiplinan dihargai secara transparan menggunakan skor poin keaktifan.
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div class="bg-slate-50/50 border border-slate-100 hover:border-emerald-200 rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-emerald-100/20 group">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-emerald-100/50 transition-all">
                                <span uk-icon="icon: check; ratio: 1.2"></span>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-800 mb-3 group-hover:text-emerald-700 transition-colors">Misi Harian & Mingguan</h3>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                Guru dapat membuat tantangan karakter seperti memimpin doa, gotong royong, atau membaca buku di perpustakaan sebagai quest gamifikasi yang terukur.
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div class="bg-slate-50/50 border border-slate-100 hover:border-indigo-200 rounded-3xl p-8 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-xl hover:shadow-indigo-100/20 group">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white rounded-2xl flex items-center justify-center mb-6 shadow-sm border border-indigo-100/50 transition-all">
                                <span uk-icon="icon: cloud; ratio: 1.2"></span>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-800 mb-3 group-hover:text-indigo-700 transition-colors">Rekomendasi Pintar AI</h3>
                            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                                Rekomendasi misi berbasis AI mendeteksi minat, bakat, dan area perkembangan siswa, memberikan umpan balik (Early Warning) yang bernilai bagi orang tua.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div id="statistik" class="py-20 bg-slate-900 text-white relative overflow-hidden" style="background-color: #0f172a;">
                <div class="absolute inset-0 -z-10">
                    <div class="absolute left-[10%] top-[-10%] w-[35rem] h-[35rem] bg-indigo-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute right-[10%] bottom-[-10%] w-[35rem] h-[35rem] bg-emerald-500/10 rounded-full blur-3xl"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                        <div class="space-y-2">
                            <strong class="text-4xl md:text-5xl font-black block text-indigo-400">10,000+</strong>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Poin Dikumpulkan</span>
                        </div>
                        <div class="space-y-2">
                            <strong class="text-4xl md:text-5xl font-black block text-emerald-400">1,500+</strong>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Misi Terselesaikan</span>
                        </div>
                        <div class="space-y-2">
                            <strong class="text-4xl md:text-5xl font-black block text-indigo-400">50+</strong>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Lencana Karakter</span>
                        </div>
                        <div class="space-y-2">
                            <strong class="text-4xl md:text-5xl font-black block text-emerald-400">100%</strong>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Terintegrasi GPS</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-slate-950 py-16 border-t border-slate-900" style="background-color: #020617;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center justify-center space-y-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl bg-white/10 p-1">
                    <span class="text-lg font-black tracking-tight text-white">CAKRAWALA</span>
                </div>
                <p class="text-xs text-slate-400 max-w-md font-medium">
                    Melampaui Nilai Akademik biasa. Menanamkan akhlak, kepedulian sosial, dan kedisiplinan nyata demi mempersiapkan generasi pemimpin masa depan.
                </p>
                <div class="pt-4 border-t border-slate-900 w-full max-w-sm text-[10px] text-slate-500 font-bold tracking-wider">
                    © 2026 CAKRAWALA. ALL RIGHTS RESERVED.
                </div>
            </div>
        </footer>
    </body>
</html>
