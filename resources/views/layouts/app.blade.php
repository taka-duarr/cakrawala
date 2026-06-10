<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Shadcn UI CDN (Franken UI) -->
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
        <div class="min-h-screen flex flex-col lg:flex-row" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }">
            
            <!-- Left Sidebar Navigation (Desktop) -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen overflow-x-clip">
                <!-- Top Navbar -->
                <header class="bg-white border-b border-slate-100 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40">
                    <!-- Left: Search / Mobile Toggle -->
                    <div class="flex items-center flex-1">
                        <button class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-700 mr-2" uk-toggle="target: #sidebar-mobile">
                            <span uk-icon="icon: menu; ratio: 1.1"></span>
                        </button>
                        
                        <!-- Desktop Sidebar Toggle Button -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="hidden lg:flex p-2 -ml-2 text-slate-400 hover:text-indigo-600 rounded-xl hover:bg-slate-50 mr-4 transition" title="Toggle Sidebar">
                            <svg class="w-5 h-5 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        
                        <div class="hidden sm:flex items-center bg-slate-100/60 border border-slate-100 rounded-xl px-3.5 py-1.5 w-80">
                            <span uk-icon="icon: search; ratio: 0.8" class="text-slate-400 mr-2"></span>
                            <input type="text" placeholder="Cari misi, teman, lencana..." 
                                class="bg-transparent border-0 border-transparent focus:border-transparent focus:ring-0 text-xs text-slate-600 focus:outline-none w-full placeholder-slate-400 p-0">
                        </div>
                    </div>

                    <!-- Right: Quick Links, Notifications, Profile -->
                    <div class="flex items-center space-x-6">
                        <!-- Quick Links -->
                        <div class="hidden md:flex items-center space-x-5 text-xs font-semibold text-slate-500">
                            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Quest</a>
                            @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
                                <a href="{{ route('student.rewards') }}" class="hover:text-indigo-600 transition">Reward Store</a>
                            @endif
                            <a href="{{ route('leaderboard') }}" class="hover:text-indigo-600 transition">Leaderboard</a>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <a href="{{ route('notifications') }}" class="relative inline-block p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-50 transition" title="Lihat Notifikasi">
                                <span uk-icon="icon: bell; ratio: 0.95"></span>
                                <span class="absolute top-1 right-1 flex h-3 w-3 items-center justify-center rounded-full bg-rose-500 text-[6px] font-bold leading-none text-white ring-[1px] ring-white">3</span>
                            </a>
                        </div>

                        <!-- Profile Info (Logout Form) -->
                        <div class="flex items-center space-x-3 pl-4 border-l border-slate-100">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-700 text-xs shadow-inner">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-[9px] text-slate-400 font-semibold capitalize">{{ auth()->user()->role->display_name ?? 'Siswa' }}</div>
                            </div>
                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                                @csrf
                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition" title="Log Out">
                                    <span uk-icon="icon: sign-out; ratio: 0.9"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Optional Header -->
                @isset($header)
                    <div class="bg-white border-b border-slate-100 py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Main Content -->
                <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Drawer (UIkit off-canvas) -->
        <div id="sidebar-mobile" class="lg:hidden" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar bg-white p-0 w-72">
                <div class="h-full flex flex-col justify-between p-6">
                    <div class="space-y-6">
                        <div class="pb-4 border-b border-slate-100">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-12 w-12 object-contain rounded-xl shadow-sm bg-slate-50 p-1">
                                <div class="text-xl font-extrabold tracking-tight text-slate-800">
                                    CAKRAWALA
                                </div>
                            </div>
                            <div class="text-[10px] text-slate-400 font-medium mt-1">Melampaui Nilai, Membentuk Masa Depan</div>
                        </div>
                        
                        <!-- Navigation Menu (Responsive) -->
                        <nav class="space-y-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ (request()->routeIs('dashboard') || request()->routeIs('*.dashboard')) ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span uk-icon="icon: home; ratio: 0.8"></span>
                                <span>Dashboard</span>
                            </a>
                            
                            <a href="{{ route('leaderboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('leaderboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span uk-icon="icon: star; ratio: 0.8"></span>
                                <span>Leaderboard</span>
                            </a>
                            
                            @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
                                <a href="{{ route('student.rewards') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('student.rewards') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                    <span uk-icon="icon: cart; ratio: 0.8"></span>
                                    <span>Toko Hadiah</span>
                                </a>
                            @endif

                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                <a href="{{ route('admin.rewards.manage') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('admin.rewards.manage') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                    <span uk-icon="icon: settings; ratio: 0.8"></span>
                                    <span>Kelola Toko Hadiah</span>
                                </a>
                            @endif

                            <a href="{{ route('events') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('events') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span uk-icon="icon: calendar; ratio: 0.8"></span>
                                <span>Event</span>
                            </a>

                            <a href="{{ route('announcements') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('announcements') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span uk-icon="icon: info; ratio: 0.8"></span>
                                <span>Pengumuman</span>
                            </a>

                            <a href="{{ route('help') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-semibold {{ request()->routeIs('help') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                                <span uk-icon="icon: question; ratio: 0.8"></span>
                                <span>Bantuan</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
