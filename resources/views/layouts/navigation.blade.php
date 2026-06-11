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

<aside class="hidden lg:flex bg-white border-r border-slate-100 flex-col h-screen sticky top-0 z-35 overflow-hidden transition-all duration-300"
    :class="sidebarCollapsed ? 'w-20 p-4' : 'w-72 p-6'">
    <!-- Top Sidebar Section (Logo & Menu) -->
    <div class="flex-1 overflow-y-auto space-y-8 pr-1 -mr-1">
        <!-- Logo -->
        <div class="border-b border-slate-100/80 transition-all duration-300" :class="sidebarCollapsed ? 'pb-3 text-center' : 'pb-5'">
            <a href="{{ route('dashboard') }}" class="flex items-center text-decoration-none" :class="sidebarCollapsed ? 'flex-col space-y-2 justify-center' : 'space-x-3'">
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
        <div class="space-y-6" :class="sidebarCollapsed ? 'border-t border-slate-50 pt-4' : ''">
            <div>
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3 px-3">
                    Menu Utama
                </span>
                <nav class="space-y-1">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ (request()->routeIs('dashboard') || request()->routeIs('*.dashboard')) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Dashboard' : 'delay: 999999'">
                        <span uk-icon="icon: home; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
                    </a>

                    <!-- Leaderboard Link -->
                    <a href="{{ route('leaderboard') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('leaderboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Leaderboard' : 'delay: 999999'">
                        <span uk-icon="icon: star; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Leaderboard</span>
                    </a>

                    <!-- Siswa-specific Links -->
                    @if($roleName === 'siswa')
                        <a href="{{ route('dashboard') }}#quest" class="sidebar-link flex items-center rounded-xl text-xs font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Quest Board' : 'delay: 999999'">
                            <span uk-icon="icon: list; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Quest Board</span>
                        </a>
                        <a href="{{ route('student.my-classes') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('student.my-classes') || request()->routeIs('student.class-detail') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelas Saya' : 'delay: 999999'">
                            <span uk-icon="icon: grid; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelas Saya</span>
                        </a>
                        <a href="{{ route('student.rewards') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('student.rewards') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Toko Hadiah' : 'delay: 999999'">
                            <span uk-icon="icon: cart; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Toko Hadiah</span>
                        </a>
                    @endif

                    <!-- Admin-specific Links -->
                    @if($roleName === 'admin')
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.users.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Sistem' : 'delay: 999999'">
                            <span uk-icon="icon: cog; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Sistem</span>
                        </a>
                        <a href="{{ route('admin.teachers.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.teachers.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Guru' : 'delay: 999999'">
                            <span uk-icon="icon: receiver; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Guru</span>
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.students.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Siswa' : 'delay: 999999'">
                            <span uk-icon="icon: users; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Siswa</span>
                        </a>
                        <a href="{{ route('admin.parents.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.parents.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Manajemen Orang Tua' : 'delay: 999999'">
                            <span uk-icon="icon: happy; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Manajemen Orang Tua</span>
                        </a>
                        <a href="{{ route('admin.classrooms.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.classrooms.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Kelas' : 'delay: 999999'">
                            <span uk-icon="icon: grid; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Kelas</span>
                        </a>
                        <a href="{{ route('admin.jurusans.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.jurusans.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Jurusan' : 'delay: 999999'">
                            <span uk-icon="icon: tag; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Jurusan</span>
                        </a>
                        <a href="{{ route('admin.academic-years.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.academic-years.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Tahun Ajaran & Semester' : 'delay: 999999'">
                            <span uk-icon="icon: calendar; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Tahun Ajaran</span>
                        </a>
                        <a href="{{ route('admin.subjects.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Mata Pelajaran' : 'delay: 999999'">
                            <span uk-icon="icon: bookmark; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Mata Pelajaran</span>
                        </a>
                        <a href="{{ route('admin.teaching-assignments.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.teaching-assignments.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Penugasan Mengajar' : 'delay: 999999'">
                            <span uk-icon="icon: users; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Penugasan Mengajar</span>
                        </a>
                        <a href="{{ route('admin.rewards.manage') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.rewards.manage') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Kelola Toko Hadiah' : 'delay: 999999'">
                            <span uk-icon="icon: settings; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kelola Toko Hadiah</span>
                        </a>

                        <!-- Manajemen Poin Group -->
                        <div x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="pt-2">
                            <span class="block text-[9px] text-slate-300 font-bold uppercase tracking-wider mb-2 px-3.5">Manajemen Poin</span>
                        </div>
                        <a href="{{ route('admin.currency-settings.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.currency-settings.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Pengaturan Poin' : 'delay: 999999'">
                            <span uk-icon="icon: credit-card; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengaturan Poin</span>
                        </a>
                        <a href="{{ route('admin.point-history.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-history.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Histori Transaksi' : 'delay: 999999'">
                            <span uk-icon="icon: history; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Histori Transaksi</span>
                        </a>
                        <a href="{{ route('admin.point-adjust.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-adjust.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Penyesuaian Poin' : 'delay: 999999'">
                            <span uk-icon="icon: plus-circle; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Penyesuaian Poin</span>
                        </a>
                        <a href="{{ route('admin.point-audit.index') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('admin.point-audit.index') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                           :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Audit Poin' : 'delay: 999999'">
                            <span uk-icon="icon: search; ratio: 0.8"></span>
                            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Audit Poin</span>
                        </a>
                    @endif

                    <!-- Profile Link -->
                    <a href="{{ route('profile.edit') }}" class="sidebar-link flex items-center rounded-xl text-xs font-semibold transition {{ request()->routeIs('profile.edit') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Profil Saya' : 'delay: 999999'">
                        <span uk-icon="icon: user; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Profil Saya</span>
                    </a>
                </nav>
            </div>

            <!-- Lainnya Section -->
            <div :class="sidebarCollapsed ? 'border-t border-slate-50 pt-4' : ''">
                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-3 px-3">
                    Lainnya
                </span>
                <nav class="space-y-1 text-xs font-semibold text-slate-500">
                    <a href="{{ route('events') }}" class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('events') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Event' : 'delay: 999999'">
                        <span uk-icon="icon: calendar; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Event</span>
                    </a>
                    <a href="{{ route('announcements') }}" class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('announcements') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                       :class="sidebarCollapsed ? 'justify-center px-0 py-2.5' : 'space-x-3 px-3.5 py-2.5'"
                       :uk-tooltip="sidebarCollapsed ? 'pos: right; title: Pengumuman' : 'delay: 999999'">
                        <span uk-icon="icon: info; ratio: 0.8"></span>
                        <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengumuman</span>
                    </a>
                    <a href="{{ route('help') }}" class="sidebar-link flex items-center rounded-xl transition {{ request()->routeIs('help') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
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
    <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-800 rounded-2xl p-5 text-white shadow-lg shadow-indigo-100/50 mt-auto">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-9 h-9 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center text-white">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
            </div>
            <div>
                <span class="text-[9px] text-indigo-100 block font-semibold uppercase tracking-wider">Level Anda</span>
                <strong class="text-sm font-bold block leading-none">{{ $levelName }}</strong>
            </div>
        </div>
        <div class="space-y-1.5">
            <div class="h-1.5 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
            </div>
            <div class="flex justify-between text-[9px] text-indigo-100 font-semibold">
                <span>{{ $user->points }} / {{ $levelMax }} Pts</span>
                <span>{{ $pointsText }}</span>
            </div>
        </div>
    </div>
    <!-- Collapsed Level Trophy Icon -->
    <div x-show="sidebarCollapsed" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center text-white shadow-md mx-auto mt-auto cursor-pointer" uk-tooltip="pos: right; title: Level {{ $levelName }} ({{ $user->points }} / {{ $levelMax }} Pts)">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6zM19.5 8.25c0-1.518-1.232-2.75-2.75-2.75h-.75V3H8v2.5h-.75C5.732 5.5 4.5 6.732 4.5 8.25v.75c0 1.518 1.232 2.75 2.75 2.75h.75M19.5 8.25v.75c0 1.518-1.232 2.75-2.75 2.75h-.75M9 21h6M12 15v6"></path></svg>
    </div>
    @endif
</aside>
