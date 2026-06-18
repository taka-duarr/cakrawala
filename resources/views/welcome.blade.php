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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

        <!-- Tailwind & Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Franken UI (Shadcn CDN equivalent) -->
        <link rel="stylesheet" href="https://unpkg.com/franken-ui@0.0.12/dist/css/core.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit-icons.min.js"></script>

        <style>
            html {
                scroll-behavior: smooth;
                scroll-padding-top: 0;
            }
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
            @keyframes fadeInUpArc {
              from {
                opacity: 0;
                transform: translate(-50%, 60%);
              }
              to {
                opacity: 1;
                transform: translate(-50%, 50%);
              }
            }
            .animate-fade-in-up {
              animation-name: fadeInUpArc;
              animation-duration: 0.8s;
              animation-timing-function: ease-out;
              animation-fill-mode: forwards;
            }
            @keyframes fadeInText {
              from {
                opacity: 0;
                transform: translateY(10px);
              }
              to {
                opacity: 1;
                transform: translateY(0);
              }
            }
            .animate-fade-in {
              animation-name: fadeInText;
              animation-duration: 0.8s;
              animation-timing-function: ease-out;
              animation-fill-mode: forwards;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-slate-900 selection:text-white">
        
        <!-- Top Lime Hero Div -->
        <div class="bg-[#E4FF1A] border-b-2 border-slate-950 min-h-screen flex flex-col justify-between relative overflow-hidden">
            <!-- Navigation -->
            <header class="max-w-7xl mx-auto w-full px-6 sm:px-8 lg:px-10 h-24 flex justify-between items-center relative z-20">
                <div class="flex items-center gap-x-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl shadow-sm bg-white p-1 border-2 border-slate-950">
                    <span class="text-xl font-black tracking-tight text-slate-950">
                        CAKRAWALA
                    </span>
                </div>
                <nav class="hidden md:flex items-center gap-x-10">
                    <a href="#tentang" class="text-xs font-black uppercase tracking-wider text-slate-900 hover:opacity-70 transition">Tentang</a>
                    <a href="#fitur" class="text-xs font-black uppercase tracking-wider text-slate-900 hover:opacity-70 transition">Fitur Utama</a>
                </nav>
                <div class="flex items-center gap-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-slate-950 text-white hover:bg-slate-800 rounded-full text-xs font-bold transition border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(255,255,255,1)]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-950 text-white hover:bg-slate-800 rounded-full text-xs font-bold transition border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(255,255,255,1)]">
                                Masuk
                            </a>
                        @endauth
                    @endif
                </div>
            </header>

            <!-- Hero Main Area: Arc Gallery Hero -->
            <div id="arc-hero-container" class="relative w-full flex-grow flex flex-col justify-between">
                
                <!-- The Arc Canvas Container -->
                <div id="arc-gallery-wrapper" class="relative w-full overflow-visible mx-auto z-10 mt-10 sm:mt-14 lg:mt-20" style="height: 340px;">
                    <!-- Pivot point at bottom center -->
                    <div id="arc-pivot" class="absolute left-1/2 bottom-0 -translate-x-1/2 w-0 h-0">
                        @php
                            $memoryImages = [
                                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1513258496099-48168024aec0?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=400&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1544717305-2782549b5136?q=80&w=400&auto=format&fit=crop',
                            ];
                        @endphp
                        
                        @foreach($memoryImages as $index => $src)
                            <div class="arc-card absolute opacity-0" data-index="{{ $index }}" style="animation-delay: {{ $index * 100 }}ms;">
                                <div class="w-full h-full rounded-2xl border-2 border-slate-950 bg-white p-1 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(15,23,42,1)] hover:scale-105 transition-all duration-200 overflow-hidden inner-card">
                                    <img src="{{ $src }}" alt="School Memory {{ $index + 1 }}" class="w-full h-full object-cover rounded-xl" draggable="false" onerror="this.src='https://placehold.co/400x400/334155/e2e8f0?text=CAKRAWALA'">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Text & Button Content below the arc -->
                <div class="relative z-20 flex-grow flex items-center justify-center px-6 pb-8 pt-4 -mt-10 sm:-mt-14 md:-mt-22 lg:-mt-28">
                    <div class="text-center max-w-3xl px-6 opacity-0 animate-fade-in" style="animation-delay: 800ms;">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-950 tracking-tight leading-[1.05] uppercase">
                            Melampaui Nilai,<br>
                            Membentuk Karakter.
                        </h1>
                        <p class="mt-3 text-xs sm:text-sm text-slate-850 font-semibold max-w-2xl mx-auto leading-relaxed">
                            Platform gamifikasi karakter siswa modern. Menumbuhkan reputasi akhlak mulia, kepedulian sosial, dan kedisiplinan tervalidasi dalam satu ekosistem terpadu.
                        </p>
                        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 bg-slate-950 text-white hover:bg-slate-800 border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                                        Eksplor Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-slate-950 text-white hover:bg-[#E4FF1A] hover:text-slate-950 border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] hover:shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                                        Mulai Sekarang
                                    </a>
                                @endauth
                            @endif
                            <a href="#tentang" class="w-full sm:w-auto px-8 py-3.5 bg-white text-slate-950 hover:bg-slate-100 border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5">
                                Pelajari Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Split Divider & Stats Row -->
            <div class="bg-white border-t-2 border-slate-950 py-6 w-full z-10">
                <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Left Stat -->
                    <div class="flex flex-col">
                        <strong class="text-3xl font-black text-slate-950">15K+</strong>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Poin Karakter Terekam Bulan Ini</span>
                    </div>

                    <!-- Right Scroll Indicator -->
                    <div class="hidden md:flex items-center gap-x-2 text-slate-950 font-bold text-xs uppercase tracking-widest">
                        <span>Scroll Down</span>
                        <span uk-icon="icon: arrow-down; ratio: 0.8" class="animate-bounce"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tentang Kami: Permasalahan & Solusi -->
        <div id="tentang" class="min-h-screen flex items-center py-20 bg-white border-b-2 border-slate-950">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 space-y-16 w-full">
                <!-- Header Section -->
                <div class="text-center max-w-3xl mx-auto space-y-4 scroll-animate">
                    <span class="px-4 py-1.5 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 rounded-full text-xs font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                        Tentang Kami
                    </span>
                    <h2 class="text-3xl font-black text-slate-950 tracking-tight sm:text-5xl uppercase leading-none">
                        Sebelum vs Sesudah CAKRAWALA
                    </h2>
                    <p class="text-sm text-slate-650 font-medium max-w-2xl mx-auto leading-relaxed">
                        Pendidikan karakter memerlukan pendekatan modern. Lihat bagaimana CAKRAWALA mengubah hambatan sistem konvensional menjadi ekosistem digital yang aktif dan menyenangkan.
                    </p>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    
                    <!-- Left: Permasalahan (Sebelum CAKRAWALA) -->
                    <div class="lg:col-span-4 bg-[#FFEAEA] border-4 border-slate-950 rounded-3xl p-8 flex flex-col justify-between text-slate-950 shadow-[6px_6px_0px_0px_rgba(239,68,68,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[10px_10px_0px_0px_rgba(239,68,68,1)] transition-all duration-300 scroll-animate">
                        <div>
                            <div class="flex items-center gap-x-3 mb-6">
                                <div class="w-10 h-10 bg-red-500 text-white border-2 border-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <span uk-icon="icon: warning; ratio: 1.1"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-red-700 block">Sistem Tradisional</span>
                                    <h3 class="text-lg font-black uppercase leading-tight">Masalah Klasik</h3>
                                </div>
                            </div>
                            
                            <ul class="space-y-6">
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600 inline-block align-middle mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> 1. Penilaian Kognitif Murni
                                    </h4>
                                    <p class="text-xs text-slate-750 font-medium leading-relaxed">
                                        Sekolah hanya fokus pada angka di atas kertas. Perbuatan baik, empati, dan kontribusi sosial harian siswa tidak dihargai secara formal.
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600 inline-block align-middle mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> 2. Celah Bolos & Titip Absen
                                    </h4>
                                    <p class="text-xs text-slate-750 font-medium leading-relaxed">
                                        Absensi manual dengan kertas rawan kecurangan. Guru kesulitan melacak lokasi kehadiran siswa secara tervalidasi dan real-time.
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600 inline-block align-middle mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> 3. Transaksi Tunai Tak Terkontrol
                                    </h4>
                                    <p class="text-xs text-slate-750 font-medium leading-relaxed">
                                        Uang saku fisik rawan hilang, disalahgunakan, atau memicu pemalakan. Sekolah tidak memiliki kontrol atas apa yang dibeli siswa di kantin.
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-red-600 inline-block align-middle mr-1"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> 4. Keterbatasan Pantauan Wali
                                    </h4>
                                    <p class="text-xs text-slate-750 font-medium leading-relaxed">
                                        Orang tua berada di area 'blindspot'—hanya mengetahui perkembangan anak saat pembagian rapor fisik tiap akhir semester.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Middle: Visual Illustration -->
                    <div class="lg:col-span-4 flex flex-col justify-center items-center scroll-animate">
                        <div class="w-full max-w-sm bg-white rounded-3xl border-4 border-slate-950 overflow-hidden shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[12px_12px_0px_0px_rgba(15,23,42,1)] transition-all duration-300">
                            <img src="{{ asset('problem_vs_solution.png') }}" alt="Visual Perbandingan" class="w-full object-cover aspect-square border-b-4 border-slate-950">
                            <div class="p-6 bg-[#E4FF1A] text-center text-slate-950">
                                <span class="text-[10px] font-black uppercase tracking-wider">Transformasi Digital</span>
                                <h3 class="text-xs font-black mt-1 uppercase">Dampak Nyata Bersama CAKRAWALA</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Solusi (Setelah CAKRAWALA) -->
                    <div class="lg:col-span-4 bg-[#EAFCEF] border-4 border-slate-950 rounded-3xl p-8 flex flex-col justify-between text-slate-950 shadow-[6px_6px_0px_0px_rgba(34,197,94,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[10px_10px_0px_0px_rgba(34,197,94,1)] transition-all duration-300 scroll-animate">
                        <div>
                            <div class="flex items-center gap-x-3 mb-6">
                                <div class="w-10 h-10 bg-green-500 text-white border-2 border-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                    <span uk-icon="icon: check; ratio: 1.1"></span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-green-700 block">Sistem CAKRAWALA</span>
                                    <h3 class="text-lg font-black uppercase leading-tight">Solusi Cerdas</h3>
                                </div>
                            </div>
                            
                            <ul class="space-y-6">
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600 inline-block align-middle mr-1"><polyline points="20 6 9 17 4 12"></polyline></svg> 1. Gamifikasi Poin Karakter
                                    </h4>
                                    <p class="text-xs text-slate-755 font-medium leading-relaxed">
                                        Setiap tindakan disiplin, bantuan sosial, dan perilaku terpuji otomatis mendapat Poin Karakter yang divalidasi oleh Guru dan masuk riwayat terenkripsi.
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600 inline-block align-middle mr-1"><polyline points="20 6 9 17 4 12"></polyline></svg> 2. Presensi GPS & QR Dinamis
                                    </h4>
                                    <p class="text-xs text-slate-755 font-medium leading-relaxed">
                                        Kehadiran siswa divalidasi koordinat GPS radius sekolah dan kode QR dinamis di kelas. Bebas kecurangan, absensi terdata instan.
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600 inline-block align-middle mr-1"><polyline points="20 6 9 17 4 12"></polyline></svg> 3. Transaksi Cashless & P2P Aman
                                    </h4>
                                    <p class="text-xs text-slate-755 font-medium leading-relaxed">
                                        Kantin cashless terintegrasi. Siswa membayar makanan sehat dengan memindai QR Merchant menggunakan poin, serta dapat saling berbagi poin keaktifan (P2P).
                                    </p>
                                </li>
                                <li class="space-y-1">
                                    <h4 class="text-sm font-black text-slate-950 uppercase flex items-center gap-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-green-600 inline-block align-middle mr-1"><polyline points="20 6 9 17 4 12"></polyline></svg> 4. Dashboard Wali & Insight AI
                                    </h4>
                                    <p class="text-xs text-slate-755 font-medium leading-relaxed">
                                        Orang tua menerima notifikasi instan aktivitas anak, poin keaktifan harian, dan rekomendasi berbasis AI untuk mengarahkan minat bakat mereka.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Features (Grid Neo-Brutalism) -->
        <div id="fitur" class="min-h-screen flex items-center py-24 bg-slate-50 border-b-2 border-slate-950">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 space-y-16 w-full">
                <div class="text-center max-w-3xl mx-auto space-y-4 scroll-animate">
                    <span class="px-4 py-1.5 bg-slate-950 text-white rounded-full text-xs font-bold uppercase tracking-wider shadow-[2px_2px_0px_0px_rgba(255,255,255,1)]">
                        Fitur Utama Platform
                    </span>
                    <h2 class="text-3xl font-black text-slate-950 tracking-tight sm:text-5xl uppercase leading-none">
                        Ekosistem Karakter Terintegrasi
                    </h2>
                    <p class="text-sm text-slate-650 font-medium max-w-2xl mx-auto leading-relaxed">
                        CAKRAWALA dilengkapi berbagai modul interaktif yang menghubungkan Siswa, Guru, Sekolah, Merchant Kantin, dan Orang Tua dalam satu sistem digital.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Card 1: Poin & Lencana -->
                    <div class="bg-[#FFF4E4] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: heart; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Poin & Lencana Karakter</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Sistem pengumpulan poin reputasi (kebaikan, kedisiplinan, prestasi) dan pencapaian lencana virtual sebagai portofolio karakter digital murid.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2: Presensi GPS & QR -->
                    <div class="bg-[#EAF5FF] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#B4F0C5] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: location; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Presensi GPS & QR Dinamis</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Absensi aman dengan validasi geofencing radius sekolah serta QR dinamis real-time per kelas untuk meminimalkan kecurangan/titip absen.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3: Misi Karakter -->
                    <div class="bg-[#FFEAF5] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#C7C6FF] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: check; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Misi & Tantangan Karakter</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Quest harian dan mingguan yang ditugaskan oleh Guru seperti memimpin doa, piket bersama, atau kegiatan literasi berhadiah poin keaktifan.
                            </p>
                        </div>
                    </div>

                    <!-- Card 4: Kantin Cashless -->
                    <div class="bg-[#EAFCEF] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#FFF4E4] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: cart; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Kantin Cashless Sekolah</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Pembayaran makanan di merchant kantin menggunakan poin. Toko membuat QR nominal pesanan, siswa melakukan scan cepat dari dashboard mereka.
                            </p>
                        </div>
                    </div>

                    <!-- Card 5: P2P Transfer -->
                    <div class="bg-[#F5EFFF] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#FFEAF5] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: refresh; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Kirim Poin Peer-to-Peer (P2P)</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Kemudahan berbagi poin keaktifan antar teman lewat QR Code terenkripsi yang terlindung dari double claim menggunakan sistem cache lock.
                            </p>
                        </div>
                    </div>

                    <!-- Card 6: Hadiah & WD Poin -->
                    <div class="bg-[#FFFEEA] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#B4F0C5] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5.5 h-5.5 text-slate-950"><rect x="3" y="8" width="18" height="4"></rect><path d="M12 8v13"></path><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path><path d="M7.5 8a2.5 2.5 0 0 1 0-5C11 3 12 8 12 8z"></path><path d="M16.5 8a2.5 2.5 0 0 0 0-5C13 3 12 8 12 8z"></path></svg>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Toko Hadiah & WD Poin</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Siswa menukar poin dengan reward/hadiah menarik sekolah. Merchant kantin dapat mengajukan penarikan dana poin (WD) aman ke pihak Admin.
                            </p>
                        </div>
                    </div>

                    <!-- Card 7: Portal Orang Tua -->
                    <div class="bg-[#FFEAEA] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#C7C6FF] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: users; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Portal Orang Tua / Wali</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Dashboard transparan bagi orang tua untuk memantau absensi anak, total poin karakter, lencana kebaikan, serta tugas yang dikumpulkan secara instan.
                            </p>
                        </div>
                    </div>

                    <!-- Card 8: Insight Rekomendasi AI -->
                    <div class="bg-[#E6FCFF] border-2 border-slate-950 rounded-3xl p-6 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-2 hover:translate-x-1 hover:shadow-[8px_8px_0px_0px_rgba(15,23,42,1)] transition-all duration-300 space-y-5 flex flex-col justify-between scroll-animate">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-[#E4FF1A] border-2 border-slate-950 text-slate-950 rounded-2xl flex items-center justify-center shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]">
                                <span uk-icon="icon: bookmark; ratio: 1.1"></span>
                            </div>
                            <h3 class="text-base font-black text-slate-950 uppercase leading-snug">Materi & Tugas Mapel</h3>
                            <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                Akses materi pembelajaran dan kerjakan tugas secara terintegrasi. Guru dapat memonitor progres belajar dan kehadiran siswa dalam satu platform interaktif.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Footer -->
        <footer class="bg-slate-950 border-t-8 border-[#E4FF1A] py-16 text-white relative">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <!-- Brand block -->
                <div class="flex flex-col items-center md:items-start space-y-4">
                    <div class="flex items-center gap-x-3">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain rounded-xl bg-white p-1 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(255,255,255,1)]">
                        <span class="text-2xl font-black tracking-tight text-[#E4FF1A] uppercase">CAKRAWALA</span>
                    </div>
                    <p class="text-xs text-slate-400 max-w-sm font-semibold leading-relaxed text-center md:text-left">
                        Melampaui Nilai Akademik biasa. Menanamkan akhlak, kepedulian sosial, dan kedisiplinan nyata demi mempersiapkan generasi pemimpin masa depan.
                    </p>
                </div>

                <!-- Action Button in Footer -->
                <div class="flex flex-col items-center md:items-end space-y-4">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Siap Bergabung?</span>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-[#E4FF1A] text-slate-950 hover:bg-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] hover:shadow-[4px_4px_0px_0px_rgba(228,255,26,1)] hover:-translate-y-0.5">
                                Masuk Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-3.5 bg-[#E4FF1A] text-slate-950 hover:bg-white border-2 border-slate-950 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-[3px_3px_0px_0px_rgba(255,255,255,1)] hover:shadow-[4px_4px_0px_0px_rgba(228,255,26,1)] hover:-translate-y-0.5">
                                Mulai Sekarang
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 mt-12 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-slate-500 font-bold tracking-wider uppercase">
                <div>
                    © 2026 CAKRAWALA. ALL RIGHTS RESERVED.
                </div>
                <div class="flex gap-x-6">
                    <a href="#tentang" class="hover:text-white transition">Tentang</a>
                    <a href="#fitur" class="hover:text-white transition">Fitur Utama</a>
                </div>
            </div>
        </footer>

        <!-- IntersectionObserver Script for Scroll Entrance Animations -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Arc Hero Layout Calculation
                const startAngle = 20;
                const endAngle = 160;
                
                const radiusLg = 340;
                const radiusMd = 265;
                const radiusSm = 175;
                
                const cardSizeLg = 95;
                const cardSizeMd = 75;
                const cardSizeSm = 60;

                const wrapper = document.getElementById('arc-gallery-wrapper');
                const pivot = document.getElementById('arc-pivot');
                const cards = document.querySelectorAll('.arc-card');
                const count = Math.max(cards.length, 2);
                const step = (endAngle - startAngle) / (count - 1);

                function layoutArc() {
                    const width = window.innerWidth;
                    let radius = radiusLg;
                    let cardSize = cardSizeLg;

                    if (width < 640) {
                        radius = radiusSm;
                        cardSize = cardSizeSm;
                    } else if (width < 1024) {
                        radius = radiusMd;
                        cardSize = cardSizeMd;
                    }

                    // Dynamically size wrapper height
                    wrapper.style.height = (radius * 1.05) + 'px';

                    cards.forEach((card, i) => {
                        const angle = startAngle + (step * i);
                        const angleRad = (angle * Math.PI) / 180;
                        
                        const x = Math.cos(angleRad) * radius;
                        const y = Math.sin(angleRad) * radius;

                        card.style.width = cardSize + 'px';
                        card.style.height = cardSize + 'px';
                        card.style.left = `calc(50% + ${x}px)`;
                        card.style.bottom = `${y}px`;
                        card.style.transform = 'translate(-50%, 50%)';
                        card.style.zIndex = count - i;

                        // Rotate the inner card container as in React transform
                        const inner = card.querySelector('.inner-card');
                        if (inner) {
                            inner.style.transform = `rotate(${angle / 4}deg)`;
                        }
                        
                        card.classList.add('animate-fade-in-up');
                    });
                }

                layoutArc();
                window.addEventListener('resize', layoutArc);

                // IntersectionObserver Scroll Entrance
                const observerOptions = {
                    root: null,
                    rootMargin: '0px -60px -60px 0px',
                    threshold: 0.05
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                const animateElements = document.querySelectorAll('.scroll-animate');
                animateElements.forEach(el => {
                    el.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-700', 'ease-out');
                    observer.observe(el);
                });
            });
        </script>
    </body>
</html>
