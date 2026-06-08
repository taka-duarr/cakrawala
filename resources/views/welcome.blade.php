<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CAKRAWALA - Melampaui Nilai, Membentuk Masa Depan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind & Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Franken UI (Shadcn CDN equivalent) -->
        <link rel="stylesheet" href="https://unpkg.com/franken-ui@0.0.12/dist/css/core.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit-icons.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <!-- Navigation -->
        <header class="bg-white border-b sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold tracking-tighter text-blue-600">CAKRAWALA</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#tentang" class="text-gray-600 hover:text-gray-900">Tentang</a>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="uk-button uk-button-default">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="uk-button uk-button-primary">Masuk</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main>
            <div class="relative pt-24 pb-32 flex content-center items-center justify-center min-h-[80vh]">
                <div class="absolute top-0 w-full h-full bg-blue-50/50"></div>
                <div class="container relative mx-auto text-center px-4">
                    <div class="max-w-3xl mx-auto">
                        <h1 class="text-5xl font-extrabold tracking-tight text-gray-900 sm:text-6xl mb-6">
                            Melampaui Nilai, <br>
                            <span class="text-blue-600">Membentuk Masa Depan.</span>
                        </h1>
                        <p class="mt-4 text-xl text-gray-600 mb-10">
                            Platform pendidikan berbasis gamifikasi yang tidak hanya mengukur pencapaian akademik, tetapi juga karakter, kepemimpinan, dan kepedulian sosial siswa.
                        </p>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="uk-button uk-button-primary uk-button-large">Kembali ke Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="uk-button uk-button-primary uk-button-large">Mulai Sekarang</a>
                                <a href="#tentang" class="uk-button uk-button-default uk-button-large ml-4">Pelajari Lebih Lanjut</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="tentang" class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Mengapa CAKRAWALA?</h2>
                        <p class="mt-4 text-lg text-gray-600">Pendidikan yang seimbang menghasilkan manusia yang utuh.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="uk-card uk-card-default uk-card-body border rounded-xl shadow-sm">
                            <div class="mb-4 text-blue-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Sistem Poin Kebaikan</h3>
                            <p class="text-gray-600">Setiap keaktifan, kepedulian sosial, dan kedisiplinan dihargai. Bentuk karakter baik melalui gamifikasi.</p>
                        </div>

                        <div class="uk-card uk-card-default uk-card-body border rounded-xl shadow-sm">
                            <div class="mb-4 text-blue-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Sistem Misi Terarah</h3>
                            <p class="text-gray-600">Quest harian dan mingguan yang dirancang khusus untuk memandu siswa menjadi versi terbaik diri mereka.</p>
                        </div>

                        <div class="uk-card uk-card-default uk-card-body border rounded-xl shadow-sm">
                            <div class="mb-4 text-blue-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Rekomendasi AI</h3>
                            <p class="text-gray-600">Didukung Kecerdasan Buatan untuk memberikan insight personal dan rekomendasi misi spesifik bagi setiap siswa.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-gray-400">© 2026 CAKRAWALA. Membentuk Generasi Masa Depan.</p>
            </div>
        </footer>
    </body>
</html>
