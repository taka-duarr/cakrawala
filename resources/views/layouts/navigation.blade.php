@php
    $user = auth()->user();
    $roleName = $user->role->name ?? '';
    
    // Level progress calculations
    $levelName = $user->current_level ?? 'Pemula';
    $levelMax = match($levelName) {
        'Pemula' => 100,
        'Berkembang' => 500,
        'Unggul' => 1500,
        'Teladan' => 3000,
        default => 9999,
    };
    $pointsText = match($levelName) {
        'Pemula' => '0 - 100 Point',
        'Berkembang' => '101 - 500 Point',
        'Unggul' => '501 - 1500 Point',
        'Teladan' => '1501 - 3000 Point',
        default => '3000+ Point',
    };
    $pct = $levelMax > 0 ? min(100, ($user->points / $levelMax) * 100) : 100;
@endphp

<aside x-data="{ get sidebarCollapsed() { return $store.sidebar.collapsed } }" class="hidden lg:flex bg-white border-r-2 border-slate-950 flex-col h-screen sticky top-0 z-35 overflow-hidden transition-all duration-300"
    :class="sidebarCollapsed ? 'w-20 p-4' : 'w-72 p-6'">
    <!-- Top Sidebar Section (Logo & Menu) -->
    <div class="flex-1 overflow-y-auto space-y-8 pr-1 -mr-1">
        <!-- Logo -->
        <div class="border-b-2 border-slate-950 transition-all duration-300" :class="sidebarCollapsed ? 'pb-3 text-center' : 'pb-5'">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center text-decoration-none" :class="sidebarCollapsed ? 'flex-col space-y-2 justify-center' : 'space-x-3'">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="object-contain rounded-xl shadow-sm bg-slate-50 p-1 transition-all duration-300" :class="sidebarCollapsed ? 'h-11 w-11' : 'h-14 w-14'">
                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 transform -translate-x-2" x-transition:enter-end="opacity-100 transform translate-x-0" class="text-xl font-extrabold tracking-tight text-slate-800">
                    CAKRAWALA
                </span>
            </a>
            <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="block text-[10px] text-slate-400 font-semibold mt-1.5 uppercase tracking-wider leading-tight">
                Melampaui Nilai, Membentuk Masa Depan
            </span>
        </div>

        <!-- Navigation Menu -->
        <div class="space-y-6" :class="sidebarCollapsed ? 'border-t-2 border-slate-950 pt-4' : ''">
            <div>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3 px-3">
                    Menu Utama
                </span>
                <nav class="space-y-1">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ (request()->routeIs('dashboard') || request()->routeIs('*.dashboard')) ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Dashboard' : 'delay: 999999'">
                        <span uk-icon="icon: home; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
                    </a>

                    <!-- Leaderboard Link -->
                    <a href="{{ route('leaderboard') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('leaderboard') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Leaderboard' : 'delay: 999999'">
                        <span uk-icon="icon: star; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Leaderboard</span>
                    </a>

                    <!-- Siswa-specific Links -->
                    @if($roleName === 'siswa')
                        <a href="{{ route('dashboard') }}#quest" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] transition"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Quest Board' : 'delay: 999999'">
                            <span uk-icon="icon: list; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Quest Board</span>
                        </a>
                        <a href="{{ route('student.my-classes') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('student.my-classes') || request()->routeIs('student.class-detail') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelas Saya' : 'delay: 999999'">
                            <span uk-icon="icon: grid; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelas Saya</span>
                        </a>

                        <a href="{{ route('student.dompet') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('student.dompet') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Dompet Poin' : 'delay: 999999'">
                            <span uk-icon="icon: credit-card; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dompet Poin</span>
                        </a>
                    @endif


                    @if($roleName === 'guru')
                        <a href="{{ route('guru.my-schedule') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('guru.my-schedule') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Jadwal Mengajar' : 'delay: 999999'">
                            <span uk-icon="icon: calendar; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Jadwal Mengajar</span>
                        </a>
                        <a href="{{ route('guru.missions.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('guru.missions.*') || request()->routeIs('guru.events.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Misi & Event' : 'delay: 999999'">
                            <span uk-icon="icon: check; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Misi & Event</span>
                        </a>
                    @endif

                    <!-- Toko-specific Links -->
                    @if($roleName === 'toko')
                        <a href="{{ route('toko.dashboard') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('toko.dashboard') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kasir' : 'delay: 999999'">
                            <span uk-icon="icon: cart; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kasir</span>
                        </a>
                        <a href="{{ route('toko.katalog') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('toko.katalog') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Katalog' : 'delay: 999999'">
                            <span uk-icon="icon: list; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Katalog</span>
                        </a>
                        <a href="{{ route('toko.withdrawals.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('toko.withdrawals.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Penarikan Dana' : 'delay: 999999'">
                            <span uk-icon="icon: credit-card; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Penarikan Dana</span>
                        </a>
                    @endif

                    <!-- Admin-specific Links -->
                    @if($roleName === 'admin')
                        <!-- Data Akademik Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Data Akademik</span>
                        </div>
                        <a href="{{ route('admin.academic-years.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.academic-years.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Tahun Ajaran & Semester' : 'delay: 999999'">
                            <span uk-icon="icon: calendar; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Tahun Ajaran</span>
                        </a>
                        <a href="{{ route('admin.jurusans.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.jurusans.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Jurusan' : 'delay: 999999'">
                            <span uk-icon="icon: tag; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Jurusan</span>
                        </a>
                        <a href="{{ route('admin.classrooms.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.classrooms.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Kelas' : 'delay: 999999'">
                            <span uk-icon="icon: grid; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Kelas</span>
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.subjects.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Mata Pelajaran' : 'delay: 999999'">
                            <span uk-icon="icon: bookmark; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Mata Pelajaran</span>
                        </a>

                        <!-- Manajemen Pengguna Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Manajemen Pengguna</span>
                        </div>
                        <a href="{{ route('admin.users.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.users.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Sistem' : 'delay: 999999'">
                            <span uk-icon="icon: cog; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Sistem</span>
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.teachers.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Guru' : 'delay: 999999'">
                            <span uk-icon="icon: receiver; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Guru</span>
                        </a>
                        <a href="{{ route('admin.students.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.students.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Siswa' : 'delay: 999999'">
                            <span uk-icon="icon: users; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Siswa</span>
                        </a>
                        <a href="{{ route('admin.parents.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.parents.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Orang Tua' : 'delay: 999999'">
                            <span uk-icon="icon: happy; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Orang Tua</span>
                        </a>

                        <!-- Penugasan & Absensi Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Penugasan & Lokasi</span>
                        </div>
                        <a href="{{ route('admin.teaching-assignments.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.teaching-assignments.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Penugasan Mengajar' : 'delay: 999999'">
                            <span uk-icon="icon: users; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Penugasan Mengajar</span>
                        </a>
                        <a href="{{ route('admin.school-locations.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.school-locations.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Lokasi Sekolah' : 'delay: 999999'">
                            <span uk-icon="icon: location; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Lokasi Sekolah</span>
                        </a>

                        <!-- Misi & Event Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Misi & Event</span>
                        </div>
                        <a href="{{ route('admin.missions.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.missions.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Misi' : 'delay: 999999'">
                            <span uk-icon="icon: list; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Misi</span>
                        </a>
                        <a href="{{ route('admin.events.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.events.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Event' : 'delay: 999999'">
                            <span uk-icon="icon: calendar; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Event</span>
                        </a>

                        <!-- Ekonomi & Toko Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Ekonomi & Toko</span>
                        </div>

                        <a href="{{ route('admin.toko.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.toko.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Toko' : 'delay: 999999'">
                            <span uk-icon="icon: cart; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Toko</span>
                        </a>
                        <a href="{{ route('admin.withdrawals.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.withdrawals.*') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Pencairan Dana Toko' : 'delay: 999999'">
                            <span uk-icon="icon: credit-card; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pencairan Dana</span>
                        </a>

                        <!-- Manajemen Poin Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Manajemen Poin</span>
                        </div>
                        <a href="{{ route('admin.currency-settings.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.currency-settings.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Pengaturan Poin' : 'delay: 999999'">
                            <span uk-icon="icon: credit-card; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengaturan Poin</span>
                        </a>
                        <a href="{{ route('admin.point-history.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-history.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Histori Transaksi' : 'delay: 999999'">
                            <span uk-icon="icon: history; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Histori Transaksi</span>
                        </a>
                        <a href="{{ route('admin.point-adjust.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-adjust.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Penyesuaian Poin' : 'delay: 999999'">
                            <span uk-icon="icon: plus-circle; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Penyesuaian Poin</span>
                        </a>
                        <a href="{{ route('admin.point-audit.index') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-audit.index') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Audit Poin' : 'delay: 999999'">
                            <span uk-icon="icon: search; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Audit Poin</span>
                        </a>
                    @endif

                    <!-- Profile Link -->
                    <a href="{{ route('profile.edit') }}" wire:navigate class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Profil Saya' : 'delay: 999999'">
                        <span uk-icon="icon: user; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Profil Saya</span>
                    </a>
                </nav>
            </div>

            <!-- Lainnya Section -->
            <div :class="sidebarCollapsed ? 'border-t-2 border-slate-950 pt-4' : ''">
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3 px-3">
                    Lainnya
                </span>
                <nav class="space-y-1 text-xs font-semibold text-slate-500">
                    <a href="{{ route('events') }}" wire:navigate class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('events') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Event' : 'delay: 999999'">
                        <span uk-icon="icon: calendar; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Event</span>
                    </a>
                    <a href="{{ route('announcements') }}" wire:navigate class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('announcements') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Pengumuman' : 'delay: 999999'">
                        <span uk-icon="icon: info; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengumuman</span>
                    </a>
                    <a href="{{ route('help') }}" wire:navigate class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('help') ? 'bg-[#E4FF1A] text-slate-950 border-2 border-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] font-black' : 'text-slate-700 hover:bg-slate-100 border-2 border-transparent hover:border-slate-950 hover:text-slate-950 hover:shadow-[2px_2px_0px_0px_rgba(15,23,42,1)]' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Bantuan' : 'delay: 999999'">
                        <span uk-icon="icon: question; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Bantuan</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Bottom Sidebar Section (Level Card) -->
    @if($roleName === 'siswa')
    <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="bg-white border-2 border-slate-950 rounded-2xl p-5 text-slate-950 shadow-[4px_4px_0px_0px_rgba(15,23,42,1)] mt-auto">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-9 h-9 bg-[#E4FF1A] border-2 border-slate-950 rounded-xl flex items-center justify-center text-slate-950 shadow-[1.5px_1.5px_0px_0px_rgba(15,23,42,1)]">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
            </div>
            <div>
                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wider">Level Anda</span>
                <strong class="text-sm font-black block leading-none uppercase text-slate-950">{{ $levelName }}</strong>
            </div>
        </div>
        <div class="space-y-1.5">
            <div class="h-2 bg-slate-100 border border-slate-950 rounded-full overflow-hidden">
                <div class="h-full bg-[#E4FF1A] rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
            </div>
            <div class="flex justify-between text-[9px] text-slate-500 font-bold uppercase">
                <span>{{ $user->points }} / {{ $levelMax }} Pts</span>
                <span>{{ $pointsText }}</span>
            </div>
        </div>
    </div>
    <!-- Collapsed Level Trophy Icon -->
    <div x-show="sidebarCollapsed" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="w-11 h-11 bg-[#E4FF1A] border-2 border-slate-950 rounded-xl flex items-center justify-center text-slate-950 shadow-[2px_2px_0px_0px_rgba(15,23,42,1)] hover:-translate-y-0.5 transition mx-auto mt-auto cursor-pointer" uk-tooltip="pos: right; title: Level {{ $levelName }} ({{ $user->points }} / {{ $levelMax }} Pts)">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
    </div>
    @endif
</aside>
