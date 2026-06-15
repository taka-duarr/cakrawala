<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">Kelola Misi & Event Karakter</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 font-sans">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center space-x-3">
                <span uk-icon="icon: check; ratio: 0.9"></span>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <!-- Section Misi -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Misi Karakter</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium font-sans">Pilih misi di bawah ini untuk memverifikasi kelulusan siswa dan memberikan reward secara manual.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Tipe Misi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                            @forelse($missions as $mission)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $mission->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium max-w-xs truncate" title="{{ $mission->description }}">{{ $mission->description }}</td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $mission->points_reward }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $typeColors = match($mission->type) {
                                            'daily' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                            'weekly' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                            'class' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                            'school' => 'bg-violet-50 text-violet-700 border-violet-100/80',
                                            'special' => 'bg-rose-50 text-rose-700 border-rose-100/80',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $typeColors }}">
                                        {{ $mission->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('guru.missions.award.show', $mission->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-3.5 py-1.5 rounded-xl transition inline-flex items-center space-x-1">
                                        <span uk-icon="icon: check; ratio: 0.65"></span>
                                        <span>Verifikasi & Award</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400 text-xs font-medium font-sans">Belum ada misi yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section Event -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden soft-glow-indigo">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Event Sekolah</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium font-sans">Pilih event sekolah di bawah ini untuk memverifikasi keikutsertaan siswa.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100/80">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Judul Event</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu & Tempat</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Hadiah Poin</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kategori</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70 font-sans">
                            @forelse($events as $event)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 text-xs">{{ $event->title }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium max-w-xs truncate" title="{{ $event->description }}">{{ $event->description }}</td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-semibold leading-relaxed">
                                    <div class="flex items-center space-x-1.5">
                                        <span uk-icon="icon: calendar; ratio: 0.7"></span>
                                        <span>{{ $event->event_date }}</span>
                                    </div>
                                    <div class="flex items-center space-x-1.5 mt-0.5">
                                        <span uk-icon="icon: location; ratio: 0.7"></span>
                                        <span>{{ $event->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-extrabold text-xs text-emerald-600">+{{ $event->points_bonus }} Pts</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $catColors = match($event->category) {
                                            'akademik' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                            'karakter' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                            'sosial' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border capitalize {{ $catColors }}">
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('guru.events.award.show', $event->id) }}" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 text-[10px] font-bold px-3.5 py-1.5 rounded-xl transition inline-flex items-center space-x-1">
                                        <span uk-icon="icon: check; ratio: 0.65"></span>
                                        <span>Verifikasi & Award</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400 text-xs font-medium font-sans">Belum ada event sekolah yang terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
