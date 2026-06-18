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

        @livewireStyles

        <!-- Shadcn UI CDN (Franken UI) -->
        <link rel="stylesheet" href="https://unpkg.com/franken-ui@0.0.12/dist/css/core.min.css" />
        <script data-navigate-once src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
        <script data-navigate-once src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit-icons.min.js"></script>

        <!-- SweetAlert2 CDN -->
        <script data-navigate-once src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
        </style>

        <!-- Alpine Global Store for Sidebar -->
        <script data-navigate-once>
            document.addEventListener('alpine:init', () => {
                Alpine.store('sidebar', {
                    collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
                    toggle() {
                        this.collapsed = !this.collapsed;
                        localStorage.setItem('sidebarCollapsed', this.collapsed);
                    }
                });
            });
        </script>
    </head>
    <body class="font-sans antialiased bg-slate-100/40 text-slate-900">
        <div class="min-h-screen flex flex-col lg:flex-row" x-data>
            
            <!-- Left Sidebar Navigation (Desktop) -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen overflow-x-clip">
                <!-- Top Navbar -->
                <header class="bg-white border-b-2 border-slate-950 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40">
                    <!-- Left: Search / Mobile Toggle -->
                    <div class="flex items-center flex-1">
                        <button class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-700 mr-2" onclick="openMobileMenu()" type="button">
                            <span uk-icon="icon: menu; ratio: 1.1"></span>
                        </button>
                        
                        <!-- Desktop Sidebar Toggle Button -->
                        <button @click="$store.sidebar.toggle()" class="hidden lg:flex p-2 -ml-2 text-slate-400 hover:text-[#E4FF1A] rounded-xl hover:bg-slate-950 mr-4 transition" title="Toggle Sidebar">
                            <svg class="w-5 h-5 transition-transform duration-300" :class="$store.sidebar.collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        
                        <div class="hidden sm:flex items-center bg-white border-2 border-slate-950 rounded-xl px-3.5 py-1.5 w-80 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] focus-within:shadow-[3px_3px_0px_0px_rgba(15,23,42,1)] transition-all">
                            <span uk-icon="icon: search; ratio: 0.8" class="text-slate-400 mr-2"></span>
                            <input type="text" placeholder="Cari misi, teman, lencana..." 
                                class="bg-transparent border-0 border-transparent focus:border-transparent focus:ring-0 text-xs text-slate-600 focus:outline-none w-full placeholder-slate-400 p-0">
                        </div>
                    </div>

                    <!-- Right: Quick Links, Notifications, Profile -->
                    <div class="flex items-center space-x-6">
                        <!-- Quick Links -->
                        <div class="hidden md:flex items-center space-x-5 text-xs font-black uppercase tracking-wider text-slate-500">
                            <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-slate-950 transition">Quest</a>
                            @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
                                <a href="{{ route('student.my-classes') }}" wire:navigate class="hover:text-slate-950 transition">Kelas Saya</a>
                            @endif
                            <a href="{{ route('leaderboard') }}" wire:navigate class="hover:text-slate-950 transition">Leaderboard</a>
                        </div>

                        <!-- Notifications -->
                        <div class="relative">
                            <a href="{{ route('notifications') }}" wire:navigate class="relative inline-block p-1.5 text-slate-400 hover:text-slate-950 hover:bg-slate-100 rounded-lg transition" title="Lihat Notifikasi">
                                <span uk-icon="icon: bell; ratio: 0.95"></span>
                                @php
                                    $unreadNotificationsCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('is_unread', true)->count() : 0;
                                @endphp
                                @if($unreadNotificationsCount > 0)
                                    <span class="absolute top-1 right-1 flex h-3 w-3 items-center justify-center rounded-full bg-rose-500 text-[6px] font-bold leading-none text-white ring-[1px] ring-white">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </a>
                        </div>

                        <!-- Profile Info (Logout Form) -->
                        <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                            <div class="w-8 h-8 bg-[#E4FF1A] border-2 border-slate-950 rounded-full flex items-center justify-center font-black text-slate-950 text-xs shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-black text-slate-950 leading-tight uppercase">{{ auth()->user()->name }}</div>
                                <div class="text-[9px] text-slate-400 font-bold capitalize">{{ auth()->user()->role->display_name ?? 'Siswa' }}</div>
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
                    <div class="bg-white border-b-2 border-slate-950 py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Main Content -->
                <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Mobile Sidebar Drawer (Pure Vanilla JS - no framework) -->
        <!-- Overlay -->
        <div id="mob-overlay" onclick="closeMobileMenu()"
             class="hidden fixed inset-0 bg-slate-900/80 z-50 lg:hidden"></div>

        <!-- Drawer -->
        <div id="mob-drawer"
             class="hidden fixed inset-y-0 left-0 w-72 bg-white shadow-2xl z-50 overflow-y-auto flex flex-col lg:hidden transition-transform transform -translate-x-full">
            
            <div class="p-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10 object-contain rounded-xl shadow-sm bg-slate-50 p-1">
                    <div class="text-lg font-extrabold tracking-tight text-slate-800">CAKRAWALA</div>
                </div>
                <button onclick="closeMobileMenu()" type="button" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ (request()->routeIs('dashboard') || request()->routeIs('*.dashboard')) ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span uk-icon="icon: home; ratio: 0.9"></span>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('leaderboard') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('leaderboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span uk-icon="icon: star; ratio: 0.9"></span>
                    <span>Leaderboard</span>
                </a>
                
                @if(auth()->user()->role && auth()->user()->role->name === 'siswa')
                    <a href="{{ route('student.my-classes') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('student.my-classes') || request()->routeIs('student.class-detail') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: grid; ratio: 0.9"></span>
                        <span>Kelas Saya</span>
                    </a>

                    <a href="{{ route('student.dompet') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('student.dompet') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: credit-card; ratio: 0.9"></span>
                        <span>Dompet Poin</span>
                    </a>
                @endif

                @if(auth()->user()->role && auth()->user()->role->name === 'guru')
                    <a href="{{ route('guru.my-schedule') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('guru.my-schedule') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: calendar; ratio: 0.9"></span>
                        <span>Jadwal Mengajar</span>
                    </a>
                @endif

                @if(auth()->user()->role && auth()->user()->role->name === 'admin')

                    <a href="{{ route('admin.subjects.index') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: bookmark; ratio: 0.9"></span>
                        <span>Mata Pelajaran</span>
                    </a>
                    <a href="{{ route('admin.teaching-assignments.index') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.teaching-assignments.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: users; ratio: 0.9"></span>
                        <span>Penugasan Mengajar</span>
                    </a>
                    <a href="{{ route('admin.toko.index') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.toko.*') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: cart; ratio: 0.9"></span>
                        <span>Manajemen Toko</span>
                    </a>
                    <a href="{{ route('admin.withdrawals.index') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.withdrawals.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: credit-card; ratio: 0.9"></span>
                        <span>Pencairan Dana</span>
                    </a>
                @endif

                @if(auth()->user()->role && auth()->user()->role->name === 'toko')
                    <a href="{{ route('toko.dashboard') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('toko.dashboard') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: cart; ratio: 0.9"></span>
                        <span>Kasir</span>
                    </a>
                    <a href="{{ route('toko.katalog') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('toko.katalog') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: list; ratio: 0.9"></span>
                        <span>Kelola Katalog</span>
                    </a>
                    <a href="{{ route('toko.withdrawals.index') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('toko.withdrawals.index') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                        <span uk-icon="icon: credit-card; ratio: 0.9"></span>
                        <span>Penarikan Dana</span>
                    </a>
                @endif

                <div class="pt-4 pb-2">
                    <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider px-4">Lainnya</span>
                </div>

                <a href="{{ route('events') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('events') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span uk-icon="icon: calendar; ratio: 0.9"></span>
                    <span>Event</span>
                </a>
                <a href="{{ route('announcements') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('announcements') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span uk-icon="icon: info; ratio: 0.9"></span>
                    <span>Pengumuman</span>
                </a>
                <a href="{{ route('help') }}" wire:navigate class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('help') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <span uk-icon="icon: question; ratio: 0.9"></span>
                    <span>Bantuan</span>
                </a>
            </nav>
        </div>

        <script>
            function openMobileMenu() {
                const overlay = document.getElementById('mob-overlay');
                const drawer = document.getElementById('mob-drawer');
                overlay.classList.remove('hidden');
                drawer.classList.remove('hidden');
                // Allow CSS transition to play
                setTimeout(() => {
                    drawer.classList.remove('-translate-x-full');
                }, 10);
                document.body.style.overflow = 'hidden';
            }
            function closeMobileMenu() {
                const overlay = document.getElementById('mob-overlay');
                const drawer = document.getElementById('mob-drawer');
                drawer.classList.add('-translate-x-full');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    drawer.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300); // match transition duration
            }
        </script>

        @livewireScripts
    </body>
</html>
